@extends('admin.master')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col">
                    <div class="card radius-10 mb-0">
                        <div class="card-body">
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

                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-1">SMTP Email Settings</h5>
                                    <p class="mb-0">Configure email settings for order notifications</p>
                                </div>
                            </div>

                            <form action="{{ route('admin.smtp.settings.update') }}" method="post">
                                @csrf
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h6 class="mb-3">Admin Notification Settings</h6>
                                        <div class="form-group mb-3">
                                            <label for="admin_notification_email" class="form-label">Admin Notification Email</label>
                                            <input type="email" name="admin_notification_email" id="admin_notification_email" 
                                                   value="{{ $general_setting->admin_notification_email ?? '' }}" 
                                                   class="form-control" placeholder="Enter admin email for notifications">
                                            <small class="text-muted">Email address where order notifications will be sent</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <h6 class="mb-3">SMTP Configuration</h6>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mail_host" class="form-label">SMTP Host</label>
                                            <input type="text" name="mail_host" id="mail_host" 
                                                   value="{{ $general_setting->mail_host ?? '' }}" 
                                                   class="form-control" placeholder="e.g., smtp.gmail.com">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mail_port" class="form-label">SMTP Port</label>
                                            <input type="text" name="mail_port" id="mail_port" 
                                                   value="{{ $general_setting->mail_port ?? '' }}" 
                                                   class="form-control" placeholder="e.g., 587">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mail_username" class="form-label">SMTP Username</label>
                                            <input type="text" name="mail_username" id="mail_username" 
                                                   value="{{ $general_setting->mail_username ?? '' }}" 
                                                   class="form-control" placeholder="Enter SMTP username">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mail_password" class="form-label">SMTP Password</label>
                                            <input type="password" name="mail_password" id="mail_password" 
                                                   value="{{ $general_setting->mail_password ?? '' }}" 
                                                   class="form-control" placeholder="Enter SMTP password">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mail_encryption" class="form-label">Encryption</label>
                                            <select name="mail_encryption" id="mail_encryption" class="form-control">
                                                <option value="">None</option>
                                                <option value="tls" {{ (isset($general_setting->mail_encryption) && $general_setting->mail_encryption == 'tls') ? 'selected' : '' }}>TLS</option>
                                                <option value="ssl" {{ (isset($general_setting->mail_encryption) && $general_setting->mail_encryption == 'ssl') ? 'selected' : '' }}>SSL</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mail_from_address" class="form-label">From Email Address</label>
                                            <input type="email" name="mail_from_address" id="mail_from_address" 
                                                   value="{{ $general_setting->mail_from_address ?? '' }}" 
                                                   class="form-control" placeholder="e.g., noreply@yourdomain.com">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mail_from_name" class="form-label">From Name</label>
                                            <input type="text" name="mail_from_name" id="mail_from_name" 
                                                   value="{{ $general_setting->mail_from_name ?? config('app.name') }}" 
                                                   class="form-control" placeholder="e.g., Your Store Name">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success float-right">Save Settings</button>
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