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

                            <form action="{{ route('admin.droploo.api.credentials.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card border-top border-0 border-4 border-primary">
                                            <div class="card-body p-5">
                                                <div class="card-title d-flex align-items-center">
                                                    <div><i class="bx bxs-key me-1 font-22 text-primary"></i>
                                                    </div>
                                                    <h5 class="mb-0 text-primary">Droploo API Credentials</h5>
                                                </div>
                                                <hr>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="droploo_app_key">App Key</label>
                                                    <input type="text" name="droploo_app_key" class="form-control"
                                                        placeholder="Enter Droploo App Key" value="{{ old('droploo_app_key', $general_setting->droploo_app_key ?? '') }}">
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="droploo_app_secret">App Secret</label>
                                                    <input type="text" name="droploo_app_secret" class="form-control"
                                                        placeholder="Enter Droploo App Secret" value="{{ old('droploo_app_secret', $general_setting->droploo_app_secret ?? '') }}">
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="droploo_username">Username</label>
                                                    <input type="text" name="droploo_username" class="form-control"
                                                        placeholder="Enter Droploo Username" value="{{ old('droploo_username', $general_setting->droploo_username ?? '') }}">
                                                </div>
                                                
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Update Credentials</button>
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