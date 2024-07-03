<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id', 'shared_with',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function sharedWith()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }
}
