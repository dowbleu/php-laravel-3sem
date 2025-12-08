@component('mail::message')
# Новая статья создана!

Добавлена статья:

@component('mail::panel')
**Название:** {{ $article->title }}

**Текст:** {{ $article->text }}

**Дата публикации:** {{ $article->date_public }}

**Автор:** {{ $author }}
@endcomponent

@component('mail::button', ['url' => 'http://127.0.0.1:3000/article'])
Посмотреть статьи
@endcomponent

@endcomponent