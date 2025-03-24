<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelkatModel extends Model
{
    protected $table = 't_pelkat';
    protected $primaryKey = 'pelkat_id'; //mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['pelkat_nama', 'deskripsi'];


    // Scope untuk mendapatkan Pelkat yang memiliki pengurus aktif
    public function scopeDenganPengurusAktif($query)
    {
        return $query->whereHas('pengurus', function ($q) {
            $q->aktif();
        });
    }
}
