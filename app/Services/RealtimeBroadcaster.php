<?php

namespace App\Services;

use App\Models\OsisChairmanCandidate;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RealtimeBroadcaster
{
    /**
     * Dapatkan payload agregat hasil perolehan suara terkini.
     */
    public static function getCurrentVotePayload(): array
    {
        $totalVoters = User::where('role', 2)->count();
        $totalVoted = User::where('role', 2)->where('vote_status', 1)->count();
        $totalUnvoted = max(0, $totalVoters - $totalVoted);
        $turnout = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;
        $unvotedPct = $totalVoters > 0 ? round(($totalUnvoted / $totalVoters) * 100, 1) : 0;

        $candidates = OsisChairmanCandidate::withCount('totalVotes')
            ->orderBy('sequence_number', 'asc')
            ->get()
            ->map(function ($c) use ($totalVoted, $totalVoters) {
                $voteCount = $c->total_votes_count ?? 0;
                $pct = $totalVoted > 0 ? round(($voteCount / $totalVoted) * 100, 1) : 0;
                $pctDpt = $totalVoters > 0 ? round(($voteCount / $totalVoters) * 100, 1) : 0;
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'vice_name' => $c->vice_name,
                    'sequence_number' => $c->sequence_number,
                    'class' => $c->class,
                    'vice_class' => $c->vice_class,
                    'slogan' => $c->slogan,
                    'work_program' => $c->work_program,
                    'image' => $c->image,
                    'votes' => $voteCount,
                    'percentage' => $pct,
                    'percentage_dpt' => $pctDpt,
                ];
            });

        return [
            'status' => 'success',
            'candidates' => $candidates,
            'summary' => [
                'total_voters' => $totalVoters,
                'total_voted' => $totalVoted,
                'total_unvoted' => $totalUnvoted,
                'turnout_percentage' => $turnout,
                'unvoted_percentage' => $unvotedPct,
            ],
            'last_updated' => now()->format('H:i:s'),
        ];
    }

    /**
     * Pancarkan pembaruan suara ke microservice Node.js Socket.io secara non-blocking.
     */
    public static function broadcast(?array $payload = null): bool
    {
        try {
            if ($payload === null) {
                $payload = self::getCurrentVotePayload();
            }

            $nodeServerUrl = env('REALTIME_SERVER_URL', 'http://127.0.0.1:3001');
            $secretKey = env('REALTIME_SECRET', 'pemilos_secret_broadcast_key_2026');

            // Kirim request ke microservice Node.js dengan timeout ketat (500ms) agar tidak membebani proses voting
            $response = Http::timeout(0.5)
                ->withHeaders([
                    'x-realtime-secret' => $secretKey,
                    'Accept' => 'application/json',
                ])
                ->post("{$nodeServerUrl}/broadcast", $payload);

            return $response->successful();
        } catch (\Throwable $e) {
            // Kegagalan pengiriman ke socket server TIDAK BOLEH membatalkan suara siswa yang sudah sah di database
            Log::debug('RealtimeBroadcaster: Socket server offline or timeout. Graceful fallback will handle clients. Error: ' . $e->getMessage());
            return false;
        }
    }
}
