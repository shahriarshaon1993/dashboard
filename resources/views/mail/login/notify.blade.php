@component('mail::message')
# A new sign-in on {{ $data['osName'] }}

@component('mail::panel')
{{ $data['loginMail'] }}
@endcomponent

We noticed a new sign-in to your account on a {{ $data['osName'] }} device. If this was you, you don’t need to do anything. If not, we’ll help you secure your account.

@component('mail::button', ['url' => $data['url']])
Change your password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
