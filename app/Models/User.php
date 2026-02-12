<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function doctorVisits()
    {
        return $this->hasMany(Visit::class, 'doctor_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    public function visits()
{
    return $this->hasMany(Visit::class, 'doctor_id');
}
}