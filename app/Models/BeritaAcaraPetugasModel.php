<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraPetugasModel extends Model
{
    use HasFactory;
    
    protected $table = 't_berita_acara_petugas';
    protected $primaryKey = 'berita_acara_petugas_id';
    protected $fillable = ['berita_acara_ibadah_id', 'peran', 'pelayan_id_jadwal', 'pelayan_id_hadir'];
    
    public function beritaAcaraIbadah()
    {
        return $this->belongsTo(BeritaAcaraIbadahModel::class, 'berita_acara_ibadah_id');
    }

    public function pelayanJadwal()
    {
        return $this->belongsTo(PelayanModel::class, 'pelayan_id_jadwal', 'pelayan_id');
    }

    public function pelayanHadir()
    {
        return $this->belongsTo(PelayanModel::class, 'pelayan_id_hadir', 'pelayan_id');
    }
}
