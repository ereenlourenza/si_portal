<?php

namespace App\Exports;

use App\Models\BeritaAcaraIbadahModel;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class BeritaAcaraSingleExport implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        // Ambil data berdasarkan ID
        $berita = BeritaAcaraIbadahModel::with([
            'ibadah', 'petugas.pelayanJadwal', 'petugas.pelayanHadir',
            'persembahan.kategori', 'persembahan.amplop', 'persembahan.lembaran'
        ])->findOrFail($this->id);

        return view('beritaacara.exportExcelSingle', compact('berita'));
    }
}
