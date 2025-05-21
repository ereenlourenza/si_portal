<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelayanModel extends Model
{
    use HasFactory;
    
    protected $table = 't_kategoripelayan'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'kategoripelayan_id'; //mendefinisikan primary key dari tabel yang digunakan
    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['kategoripelayan_kode','kategoripelayan_nama'];

}
