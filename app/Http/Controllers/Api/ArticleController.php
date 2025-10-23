<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['source', 'author', 'category']);

        if ($q = $request->input('q')) {
            $query->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%")
                  ->orWhere('content', 'like', "%{$q}%");
        }

        if ($source = $request->input('source')) {
            $query->whereHas('source', fn($q2) =>
                $q2->where('key', $source)->orWhere('id', $source)
            );
        }

        if ($from = $request->input('date_from')) {
            $query->where('published_at', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->where('published_at', '<=', $to);
        }

        $articles = $query->orderBy('published_at', 'desc')
                          ->paginate(min(50, $request->input('per_page', 20)));

        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::with(['source','author','category'])->findOrFail($id);
        return response()->json($article);
    }
}
