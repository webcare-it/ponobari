@extends('admin.master')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <!-- Steadfast API Credentials -->
                <div class="col-md-12">
                    <div class="card card-radius-10">
                        <div class="card-header">
                            <h5 class="mb-1">Steadfast Courier API Settings</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('steadfast.courier.settings.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="steadfast_api_key">API Key</label>
                                            <input type="text" name="steadfast_api_key" 
                                                   value="{{ $general_setting->steadfast_api_key ?? '' }}" 
                                                   class="form-control" 
                                                   placeholder="Enter Steadfast API Key">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="steadfast_secret_key">Secret Key</label>
                                            <input type="text" name="steadfast_secret_key" 
                                                   value="{{ $general_setting->steadfast_secret_key ?? '' }}" 
                                                   class="form-control" 
                                                   placeholder="Enter Steadfast Secret Key">
                                        </div>
                                        <button type="submit" class="btn btn-success">Update Settings</button>
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