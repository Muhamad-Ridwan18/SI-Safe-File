<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportReportDocument implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $userId = null;
    protected $categoryId = null;

    /**
     * Optional constructor to filter by user_id or category_id
     */
    public function __construct($userId = null, $categoryId = null)
    {
        $this->userId = $userId;
        $this->categoryId = $categoryId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Document::query();
        
        // Apply filters if provided
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        
        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }
        
        return $query->with(['user', 'category'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Original Filename',
            'Category',
            'Owner',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param mixed $document
     * @return array
     */
    public function map($document): array
    {
        return [
            $document->id,
            $document->original_filename,
            $document->category ? $document->category->name : 'N/A',
            $document->user ? $document->user->name : 'N/A',
            $document->created_at->format('Y-m-d H:i:s'),
            $document->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}
