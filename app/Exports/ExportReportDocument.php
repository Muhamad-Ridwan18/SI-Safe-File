<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportReportDocument implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return the collection of documents.
     */
    public function collection()
    {
        return Document::with(['user', 'category'])->get();
    }

    /**
     * Map the data for each document.
     */
    public function map($document): array
    {
        return [
            $document->id,
            $document->user->name ?? 'N/A', // Assuming user has a 'name' column
            $document->original_filename,
            $document->encrypted_filename,
            $document->category->name ?? 'N/A', // Assuming category has a 'name' column
            $document->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Define the headings for the Excel file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Original Filename',
            'Encrypted Filename',
            'Category',
            'Created At',
        ];
    }
}
