<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanRuanganModel extends Model
{
    protected $table = 't_peminjamanruangan'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'peminjamanruangan_id'; //mendefinisikan primary key dari tabel yang digunakan

    protected $fillable = ['peminjam_nama','peminjam_telepon','tanggal','waktu_mulai','waktu_selesai','ruangan_id','keperluan','status','alasan_penolakan'];

    public function ruangan(): BelongsTo{
        return $this->belongsTo(RuanganModel::class, 'ruangan_id', 'ruangan_id');
    }
}
