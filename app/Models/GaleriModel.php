<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriModel extends Model
{
    protected $table = 't_galeri'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'galeri_id'; //mendefinisikan primary key dari tabel yang digunakan
    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['kategorigaleri_id','judul','deskripsi','foto'];

    public function kategorigaleri(): BelongsTo{
        return $this->belongsTo(KategoriGaleriModel::class, 'kategorigaleri_id', 'kategorigaleri_id');
    }
}
