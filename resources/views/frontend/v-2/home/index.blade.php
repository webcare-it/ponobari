@extends('frontend.v-2.master')

@section('title')
    Home
@endsection

@section('content-v2')
    <section class="home-slider-section container">
        <div class="row">
             <div class="col-md-3">
                <div class="top-selling-product-wrapper">
                    <h5 class="top-selling-product-title">
                        Top Selling Products
                    </h5>
                    <div class="top-selling-product-items-wrap">
                        @foreach ($top_products as $product)
                        <div class="top-selling-item-outer">
                            @if ($product->is_variable == true)
                            <a href="{{url('variable-product/'.$product->product->slug)}}" class="top-selling-product-image">
                            @else
                            <a href="{{url('product/'.$product->product->slug)}}" class="top-selling-product-image">
                            @endif
                                <img src="{{asset('product/images/'.$product->product->image)}}" alt="Image" />
                            </a>
                            <div class="top-selling-product-content">
                                @if ($product->is_variable == true)
                                <a href="{{url('variable-product/'.$product->product->slug)}}" class="product-name">
                                @else
                                <a href="{{url('product/'.$product->product->slug)}}" class="product-name">
                                @endif
                                    {{$product->product->name}}
                                </a>
                                <h6 class="product-regular-price">
                                    @if ($product->product->discount_price != null)
                                    ৳{{$product->product->discount_price}}
                                    @else
                                    ৳{{$product->product->regular_price}}
                                    @endif
                                </h6>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="slider-items-wrapper">
                    @foreach($sliders as $slider)
                    <div class="slider-item-outer">
                        <img src="{{ asset('/setting/'.$slider->image) }}" alt="image">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /Home Slider -->
    <section class="notice-section">
        <div class="container">
            <div class="notice_text_wrap">
                <marquee direction="left">অর্ডার করার পূর্বে কাস্টমার কেয়ার থেকে পন্যের স্টক ও ডেলিভারি সম্পর্কে জেনে নেয়ার অনুরোধ করা যাচ্ছে। প্রযুক্তি পণ্যের মূল্য অস্থিতিশীল থাকার কারণে যেকোনো মুহূর্তে পণ্যের দাম পরিবর্তন হতে পারে।</marquee>
            </div>
        </div>
    </section>
    <!-- Categoris Slider -->
    <section class="categoris-slider-section">
        <div class="container">
            <div class="section-title-outer">
                <h1 class="title">
                    Categories
                </h1>
            </div>
            <div class="categoris-items-wrapper owl-carousel">
                @foreach ($categories as $category)
                    <a href="{{ url('/products/'.$category->slug) }}" class="categoris-item">
                        <img src="{{ asset('/category/'.$category->image) }}" alt="category" />
                        <h6 class="categoris-name">{{ $category->name }}</h6>
                        <span class="items-number">{{ count($category->products) }} items</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <!-- /Categoris Slider -->

    <!-- Banner -->
    <section class="banner-section">
        <div class="container">
            <div class="row">
                @foreach($topBanners as $topBanner)
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="banner-item-outer">
                            <img src="{{ asset('/setting/'.$topBanner->image) }}" alt="banner image" />
                            <div class="banner-content">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- /Banner -->
    @if(count($hot_products) > 0)
    <!-- Popular Product -->
    <section class="product-section">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="section-title-outer">
                    <h1 class="title">
                        Hot Products
                    </h1>
                    <a href="{{ url('/hot/product') }}" class="product-view-all-btn">
                        View All
                    </a>
                </div>
                <div class="product-items-wrapper owl-carousel">
                    @foreach ($hot_products as $product)
                    <div class="product-item-wrapper">
                        <div class="product-image-outer">
                            @if ($product->is_variable == true)
                            <a href="{{url('variable-product/'.$product->slug)}}" class="product-imgae">
                            @else
                            <a href="{{url('product/'.$product->slug)}}" class="product-imgae">
                            @endif
                                <img src="{{asset('product/images/'.$product->image)}}" class="main-image" alt="product image">
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
                                <div class="product-price">
                                    @if ($product->discount_price != null)
                                    <span>{{$product->discount_price}} Tk.</span>
                                    @else
                                    <span>{{$product->regular_price}} Tk.</span>
                                    @endif
                                </div>
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
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /Popular Product -->
    @endif

    @if(count($new_products) > 0)
    <!-- Popular Product -->
    <section class="product-section">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="section-title-outer">
                    <h1 class="title">
                        New Arrival
                    </h1>
                    <a href="{{ url('/new/product') }}" class="product-view-all-btn">
                        View All
                    </a>
                </div>
                <!-- <new-arrival-products></new-arrival-products> -->
                <div class="product-items-wrapper owl-carousel">
                    @foreach ($new_products as $product)
                    <div class="product-item-wrapper">
                        <div class="product-image-outer">
                            @if ($product->is_variable == true)
                            <a href="{{url('variable-product/'.$product->slug)}}" class="product-imgae">
                            @else
                            <a href="{{url('product/'.$product->slug)}}" class="product-imgae">
                            @endif
                                <img src="{{asset('product/images/'.$product->image)}}" class="main-image" alt="product image">
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
                                <div class="product-price">
                                    @if ($product->discount_price != null)
                                    <span>{{$product->discount_price}} Tk.</span>
                                    @else
                                    <span>{{$product->regular_price}} Tk.</span>
                                    @endif
                                </div>
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
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /Popular Product -->
    @endif

    @if(count($regular_products) > 0)
    <!-- Popular Product -->
    <section class="product-section">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="section-title-outer">
                    <h1 class="title">
                        Regular Products
                    </h1>
                    <a href="{{ url('/feature/product') }}" class="product-view-all-btn">
                        View All
                    </a>
                </div>
                <!-- <feature-products></feature-products> -->
                <div class="product-items-wrapper owl-carousel">
                    @foreach ($regular_products as $product)
                    <div class="product-item-wrapper">
                        <div class="product-image-outer">
                            @if ($product->is_variable == true)
                            <a href="{{url('variable-product/'.$product->slug)}}" class="product-imgae">
                            @else
                            <a href="{{url('product/'.$product->slug)}}" class="product-imgae">
                            @endif
                                <img src="{{asset('product/images/'.$product->image)}}" class="main-image" alt="product image">
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
                                <div class="product-price">
                                    @if ($product->discount_price != null)
                                    <span>{{$product->discount_price}} Tk.</span>
                                    @else
                                    <span>{{$product->regular_price}} Tk.</span>
                                    @endif
                                </div>
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
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /Popular Product -->
    @endif

    @if(count($discount_products) > 0)
     <!-- Popular Product -->
    <section class="product-section">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="section-title-outer">
                    <h1 class="title">
                        Discount Products
                    </h1>
                    <a href="{{ url('/discount/product') }}" class="product-view-all-btn">
                        View All
                    </a>
                </div>
                <!-- <discount-products></discount-products> -->
                <div class="product-items-wrapper owl-carousel">
                    @foreach ($discount_products as $product)
                    <div class="product-item-wrapper">
                        <div class="product-image-outer">
                            @if ($product->is_variable == true)
                            <a href="{{url('variable-product/'.$product->slug)}}" class="product-imgae">
                            @else
                            <a href="{{url('product/'.$product->slug)}}" class="product-imgae">
                            @endif
                                <img src="{{asset('product/images/'.$product->image)}}" class="main-image" alt="product image">
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
                                <div class="product-price">
                                    @if ($product->discount_price != null)
                                    <span>{{$product->discount_price}} Tk.</span>
                                    @else
                                    <span>{{$product->regular_price}} Tk.</span>
                                    @endif
                                </div>
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
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /Popular Product -->
    @endif

    <!-- Bottom Banner -->
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="slider-item-outer">
                @if(isset($bottomBanner->image) != null)
                <img src="{{ asset('/setting/'.$bottomBanner->image) }}" alt="footer banner image"/>
                @endif
                </div>
            </div>
        </div>
    </section>
    <!-- /Bottom Banner -->

    <!-- Footer top -->
    <section class="footer-top-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-top-item-wrap">
                        <div class="image-outer">
                            <img src="{{ asset('/frontend/v-2/') }}/assets/images/icon-1.svg">
                        </div>
                        <div class="content">
                            <h3 class="title">
                                Best prices & offers
                            </h3>
                            <p class="description">
                                Orders $50 or more
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-top-item-wrap">
                        <div class="image-outer">
                            <img src="{{ asset('/frontend/v-2/') }}/assets/images/icon-2.svg">
                        </div>
                        <div class="content">
                            <h3 class="title">
                                Free delivery
                            </h3>
                            <p class="description">
                                24/7 amazing services
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-top-item-wrap">
                        <div class="image-outer">
                            <img src="{{ asset('/frontend/v-2/') }}/assets/images/icon-3.svg">
                        </div>
                        <div class="content">
                            <h3 class="title">
                                Great daily deal
                            </h3>
                            <p class="description">
                                When you sign up
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-top-item-wrap">
                        <div class="image-outer">
                            <img src="{{ asset('/frontend/v-2/') }}/assets/images/icon-5.svg">
                        </div>
                        <div class="content">
                            <h3 class="title">
                                Easy returns
                            </h3>
                            <p class="description">
                                Within 7 days
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
