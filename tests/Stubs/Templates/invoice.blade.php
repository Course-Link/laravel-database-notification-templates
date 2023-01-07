<x-mail::message>
# Invoice Paid

Your invoice has been paid!

<x-mail::button url="https://laravel.com">
    View Invoice
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
