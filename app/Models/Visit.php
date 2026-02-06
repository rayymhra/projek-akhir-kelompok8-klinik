<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'tanggal_kunjungan',
        'status',
        'nomor_antrian',
        'prefix_antrian',
        'waktu_dipanggil',
        'waktu_selesai',
        'poli',
        'prioritas'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'waktu_dipanggil' => 'datetime',
        'waktu_selesai' => 'datetime'
    ];

    // Helper method to get full queue number
    public function getNomorAntrianFullAttribute()
    {
        if ($this->prefix_antrian && $this->nomor_antrian) {
            return $this->prefix_antrian . '-' . str_pad($this->nomor_antrian, 3, '0', STR_PAD_LEFT);
        }
        return str_pad($this->nomor_antrian, 3, '0', STR_PAD_LEFT);
    }

    // Scope for today's visits
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_kunjungan', today());
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    
}