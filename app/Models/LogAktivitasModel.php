<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitasModel extends Model
{
    use HasFactory;
    
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id', 'modul', 'aksi', 'aktivitas', 'ip_address', 'user_agent'
    ];

    public function user() {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}
