@component('mail::message')
# Новый комментарий!

Добавлен комментарий с текстом:

@component('mail::panel')
{{ $comment->text }}
@endcomponent

Для статьи: {{ $article_title }}.  
Автор комментария: {{ $author }}.

@component('mail::button', ['url' => 'http://127.0.0.1:3000/comment'])
Модерация комментариев
@endcomponent

@endcomponent
