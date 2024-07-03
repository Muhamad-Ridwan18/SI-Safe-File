<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accessCodes()
    {
        return $this->hasMany(AccessCode::class);
    }

    public function accessRequests()
    {
        return $this->hasMany(AccessRequest::class);
    }

    public function sharedDocuments()
    {
        return $this->hasMany(SharedDocument::class);
    }
}
