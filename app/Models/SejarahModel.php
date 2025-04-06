<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SejarahModel extends Model
{
    use HasFactory;

    protected $table = 't_sejarah';
    protected $primaryKey = 'sejarah_id';

    protected $fillable = [
        'judul_subbab',
        'isi_konten',
    ];
}
