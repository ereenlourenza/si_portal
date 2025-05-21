<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuanganModel extends Model
{
    use HasFactory;
    
    protected $table = 't_ruangan'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'ruangan_id'; //mendefinisikan primary key dari tabel yang digunakan

    protected $fillable = ['ruangan_nama','deskripsi','foto'];
}
