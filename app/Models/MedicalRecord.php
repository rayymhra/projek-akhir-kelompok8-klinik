<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'keluhan',
        'diagnosa',
        'tindakan',
        'catatan'
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}