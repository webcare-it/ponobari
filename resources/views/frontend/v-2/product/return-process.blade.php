@extends('frontend.v-2.master')

@section('title')
    Product Return
@endsection

@section('content-v2')
<section class="banner-section">
    <div class="banner-bg-image">
        <img src="{{ asset('/frontend/') }}/assets/images/category-banner.jpg" alt="shop product"/>
    </div>
    <h2 class="banner-title">Product Return Process</h2>
</section>
<section class="return-process-section" id="app">
    <div class="container">
        <div class="row">
            <div class="col-md-10 m-auto">
                <return-product-form></return-product-form>
            </div>
        </div>
    </div>
  </section>
@endsection
