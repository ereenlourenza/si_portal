<?php

namespace App\Exports;

use App\Models\BeritaAcaraIbadahModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PersembahanExport implements WithMultipleSheets
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Ambil berita acara dan relasi persembahan
        $berita = BeritaAcaraIbadahModel::with(['persembahan.kategori', 'persembahan.amplop', 'persembahan.lembaran1'])
            ->where('berita_acara_ibadah_id', $this->id)
            ->first();

            // dd($berita->persembahan->toArray());

        $grouped = $berita->persembahan->groupBy('kategori.kategori_persembahan_nama');

        foreach ($grouped as $kategoriNama => $persembahanItems) {
            $firstItem = $persembahanItems->first();
            $jenis = $firstItem->jenis_input;
        
            if ($jenis === 'amplop') {
                $columnsUsed = ['Jenis Input', 'No Amplop', 'Nama Pengguna', 'Jumlah'];
            } elseif ($jenis === 'lembaran') {
                $columnsUsed = ['Jenis Input', 'Nominal', 'Jumlah Lembar', 'Subtotal'];
            } elseif ($jenis === 'langsung') {
                $columnsUsed = ['Jenis Input', 'Total'];
            } else {
                $columnsUsed = ['Jenis Input', 'Data']; // fallback
            }
        
            $sheets[] = new KategoriSheetExport($kategoriNama, $persembahanItems, $columnsUsed);
        }

        return $sheets;
    }
}
