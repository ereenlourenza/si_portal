<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class BeritaAcaraPersembahanModel extends Model
{
    protected $table = 't_berita_acara_persembahan';
    protected $primaryKey = 'berita_acara_persembahan_id';
    protected $fillable = ['berita_acara_ibadah_id', 'kategori_persembahan_id', 'jenis_input', 'total'];

    public function kategori()
    {
        return $this->belongsTo(KategoriPersembahanModel::class, 'kategori_persembahan_id', 'kategori_persembahan_id');
    }

    public function beritaAcaraIbadah()
    {
        return $this->belongsTo(BeritaAcaraIbadahModel::class, 'berita_acara_ibadah_id');
    }

    public function amplop()
    {
        return $this->hasMany(PersembahanAmplopModel::class, 'berita_acara_persembahan_id');
    }

    public function lembaran()
    {
        return $this->hasMany(PersembahanLembaranModel::class, 'berita_acara_ibadah_id', 'berita_acara_ibadah_id')
                    ->where('kategori_persembahan_id', $this->kategori_persembahan_id);
    }

    public function lembaran1()
    {
        return $this->hasMany(PersembahanLembaranModel::class, 'berita_acara_ibadah_id', 'berita_acara_ibadah_id')
                    ->whereColumn('kategori_persembahan_id', 'kategori_persembahan_id');
    }



}
