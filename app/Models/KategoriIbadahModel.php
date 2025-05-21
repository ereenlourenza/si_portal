<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriIbadahModel extends Model
{
    use HasFactory;
    
    protected $table = 't_kategoriibadah'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'kategoriibadah_id'; //mendefinisikan primary key dari tabel yang digunakan
    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['kategoriibadah_kode','kategoriibadah_nama'];

}
