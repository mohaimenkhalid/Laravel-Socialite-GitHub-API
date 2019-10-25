<?php

namespace App\Exports;

use App\User;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;


class UsersExport implements FromQuery, WithHeadings, WithColumnFormatting, WithEvents
{
    /**
    * @return \Illuminate\Database\Eloquent\Builder
    */
   /* public function collection()
    {
        //return User::all();
        // return User::select('name', 'email')->where('id', '>', '26')->get();
         return User::select('name', 'email')->where('id', '>', '26')->get();
    }*/

    public function query()
    {
        return User::query();
    }

    public function map($user): array
    {
        return [
            'Custom Text '.$user->name,
            $user->email,
            Date::dateTimeToExcel($user->created_at),
        ];
    }

    public function headings(): array
    {
        return [
            'Serial',
            'Name',
            'Email',
            'Date'

        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }


    public function registerEvents(): array
    {
        $styleArray = [
            'font' => [
                'bold' => true,
            ]
        ];

        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) use ($styleArray) {
                $event->sheet->getStyle('A1:G1')->applyFromArray($styleArray);
            },
        ];
    }

}
