<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id', 'access_code', 'qr_code_path',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

