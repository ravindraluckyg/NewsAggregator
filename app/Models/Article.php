<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'source_id',
        'author_id',
        'category_id',
        'external_id',
        'title',
        'description',
        'content',
        'url',
        'url_to_image',
        'published_at',
        'language',
        'raw',
    ];

    public function source()  { return $this->belongsTo(Source::class); }
    public function author()  { return $this->belongsTo(Author::class); }
    public function category() { return $this->belongsTo(Category::class); }
}
