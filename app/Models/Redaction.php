<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Redaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entry', 'file',
    ];



}
