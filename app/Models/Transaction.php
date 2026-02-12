<?php

namespace App\Models;

use App\Models\TransactionDetail;
use App\Models\Visit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'total_biaya',
        'metode_pembayaran',
        'status',
        'bukti_pembayaran',
        'created_by',
        'confirmed_by',
        'confirmed_at',
        'cancelled_by',
        'cancelled_at'
    ];

    protected $casts = [
        'total_biaya' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Relasi ke user yang membuat transaksi
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang mengkonfirmasi pembayaran
    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    // Relasi ke user yang membatalkan transaksi
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // Boot method untuk auto-fill created_by
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check() && !$model->created_by) {
                $model->created_by = auth()->id();
            }
        });
    }

    // Scopes
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeBatal($query)
    {
        return $query->where('status', 'batal');
    }
}