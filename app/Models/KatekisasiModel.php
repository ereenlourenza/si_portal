<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatekisasiModel extends Model
{
    use HasFactory;

    protected $table = 't_katekisasi';
    protected $primaryKey = 'katekisasi_id';

    protected $fillable = [
        'nama_lengkap', 
        'tempat_lahir', 
        'tanggal_lahir', 
        'jenis_kelamin', 
        'alamat_katekumen', 
        'nomor_telepon_katekumen', 
        'pendidikan_terakhir', 
        'pekerjaan', 
        'is_baptis',
        'tempat_baptis',
        'no_surat_baptis',
        'tanggal_surat_baptis',
        'dilayani',
        'nama_ayah', 
        'nama_ibu', 
        'alamat_ortu', 
        'nomor_telepon_ortu', 
        'akta_kelahiran', 
        'surat_baptis', 
        'pas_foto', 
        'status',
        'alasan_penolakan'
    ];

}
