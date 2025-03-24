<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersembahanModel extends Model
{
    protected $table = 't_persembahan'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'persembahan_id'; //mendefinisikan primary key dari tabel yang digunakan

    protected $fillable = ['persembahan_nama','nomor_rekening','atas_nama','barcode'];
}
