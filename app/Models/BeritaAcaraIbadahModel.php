<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraIbadahModel extends Model
{
    protected $table = 't_berita_acara_ibadah';
    protected $primaryKey = 'berita_acara_ibadah_id';
    protected $fillable = ['ibadah_id', 'bacaan_alkitab', 'jumlah_kehadiran', 'catatan', 'ttd_pelayan_1_id', 'ttd_pelayan_4_id', 'ttd_pelayan_1_img', 'ttd_pelayan_4_img'];
    
    public function ibadah()
    {
        return $this->belongsTo(IbadahModel::class, 'ibadah_id', 'ibadah_id');
    }

    public function pelayan1()
    {
        return $this->belongsTo(PelayanModel::class, 'ttd_pelayan_1_id', 'pelayan_id');
    }

    public function pelayan4()
    {
        return $this->belongsTo(PelayanModel::class, 'ttd_pelayan_4_id', 'pelayan_id');
    }

    public function petugas()
    {
        return $this->hasMany(BeritaAcaraPetugasModel::class, 'berita_acara_ibadah_id');
    }

    public function persembahan()
    {
        return $this->hasMany(BeritaAcaraPersembahanModel::class, 'berita_acara_ibadah_id');
    }

    public function lembaran()
    {
        return $this->hasMany(PersembahanLembaranModel::class, 'berita_acara_ibadah_id');
    }
}
