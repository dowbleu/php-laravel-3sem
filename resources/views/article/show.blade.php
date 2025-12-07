@extends('layout')
@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center">{{ $article->title }}</h5>
            <h5 class="card-subtitle mb-2 text-body-secondary">{{ $article->date_public }}</h6>
                <p class="card-text mb-3">{{ $article->text }}</p>
                <div class="btn-toolbar" role="toolbar">
                    <a href="/article/{{ $article->id }}/edit" class="btn btn-primary me-3">Edit</a>
                    <form action="/article/{{ $article->id }}" method="post">
                        @METHOD("DELETE")
                        @CSRF
                        <button type="submit" class="btn btn-warning">Delete</button>
                    </form>
                </div>
        </div>
    </div>

    <div class="mt-4">
        <h4>Comments</h4>

        @if($article->comments->count() > 0)
            @foreach($article->comments as $comment)
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">{{ $comment->text }}</p>
                        <p class="text-muted">Автор: {{ $comment->user->name }}</p>
                        <small class="text-muted">{{ $comment->created_at->format('Y-m-d H:i') }}</small>
                        <div class="mt-2">
                            <a href="/comment/{{ $comment->id }}/edit" class="btn btn-sm btn-primary">Edit</a>
                            <form action="/comment/{{ $comment->id }}" method="post" class="d-inline">
                                @METHOD("DELETE")
                                @CSRF
                                <button type="submit" class="btn btn-sm btn-warning">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted">No comments yet.</p>
        @endif

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Add Comment</h5>
                <form action="/comment" method="post">
                    @CSRF
                    <input type="hidden" name="article_id" value="{{ $article->id }}">
                    <div class="mb-3">
                        <label for="text" class="form-label">Comment</label>
                        <textarea class="form-control" id="text" name="text" rows="3" required></textarea>
                    </div>
                    <button type="submit" style="background-color: #0d6efd;" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection