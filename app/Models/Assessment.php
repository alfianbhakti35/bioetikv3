<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name_assessment',
        'skor',
        'keteranagan'
    ];


    public function assessment()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
