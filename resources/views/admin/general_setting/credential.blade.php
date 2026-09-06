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

                            <form action="{{ url('/admin/update/credentials') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-top border-0 border-4 border-primary">
                                            <div class="card-body p-5">
                                                <div class="card-title d-flex align-items-center">
                                                    <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                                                    </div>
                                                    <h5 class="mb-0 text-primary">Change Credentials</h5>
                                                </div>
                                                <hr>
                                                <div class="form-group mb-3">
                                                    <label for="password">Password</label>
                                                    <input type="password" name="password" class="form-control"
                                                        placeholder="Enter New Password">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <input type="password" name="password_confirmation"
                                                        class="form-control" placeholder="Confirm Password">
                                                </div>
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Update</button>
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
