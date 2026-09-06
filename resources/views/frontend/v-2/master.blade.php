<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="{{asset('uploads/setting/'.$setting->logo)}}" type="image/png" />
    <!-- Dynamic Theme Colors -->
    {!! \App\Helpers\ThemeHelper::getThemeStyleTag() !!}
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <!-- Lobibox CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/notifications/css/lobibox.min.css') }}">
    <style>
        .rating-stars input[type="radio"] {
            display: none;
        }
        
        .rating-stars label {
            font-size: 24px;
            color: #ddd;
            cursor: pointer;
        }
        
        .rating-stars input[type="radio"]:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #ffc107;
        }
        
        .review-item-rating-stars .fa-star.fas {
            color: #ffc107;
        }
        
        .review-item-rating-stars .fa-star.far {
            color: #ddd;
        }
        
        /* Ensure tabs are visible on mobile */
        @media (max-width: 767px) {
            .nav-pills .nav-item {
                flex: 1;
                text-align: center;
                margin-bottom: 5px;
            }
            
            .nav-pills .nav-link {
                display: block;
                width: 100%;
                text-align: center;
            }
            
            .product-details-info .nav {
                flex-wrap: wrap;
            }
        }
        
        /* Fix review item layout on mobile */
        @media (max-width: 767px) {
            .review-item-wrapper {
                flex-direction: column;
            }
            
            .review-item-left {
                margin-bottom: 15px;
            }
            
            .review-photo img {
                max-width: 100% !important;
                height: auto;
            }
        }
        
        /* Ensure variable product tabs are visible on mobile */
        @media (max-width: 576px) {
            .flex-column.flex-sm-row {
                flex-direction: column !important;
            }
            
            .gap-3 {
                gap: 0.5rem !important;
            }
            
            .my-1 {
                margin-top: 0.25rem !important;
                margin-bottom: 0.25rem !important;
            }
        }
    </style>

    <!-- Pavicon ICon -->
    @include('frontend.v-2.includes.style')
<!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{$code->gtm_id ?? 'GTM-XXXXXXX'}}');</script>
    <!-- End Google Tag Manager -->
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{$code->gtm_id ?? 'GTM-XXXXXXX'}}"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    @include('frontend.v-2.includes.header')

    <main>
        <!-- Flash Messages -->
        <div class="container mt-3">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <!-- Home Slider -->
        @yield('content-v2')
        <!-- /Footer top -->
    </main>

    <!-- Footer -->
    @include('frontend.v-2.includes.footer')
    <!-- /Footer -->

    <!-- Jquery CDN -->
    @include('frontend.v-2.includes.script')
    <!-- Lobibox JS -->
    <script src="{{ asset('backend/assets/plugins/notifications/js/lobibox.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/notifications/js/notifications.min.js') }}"></script>
    @stack('script')
    <a href="https://wa.me/{{ $setting->whatsapp ?? '' }}" target="_blank" class="whatapps-btn-inner">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <script>
        // Ensure tabs work properly on mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event listeners to all tab buttons
            var tabButtons = document.querySelectorAll('[data-bs-toggle="pill"]');
            tabButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    // Remove active class from all buttons
                    var parentNav = this.closest('ul');
                    var allButtons = parentNav.querySelectorAll('.nav-link, .order-btn');
                    allButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>