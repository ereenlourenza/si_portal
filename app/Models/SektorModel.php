<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SektorModel extends Model
{
    protected $table = 't_sektor'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'sektor_id'; //mendefinisikan primary key dari tabel yang digunakan
    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['sektor_nama','deskripsi', 'jumlah_jemaat', 'pelayan_id'];

    public function pelayan(): BelongsTo{
        return $this->belongsTo(PelayanModel::class, 'pelayan_id', 'pelayan_id');
    }
}
