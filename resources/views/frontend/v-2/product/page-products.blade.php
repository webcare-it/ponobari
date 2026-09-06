@extends('frontend.v-2.master')

@section('title')
    {{ ucfirst($pageName?->type) }}
@endsection

@section('content-v2')
    <section class="product-section">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h2>{{ ucfirst($pageName?->page_name) }} Products</h2>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <div class="row">
                    @foreach ($pageProducts as $product)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-item-wrapper">
                            <div class="product-image-outer">
                                @if ($product->is_variable == true)
                                <a href="{{url('variable-product/'.$product->slug)}}" class="product-imgae">
                                @else
                                <a href="{{url('product/'.$product->slug)}}" class="product-imgae">
                                @endif
                                    <img src="{{asset('product/images/'.$product->image)}}">
                                </a>
                                <div class="product-badges hot">
                                    <span style="text-transform: capitalize">{{$product->product_type}}</span>
                                </div>
                            </div>
                            <div class="product-content-outer">
                                @if ($product->is_variable == true)
                                <a href="{{url('variable-product/'.$product->slug)}}" class="product-name">
                                @else
                                <a href="{{url('product/'.$product->slug)}}" class="product-name">    
                                @endif
                                    {{mb_strlen($product->name, 'UTF-8') > 50 ? mb_substr($product->name, 0, 50, 'UTF-8') . '....' : $product->name}}
                                </a>
                                <div class="product-item-bottom">
                                    @if ($product->discount_price != null)
                                    <div class="product-price">
                                        <span>{{$product->discount_price}} Tk.</span>
                                    </div>
                                    @else
                                    <div class="product-price">
                                        <span>{{$product->regular_price}} Tk.</span>
                                    </div>
                                    @endif
                                    <div class="add-cart">
                                        <a href="{{url('/add/to/cart/'.$product->id.'/add_cart')}}" class="add-cart-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add
                                        </a>
                                    </div>
                                </div>
                                <a href="{{url('/add/to/cart/'.$product->id.'/quick_order')}}" class="quick-order-btn-inner">Quick Order</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

@endsection

@push('script')
@endpush
