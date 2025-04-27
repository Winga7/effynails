<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
  protected $fillable = [
    'content',
    'about_title',
    'about_content',
    'experience_title',
    'experience_content'
  ];
}
