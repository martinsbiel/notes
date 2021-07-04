<?php

namespace App\Exports;

use App\Models\Note;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NotesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return auth()->user()->notes()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Conteúdo',
            'Data de criação',
            'Data da última atualização'
        ];
    }

    public function map($line): array
    {
        return [
            $line->id,
            $line->title,
            $line->content,
            date('d/m/Y H:i:s', strtotime($line->created_at)),
            date('d/m/Y H:i:s', strtotime($line->updated_at))
        ];
    }
}
