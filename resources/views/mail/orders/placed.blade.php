<x-mail::message>
# Order placed successfully!

Thank you for you order. Your order number is {{ $order->id }}.

<x-mail::button :url="{{$url}}">
View order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
