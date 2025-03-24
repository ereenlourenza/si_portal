<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelayanModel extends Model
{
    protected $table = 't_pelayan'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'pelayan_id'; //mendefinisikan primary key dari tabel yang digunakan
    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['kategoripelayan_id', 'pelkat_id', 'nama', 'foto','masa_jabatan_mulai','masa_jabatan_selesai','keterangan'];

    public function kategoripelayan(): BelongsTo{
        return $this->belongsTo(KategoriPelayanModel::class, 'kategoripelayan_id', 'kategoripelayan_id');
    }

    public function pelkat(): BelongsTo{
        return $this->belongsTo(PelkatModel::class, 'pelkat_id', 'pelkat_id');
    }

    // Relasi ke PHMJ
    public function phmj() {
        return $this->hasOne(PHMJModel::class, 'pelayan_id', 'pelayan_id');
    }
}
