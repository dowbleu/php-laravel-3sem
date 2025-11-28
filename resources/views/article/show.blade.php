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
@endsection