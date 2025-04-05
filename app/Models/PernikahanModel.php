<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PernikahanModel extends Model
{
    use HasFactory;

    protected $table = 't_pernikahan';
    protected $primaryKey = 'pernikahan_id';

    protected $fillable = [
        'nama_lengkap_pria', 
        'nama_lengkap_pria', 
        'tempat_lahir_pria', 
        'tanggal_lahir_pria', 
        'tempat_sidi_pria', 
        'tanggal_sidi_pria', 
        'pekerjaan_pria', 
        'alamat_pria', 
        'nomor_telepon_pria',
        'nama_ayah_pria',
        'nama_ibu_pria',
        'nama_lengkap_wanita', 
        'nama_lengkap_wanita', 
        'tempat_lahir_wanita', 
        'tanggal_lahir_wanita', 
        'tempat_sidi_wanita', 
        'tanggal_sidi_wanita', 
        'pekerjaan_wanita', 
        'alamat_wanita', 
        'nomor_telepon_wanita',
        'nama_ayah_wanita',
        'nama_ibu_wanita',
        'tanggal_pernikahan',
        'waktu_pernikahan',
        'dilayani',
        'ktp',
        'kk',
        'surat_sidi',
        'akta_kelahiran',
        'sk_nikah',
        'sk_asalusul',
        'sp_mempelai',
        'sk_ortu',
        'akta_perceraian_kematian',
        'si_kawin_komandan',
        'sp_gereja_asal',
        'foto',
        'biaya',
        'status',
        'alasan_penolakan',
    ];
}
