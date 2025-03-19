<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IbadahModel extends Model
{
    protected $table = 't_ibadah'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'ibadah_id'; //mendefinisikan primary key dari tabel yang digunakan
    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['kategoriibadah_id','tanggal','waktu','tempat','lokasi','sektor','nama_pelkat','ruang','pelayan_firman'];

    public function kategoriibadah(): BelongsTo{
        return $this->belongsTo(KategoriIbadahModel::class, 'kategoriibadah_id', 'kategoriibadah_id');
    }
}
