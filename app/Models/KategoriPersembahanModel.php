<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPersembahanModel extends Model
{
    use HasFactory;
    
    protected $table = 't_kategori_persembahan';
    protected $primaryKey = 'kategori_persembahan_id';
    protected $fillable = ['kategori_persembahan_nama'];

    public function persembahan()
    {
        return $this->hasMany(BeritaAcaraPersembahanModel::class, 'kategori_persembahan_id');
    }
}
