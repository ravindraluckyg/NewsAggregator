<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['source', 'author', 'category'])
            ->orderByDesc('published_at');

        if ($request->has('source')) {
            $query->whereHas('source', fn($q) => $q->where('name', $request->source));
        }

        if ($request->has('category')) {
            $query->whereHas('category', fn($q) => $q->where('name', $request->category));
        }

        $articles = $query->paginate(10);

        return view('articles.index', compact('articles'));
    }
}
