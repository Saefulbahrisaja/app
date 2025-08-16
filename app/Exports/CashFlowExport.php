<?php

namespace App\Exports;

use App\Models\CashFlow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CashFlowExport implements FromCollection, WithHeadings, WithMapping
{

    public function __construct($start = null, $end = null)
    {
        $this->start = $start;
        $this->end = $end;
    }
    public function collection()
    {
        $query = CashFlow::query();

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        return $query->orderBy('tanggal', 'asc')->get();
    }

    public function map($row): array
    {
        return [
            $row->tanggal->format('d-m-Y'),
            ucfirst($row->type),
            $row->kategori,
            $row->type === 'keluar' ? '-'.$row->jumlah : $row->jumlah,
            $row->keterangan
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis',
            'Kategori',
            'Jumlah',
            'Keterangan'
        ];
    }
}
