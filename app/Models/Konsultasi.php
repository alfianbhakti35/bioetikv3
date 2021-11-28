<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'rekam_medik_id',
        'rekam_medik',
        'status',
        'jadwal'
    ];

    public function konselor()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }
}
