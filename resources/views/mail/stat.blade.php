@component('mail::message')
# Daily Statistics Report

Here is the daily statistics for {{ date('Y-m-d') }}:

@component('mail::panel')
**Article Views Today:** {{ $articleViews }}

**New Comments Today:** {{ $commentsCount }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent