<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisResult extends Model
{
    use HasFactory;

    public function addictionLevel()
    {
        return $this->belongsTo(AddictionLevel::class, 'addiction_level_id');
    }
}
