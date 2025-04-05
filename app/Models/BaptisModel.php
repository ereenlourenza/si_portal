<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaptisModel extends Model
{
    use HasFactory;

    protected $table = 't_baptis';
    protected $primaryKey = 'baptis_id';

    protected $fillable = [
        'nama_lengkap', 
        'tempat_lahir', 
        'tanggal_lahir', 
        'jenis_kelamin', 
        'nama_ayah', 
        'nama_ibu', 
        'tempat_pernikahan', 
        'tanggal_pernikahan', 
        'tempat_sidi_ayah', 
        'tanggal_sidi_ayah', 
        'tempat_sidi_ibu', 
        'tanggal_sidi_ibu', 
        'alamat', 
        'nomor_telepon', 
        'tanggal_baptis', 
        'dilayani', 
        'surat_nikah_ortu', 
        'akta_kelahiran_anak',
        'status',
        'alasan_penolakan'
    ];
}
