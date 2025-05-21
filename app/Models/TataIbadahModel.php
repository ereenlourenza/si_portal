<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TataIbadahModel extends Model
{
    use HasFactory;
    
    protected $table = 't_tataibadah'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'tataibadah_id'; //mendefinisikan primary key dari tabel yang digunakan
    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['tanggal','judul','deskripsi','file'];

}
