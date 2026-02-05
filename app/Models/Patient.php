<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_rekam_medis',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir?->age;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            $patient->no_rekam_medis = 'RM-'.date('Ymd').'-'.str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}
