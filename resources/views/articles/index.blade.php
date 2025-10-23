@extends('layouts.app')

@section('title', 'Latest News')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">📰 Latest News</h1>

    <div class="grid md:grid-cols-3 gap-6">
        @forelse ($articles as $article)
            <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition">
                @if($article->url_to_image)
                    <img src="{{ $article->url_to_image }}" alt="Image" class="w-full h-48 object-cover">
                @endif

                <div class="p-4">
                    <h2 class="text-lg font-semibold mb-2">
                        <a href="{{ $article->url }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ Str::limit($article->title, 100) }}
                        </a>
                    </h2>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($article->description, 150) }}</p>

                    <div class="text-xs text-gray-500 flex justify-between items-center">
                        <span>{{ $article->source->name ?? 'Unknown Source' }}</span>
                        <span>{{ optional($article->published_at)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center col-span-3 text-gray-500">No articles found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $articles->links() }}
    </div>
</div>
@endsection
