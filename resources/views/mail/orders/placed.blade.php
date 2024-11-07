<x-mail::message>
# Order Placed Succssefully!

Thank You for Your Order. Its number
is {{ $order->id}}.


<x-mail::button :url="$url">
View Order
</x-mail::button>

Sincerely Yours, E-Store.
</x-mail::message>

