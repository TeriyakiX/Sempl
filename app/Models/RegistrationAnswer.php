<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer',
    ];

    // Связь с вопросами
    public function question()
    {
        return $this->belongsTo(RegistrationQuestion::class, 'question_id');
    }
}
