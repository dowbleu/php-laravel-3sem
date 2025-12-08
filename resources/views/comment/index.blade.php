@extends('layout')
@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success" role="alert">
            {{session('message')}}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Date public</th>
                <th scope="col">Author</th>
                <th scope="col">Article</th>
                <th scope="col">Text</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <th scope="row">{{$comment->created_at}}</th>
                    <td>{{$comment->user->name ?? 'Unknown'}}</td>
                    <td><a href="/article/{{$comment->article_id}}">{{$comment->article->title}}</a></td>
                    <td>{{$comment->text}}</td>
                    <td>
                        <a href="/comment/accept/{{$comment->id}}" class="btn btn-primary">Accept</a>
                        <a href="/comment/reject/{{$comment->id}}" class="btn btn-warning">Reject</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{$comments->links()}}
@endsection