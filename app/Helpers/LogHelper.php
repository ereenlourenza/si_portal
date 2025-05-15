<?php

use App\Models\LogAktivitas;
use App\Models\LogAktivitasModel;
use Illuminate\Support\Facades\Auth;

if (!function_exists('simpanLogAktivitas')) {
    function simpanLogAktivitas($modul, $aksi, $aktivitas)
    {
        if (Auth::check()) {
            LogAktivitasModel::create([
                'user_id'    => Auth::id(),
                'modul'      => $modul,
                'aksi'       => $aksi,
                'aktivitas'  => $aktivitas,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
