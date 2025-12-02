@extends('layout')
@section('content')

    <ul class="list-group mb-3">
        @foreach($errors->all() as $error)
            <li class="list-group-item list-group-item-danger">{{ $error }}</li>
        @endforeach
    </ul>

    <form action="/comment/{{ $comment->id }}" method="POST">
        @CSRF
        @METHOD("PUT")
        <div class="mb-3">
            <label for="text" class="form-label">Enter comment text</label>
            <textarea class="form-control" id="text" name="text" rows="5" required>{{ $comment->text }}</textarea>
        </div>
        <button style="background-color: #0d6efd;" type="submit" class="btn btn-primary">Update comment</button>
    </form>
@endsection