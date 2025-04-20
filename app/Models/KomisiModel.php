<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomisiModel extends Model
{
    use HasFactory;

    protected $table = 't_komisi';
    protected $primaryKey = 'komisi_id'; //mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['komisi_nama', 'deskripsi'];
}
