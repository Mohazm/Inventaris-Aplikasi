<?php

namespace App\Exports;

use App\Models\Transactions_in;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsIntExport implements FromQuery, WithHeadings, WithStyles
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function query()
    {
        return Transactions_in::query()
            ->whereMonth('tanggal_masuk', $this->month)
            ->select('id', 'supplier_id', 'tanggal_masuk', 'jumlah');
    }

    public function headings(): array
    {
        return ['ID', 'Nama Supplier', 'Tanggal Masuk', 'Jumlah'];
    }

    public function styles(Worksheet $sheet)
    {
        // Gaya untuk header
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => '4CAF50'], // Hijau header
            ],
        ]);

        // Gaya untuk setiap baris data
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'argb' => ($row % 2 === 0) ? 'FFEBEE' : 'E3F2FD', // Pink untuk genap, biru muda untuk ganjil
                    ],
                ],
            ]);
        }
    }
}
