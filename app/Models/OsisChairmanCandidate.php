<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsisChairmanCandidate extends Model
{
    use HasFactory;

    public function totalVotes()
    {
        return $this->hasMany(TotalVoteUser::class, 'candidate_id');
    }
}
