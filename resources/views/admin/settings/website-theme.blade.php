@extends('admin.master')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col">
                    <div class="card radius-10 mb-0">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(Session::has('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.website.theme.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card border-top border-0 border-4 border-primary">
                                            <div class="card-body p-5">
                                                <div class="card-title d-flex align-items-center">
                                                    <div><i class="bx bxs-palette me-1 font-22 text-primary"></i>
                                                    </div>
                                                    <h5 class="mb-0 text-primary">Website Theme Settings</h5>
                                                </div>
                                                <hr>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="website_primary_color">Primary Color</label>
                                                    <input type="color" name="website_primary_color" class="form-control form-control-color"
                                                        value="{{ old('website_primary_color', $general_setting->website_primary_color ?? '#0d6efd') }}">
                                                    <small class="text-muted">Choose the primary color for your website</small>
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="website_secondary_color">Secondary Color</label>
                                                    <input type="color" name="website_secondary_color" class="form-control form-control-color"
                                                        value="{{ old('website_secondary_color', $general_setting->website_secondary_color ?? '#6c757d') }}">
                                                    <small class="text-muted">Choose the secondary color for your website</small>
                                                </div>
                                                
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Update Theme Settings</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection