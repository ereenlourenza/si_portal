<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriGaleriModel extends Model
{
    protected $table = 't_kategorigaleri'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'kategorigaleri_id'; //mendefinisikan primary key dari tabel yang digunakan
    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['kategorigaleri_kode','kategorigaleri_nama'];
}
