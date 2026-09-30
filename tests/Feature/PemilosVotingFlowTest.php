<?php

namespace Tests\Feature;

use App\Models\OsisChairmanCandidate;
use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PemilosVotingFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Bersihkan cache sebelum setiap test
        Cache::flush();

        // Reset vote status pemilih testing & truncate suara
        User::where('nisn', 'like', 'NIS%')->update([
            'vote_status' => 0,
            'is_vote' => null,
        ]);
        \App\Models\TotalVoteUser::truncate();
    }

    /**
     * Test: Siswa dapat login dengan NISN dan kode akses yang valid.
     */
    public function test_voter_can_login_successfully(): void
    {
        $voter = User::where('nisn', 'NIS0001')->first();
        $this->assertNotNull($voter, 'User NIS0001 harus ada di database');

        $response = $this->post(route('auth.user'), [
            'nisn' => 'NIS0001',
            'code_access' => 'PASS0001',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($voter);

        // Pastikan session id tercatat di Cache untuk proteksi anti-double login
        $activeSession = Cache::get('active_voter_' . $voter->id);
        $this->assertNotEmpty($activeSession);
    }

    /**
     * Test: Anti-Double Login mencegah PC lain login ke akun yang sedang aktif di bilik pertama.
     */
    public function test_anti_double_login_blocks_concurrent_session(): void
    {
        $voter = User::where('nisn', 'NIS0002')->first();

        // Simulasikan akun ini sudah aktif di bilik A
        Cache::put('active_voter_' . $voter->id, 'session_bilik_A', now()->addMinutes(5));
        Cache::put('active_voter_time_' . $voter->id, now(), now()->addMinutes(5));

        // Percobaan login dari bilik B (session berbeda)
        $response = $this->withSession(['different_session' => true])
            ->post(route('auth.user'), [
                'nisn' => 'NIS0002',
                'code_access' => 'PASS0002',
            ]);

        // Harus dialihkan kembali dengan pesan error
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    /**
     * Test: Siswa dapat memberikan hak suara (vote) secara sah ke paslon pilihan.
     */
    public function test_voter_can_cast_vote_successfully(): void
    {
        $voter = User::where('nisn', 'NIS0003')->first();
        $candidate = OsisChairmanCandidate::first();
        $initialVoteCount = $candidate->totalVotes()->count();

        // Pastikan tidak ada sesi bilik asing di cache
        Cache::forget('active_voter_' . $voter->id);

        // Kirim suara sebagai user yang sedang login di bilik
        $response = $this->actingAs($voter)->post(route('result.store'), [
            'candidate_id' => $candidate->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'error' => false,
            'code' => 200,
        ]);

        // Verifikasi perubahan di database
        $voter->refresh();
        $this->assertEquals(1, $voter->vote_status, 'vote_status harus menjadi 1');
        $this->assertEquals($candidate->id, $voter->is_vote, 'is_vote harus sesuai candidate_id');
        $this->assertEquals($initialVoteCount + 1, $candidate->totalVotes()->count(), 'Suara paslon harus bertambah 1');
    }

    /**
     * Test: Pemilih yang sudah pernah memilih dilarang memilih kedua kalinya (Pessimistic lock check).
     */
    public function test_voter_cannot_vote_twice(): void
    {
        $voter = User::where('nisn', 'NIS0004')->first();
        // Set user sudah vote
        $voter->vote_status = 1;
        $voter->is_vote = 1;
        $voter->save();

        Cache::forget('active_voter_' . $voter->id);

        // Percobaan voting ulang
        $response = $this->actingAs($voter)->post(route('result.store'), [
            'candidate_id' => 1,
        ]);

        $response->assertStatus(400);
        $this->assertTrue($response->json('error'));
        $this->assertStringContainsString('sudah pernah menggunakan hak suara', $response->json('message'));
    }

    /**
     * Test: Pemilih yang sudah pernah memilih dilarang login kembali.
     */
    public function test_voter_who_voted_cannot_login_again(): void
    {
        $voter = User::where('nisn', 'NIS0005')->first();
        $voter->vote_status = 1;
        $voter->is_vote = 1;
        $voter->save();

        $response = $this->post(route('auth.user'), [
            'nisn' => 'NIS0005',
            'code_access' => 'PASS0005',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    /**
     * Test: Halaman ShowOff (Aula) menggunakan Cache dan tidak error.
     */
    public function test_show_off_page_loads_and_uses_cache(): void
    {
        $response = $this->get(route('dashboard.showoff'));
        $response->assertStatus(200);

        // Pastikan key cache telah terisi
        $this->assertTrue(Cache::has('pemilos_web_setting'));
        $this->assertTrue(Cache::has('pemilos_candidates_list'));
    }
}
