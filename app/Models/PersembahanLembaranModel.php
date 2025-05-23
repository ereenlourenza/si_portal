<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersembahanLembaranModel extends Model
{
    use HasFactory;
    
    protected $table = 't_persembahan_lembaran';
    protected $primaryKey = 'persembahan_lembaran_id';
    protected $fillable = [
        'berita_acara_ibadah_id', 'kategori_persembahan_id', 
        'jumlah_100','jumlah_200','jumlah_500','jumlah_1000_koin', 'jumlah_1000_kertas','jumlah_2000','jumlah_5000',
        'jumlah_10000','jumlah_20000','jumlah_50000','jumlah_100000',
        'total_persembahan'
    ];

    public function beritaAcaraIbadah()
    {
        return $this->belongsTo(BeritaAcaraIbadahModel::class, 'berita_acara_ibadah_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPersembahanModel::class, 'kategori_persembahan_id');
    }
}
