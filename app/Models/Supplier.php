<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat_supplier',
        'nama_bb',
        'no_hp'
    ];
    
    public function bahan_masuk()
    {
        return $this->hasMany(BahanMasuk::class, 'supplier_id');
    }
}
