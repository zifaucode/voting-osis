<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsisChairmanCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vice_name',
        'sequence_number',
        'class',
        'vice_class',
        'slogan',
        'visi',
        'misi',
        'work_program',
        'image',
        'vote_total',
    ];

    public function totalVotes()
    {
        return $this->hasMany(TotalVoteUser::class, 'candidate_id');
    }
}
