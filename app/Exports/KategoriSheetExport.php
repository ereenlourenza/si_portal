<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KategoriSheetExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $kategoriNama;
    protected $persembahanItems;
    protected $columnsUsed;

    public function __construct($kategoriNama, $persembahanItems, array $columnsUsed)
    {
        $this->kategoriNama = $kategoriNama;
        $this->persembahanItems = $persembahanItems;
        $this->columnsUsed = $columnsUsed;
    }

    public function collection()
    {
        $rows = [];

        foreach ($this->persembahanItems as $item) {
            if ($item->jenis_input == 'amplop') {
                $totalAmplop = 0;
                
                foreach ($item->amplop as $amplop) {
                    $rows[] = [
                        'Jenis Input' => 'Amplop',
                        'No Amplop' => $amplop->no_amplop,
                        'Nama' => $amplop->nama_pengguna_amplop,
                        'Jumlah' => $amplop->jumlah,
                    ];
                    // Tambahkan ke total amplop
                    $totalAmplop += $amplop->jumlah;
                }
                // $rows[] = [
                //     'Jenis Input' => 'Total',
                //     'No Amplop' => '',
                //     'Nama' => '',
                //     'Jumlah' => $totalAmplop,
                // ];

            } elseif ($item->jenis_input == 'lembaran') {
                // Filter data lembaran khusus untuk kategori ini
                $lembaranData = collect($item->lembaran1 ?? [])->where('kategori_persembahan_id', $item->kategori_persembahan_id)->all();
            
                $rows = [];

                $totalLembaran = 0;
            
                if (!empty($lembaranData)) {
                    foreach ($lembaranData as $lembaran) {
                        $denominations = [
                            100 => $lembaran['jumlah_100'] ?? 0,
                            200 => $lembaran['jumlah_200'] ?? 0,
                            500 => $lembaran['jumlah_500'] ?? 0,
                            1000 => $lembaran['jumlah_1000'] ?? 0,
                            2000 => $lembaran['jumlah_2000'] ?? 0,
                            5000 => $lembaran['jumlah_5000'] ?? 0,
                            10000 => $lembaran['jumlah_10000'] ?? 0,
                            20000 => $lembaran['jumlah_20000'] ?? 0,
                            50000 => $lembaran['jumlah_50000'] ?? 0,
                            100000 => $lembaran['jumlah_100000'] ?? 0,
                        ];
            
                        if (!isset($rows[$item->kategori_persembahan_id])) {
                            $rows[$item->kategori_persembahan_id] = [];
                        }
            
                        foreach ($denominations as $nominal => $jumlah) {
                            if ($jumlah > 0) {
                                $existingRow = array_filter($rows[$item->kategori_persembahan_id], function ($row) use ($nominal) {
                                    return $row['Nominal'] == $nominal;
                                });
            
                                if (empty($existingRow)) {
                                    $rows[$item->kategori_persembahan_id][] = [
                                        'Jenis Input' => 'Lembaran',
                                        'Nominal' => $nominal,
                                        'Jumlah Lembar' => $jumlah,
                                        'Subtotal' => $nominal * $jumlah,
                                    ];
                                } else {
                                    $index = array_key_first($existingRow);
                                    $rows[$item->kategori_persembahan_id][$index]['Jumlah Lembar'] += $jumlah;
                                    $rows[$item->kategori_persembahan_id][$index]['Subtotal'] = $nominal * $rows[$item->kategori_persembahan_id][$index]['Jumlah Lembar'];
                                }

                                // Tambahkan ke total lembaran
                                $totalLembaran += $nominal * $jumlah;
                            }
                        }
                        $rows[$item->kategori_persembahan_id][] = [
                            'Jenis Input' => 'Total',
                            'Nominal' => '',
                            'Jumlah Lembar' => '',
                            'Subtotal' => $totalLembaran,
                        ];
                    }
                }
            
                // dd($rows);
            } elseif ($item->jenis_input == 'langsung') {
                $rows[] = [
                    'Jenis Input' => 'Langsung',
                    'Total' => $item->total,
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return $this->columnsUsed;
    }

    public function title(): string
    {
        return $this->kategoriNama;
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumnIndex = count($this->columnsUsed);
        $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColumnIndex);

        $sheet->getStyle("A1:{$lastColumnLetter}1")->getFont()->setBold(true);
        $sheet->getStyle("A:{$lastColumnLetter}")->getAlignment()->setHorizontal('center');
    }
}
