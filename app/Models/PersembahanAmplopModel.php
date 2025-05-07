<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersembahanAmplopModel extends Model
{
    protected $table = 't_persembahan_amplop';
    protected $primaryKey = 'persembahan_amplop_id';
    protected $fillable = ['berita_acara_persembahan_id', 'no_amplop', 'nama_pengguna_amplop', 'jumlah'];

    public function beritaAcaraPersembahan()
    {
        return $this->belongsTo(BeritaAcaraPersembahanModel::class, 'berita_acara_persembahan_id');
    }
}
