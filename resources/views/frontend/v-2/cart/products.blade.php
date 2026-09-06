@extends('frontend.v-2.master')

@section('title')
    Cart products
@endsection

@section('content-v2')
    <div id="app">
        @if(isset($auth_user))
            <cart-products :auth_user="{{ $auth_user }}"></cart-products>
        @else
            <cart-products></cart-products>
        @endif
    </div>

@endsection

@push('script')
{{-- Data Layer... --}}
<script type = "text/javascript">
    window.addEventListener('load', function() {
        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event    : "view_cart",
            ecommerce: {
                items: [@foreach ($auth_user as $cart){
                    item_name     : "{{$cart->product->name}}",
                    item_id       : "{{$cart->product->id}}",
                    price         : "{{$cart->product->regular_price}}",
                    item_brand    : "Unknown",
                    item_category : "Unknown",
                    item_variant  : "",
                    item_list_name: "", 
                    item_list_id  : "",
                    index         : 0, 
                    quantity      : "{{$cart->qty}}"
                },@endforeach]
            }
        });
    });
    </script>
@endpush
