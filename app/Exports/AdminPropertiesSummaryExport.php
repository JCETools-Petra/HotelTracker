<?php

namespace App\Exports;

use App\Models\Property;
use Maatwebsite\Excel\Concerns\FromCollection; // Kita akan menggunakan FromCollection karena data sudah diolah di controller
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AdminPropertiesSummaryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $propertiesData;

    public function __construct($propertiesData)
    {
        $this->propertiesData = $propertiesData;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Data sudah disiapkan dan di-pass dari controller
        return $this->propertiesData;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'Nama Properti',
            'Total Catatan Pendapatan',
            'Total Pendapatan MICE (Rp)',
            'Total Pendapatan F&B (Rp)',
            'Total Pendapatan Kamar Offline (Rp)',
            'Total Pendapatan Kamar Online (Rp)',
            'Total Pendapatan Lainnya (Rp)',
            'Grand Total Pendapatan (Rp)',
        ];
    }

    /**
    * @param mixed $property // Ini adalah objek/item dari $propertiesData
    * @return array
    */
    public function map($property): array
    {
        // Asumsi $property adalah objek atau array yang memiliki properti/key berikut
        // yang sudah dihitung di Admin\DashboardController
        $grandTotal = ($property->total_mice_income ?? 0) +
                      ($property->total_fnb_income ?? 0) +
                      ($property->total_offline_room_income ?? 0) +
                      ($property->total_online_room_income ?? 0) +
                      ($property->total_others_income ?? 0);

        return [
            $property->name,
            $property->total_income_records ?? 0,
            $property->total_mice_income ?? 0,
            $property->total_fnb_income ?? 0,
            $property->total_offline_room_income ?? 0,
            $property->total_online_room_income ?? 0,
            $property->total_others_income ?? 0,
            $grandTotal,
        ];
    }
}
