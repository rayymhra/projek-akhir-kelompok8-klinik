<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_rekam_medis',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'no_hp_keluarga',
        'email',
        'nik',
        'no_bpjs',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'golongan_darah',
        'alergi',
        'riwayat_penyakit',
        'nama_keluarga',
        'hubungan_keluarga',
        'pekerjaan',
        'status_pernikahan',
        'catatan',
        'is_active'
    ];

    protected $appends = ['umur', 'alamat_lengkap'];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function medicalRecords()
    {
        return $this->hasManyThrough(MedicalRecord::class, Visit::class);
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir?->age;
    }

    public function getAlamatLengkapAttribute()
    {
        $alamat = $this->alamat;
        
        if ($this->rt && $this->rw) {
            $alamat .= ", RT {$this->rt}/RW {$this->rw}";
        }
        
        if ($this->kelurahan) {
            $alamat .= ", Kel. {$this->kelurahan}";
        }
        
        if ($this->kecamatan) {
            $alamat .= ", Kec. {$this->kecamatan}";
        }
        
        if ($this->kota) {
            $alamat .= ", {$this->kota}";
        }
        
        if ($this->provinsi) {
            $alamat .= ", {$this->provinsi}";
        }
        
        if ($this->kode_pos) {
            $alamat .= " {$this->kode_pos}";
        }
        
        return $alamat;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            $patient->no_rekam_medis = 'RM-' . date('Ymd') . '-' . str_pad(Patient::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    

    
}