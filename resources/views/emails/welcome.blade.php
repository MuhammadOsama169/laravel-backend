@component('mail::message')
    # Welcome to Our App!
    Thank you for signing up. We're excited to have you on board!
    
    @component('mail::button', ['url' => 'https://example.com'])
        Visit Our Website
    @endcomponent
    
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent