<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasilitasCheck extends Model
{
    protected $table = 'fasilitas_check';
    protected $fillable = [
        'user_id',
        'slug',
        'komponen',
        'deskripsi',
        'status',
        'tanggal_cek',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
