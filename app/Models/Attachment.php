<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
    ];

    protected $with = ['note'];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
