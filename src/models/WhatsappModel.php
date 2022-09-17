<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappModel extends Model
{
    use HasFactory;

    protected $table = 'whatsapp';

    protected $fillable = ["txt","telf","img","aud","pdf","mp4","status","sender"];
}
