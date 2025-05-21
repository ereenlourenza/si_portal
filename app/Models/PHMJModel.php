<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PHMJModel extends Model
{
    use HasFactory;
    
    protected $table = 't_phmj';
    protected $primaryKey = 'phmj_id';
    protected $fillable = ['pelayan_id', 'jabatan', 'periode_mulai', 'periode_selesai'];

    public function pelayan() {
        return $this->belongsTo(PelayanModel::class, 'pelayan_id', 'pelayan_id');
    }
}
