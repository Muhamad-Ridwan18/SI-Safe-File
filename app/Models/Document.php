<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',  
        'original_filename',
        'encrypted_filename',
        'encryption_key',
        'iv',
        'secret_key',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
