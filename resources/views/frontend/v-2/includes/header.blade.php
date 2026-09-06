<header class="header-section">
    <div class="container">
        <div class="header-top-wrapper">
            <a href="{{ url('/') }}" class="brand-logo-outer">
                <img src="{{asset('setting/'.$setting->logo)}}">
            </a>
            <div class="search-form-outer">
                <form action="{{url('/view/product/search')}}" method="GET" class="form-group search-form">
                    @csrf
                    <input type="text" name="search" class="form-control" placeholder="Search for items...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="header-top-right-outer">
                <div class="res-search">
                    <i class="fas fa-search"></i>
                </div>
                <div id="cart">
                    <add-cart></add-cart>
                </div>
                {{-- <div class="header-top-right-item dropdown account">
                    <div class="header-top-right-item-link">
                        @if (auth()->check())
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                        @else
                        <i class="fas fa-user"></i> <a href="{{url('/customer/login-form')}}" class="account-list-item-link">Login</a>
                        @endif
                    </div>
                    <ul class="account-list">
                        <li class="account-list-item">
                            @if (auth()->check())
                            <a href="{{ route('logout') }}" class="account-list-item-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-user"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                             </form>
                            @endif
                        </li>
                        
                    </ul>
                </div> --}}
            </div>
        </div>
        <div class="header-bottom-wrapper">
            <div class="category-items-wrapper">
                <div class="category-icon-outer">
                    <i class="fas fa-th-large"></i> <span>All Category</span>
                </div>
                <div class="category-items-outer">
                    <ul class="category-list">
                        @foreach ($categories as $category)
                            <li class="category-list-item item-has-submenu">
                                <a href="{{ url('/products/'.$category->slug) }}" class="category-list-item-link">
                                    <img src="{{ asset('/category/'.$category->image) }}" alt="category">
                                    {{ $category->name }}
                                </a>
                                <ul class="nav-item-category-submenu">
                                    @foreach($category->subcategories as $subcategory)
                                        <li class="category-submenu-item">
                                            <a href="{{ url('/subcategory/products/'.$subcategory->slug) }}" class="category-submenu-item-link">
                                                {{ $subcategory->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="manu-wrapper">
                <!-- Nav Toggle Button -->
				<div class="nav-toggle-btn">
					<div class="btn-inner"></div>
				</div>
                <ul class="manu-list">
                    <li class="manu-list-item">
                        <a href="{{ url('/') }}" class="manu-list-item-link">
                            Home
                        </a>
                    </li>
                    <li class="manu-list-item">
                        <a href="{{ url('/shops') }}" class="manu-list-item-link">
                            Shop
                        </a>
                    </li>
                    @foreach ($allPages as $page)
                       <li class="manu-list-item">
                          <a href="{{ url('/page/products/'. \Illuminate\Support\Str::slug($page->name)) }}" target="_blank" class="manu-list-item-link">
                              {{ ucfirst($page?->name) }}
                          </a>
                       </li>
                    @endforeach
                    <li class="manu-list-item">
                        <a href="{{ url('/return/process') }}" class="manu-list-item-link">
                            Return Process
                        </a>
                    </li>
{{--                  <li class="manu-list-item">--}}
{{--                      <a href="#" class="manu-list-item-link">--}}
{{--                          Order Tracking--}}
{{--                      </a>--}}
{{--                  </li>--}}
                </ul>
            </div>
        </div>
    </div>
</header>
