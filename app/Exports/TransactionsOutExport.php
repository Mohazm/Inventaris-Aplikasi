<?php

namespace App\Exports;

use App\Models\Transactions_out;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsOutExport implements FromQuery, WithHeadings, WithStyles
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function query()
    {
        return Transactions_out::query()
            ->whereMonth('tanggal_keluar', $this->month)
            ->select('id', 'tanggal_keluar', 'item_id', 'tujuan_keluar', 'jumlah');
    }

    public function headings(): array
    {
        return ['ID', 'Tanggal Keluar', 'Barang', 'Tujuan', 'Jumlah'];
    }

    public function styles(Worksheet $sheet): array
    {
        // Styling untuk header
        $sheet->getStyle('1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '4CAF50']]
        ]);

        // Warna setiap baris berdasarkan jumlah
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => ($row % 2 == 0) ? 'FFEBEE' : 'E3F2FD']]
            ]);
        }

        return [];
    }
}
