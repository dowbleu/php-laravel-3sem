@component('mail::message')
# New Article Created!

A new article has been created:

@component('mail::panel')
**Title:** {{ $article->title }}

**Text:** {{ $article->text }}

**Publication Date:** {{ $article->date_public }}

**Author:** {{ $author }}
@endcomponent

@component('mail::button', ['url' => 'http://127.0.0.1:3000/article'])
View Articles
@endcomponent

@endcomponent