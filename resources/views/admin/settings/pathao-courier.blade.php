@extends('admin.master')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <!-- Pathao API Credentials -->
                <div class="col-md-12">
                    <div class="card card-radius-10">
                        <div class="card-header">
                            <h5 class="mb-1">Pathao Courier API Settings</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pathao.courier.settings.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="pathao_client_id" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Client ID</label>
                                            <input type="text" name="pathao_client_id" value="{{ $general_setting->pathao_client_id ?? '' }}" class="form-control" placeholder="Enter your Pathao Client ID">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="pathao_client_secret" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Client Secret</label>
                                            <input type="text" name="pathao_client_secret" value="{{ $general_setting->pathao_client_secret ?? '' }}" class="form-control" placeholder="Enter your Pathao Client Secret">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="pathao_username" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Username</label>
                                            <input type="text" name="pathao_username" value="{{ $general_setting->pathao_username ?? '' }}" class="form-control" placeholder="Enter your Pathao Username">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="pathao_password" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Password</label>
                                            <input type="password" name="pathao_password" value="{{ $general_setting->pathao_password ?? '' }}" class="form-control" placeholder="Enter your Pathao Password">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="pathao_sandbox">
                                                <input type="checkbox" name="pathao_sandbox" value="1" {{ ($general_setting->pathao_sandbox ?? false) ? 'checked' : '' }}>
                                                Sandbox Mode
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mt-2 float-right">Save API Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Pathao Test Form -->
                <div class="col-md-12 mt-4">
                    <div class="card card-radius-10">
                        <div class="card-header">
                            <h5 class="mb-1">Test Pathao Courier</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('/patho/courier/data/store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="store_id" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Store Id</label>
                                            <input type="text" name="store_id" value="112586" class="form-control" placeholder="Enter your store id">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="merchant_order_id" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Marchant Order Id</label>
                                            <input type="text" name="merchant_order_id" class="form-control" placeholder="Enter your Marchant Order id">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="recipient_name" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Recipient Name</label>
                                            <input type="text" name="recipient_name" class="form-control" placeholder="Enter Recipient Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="recipient_phone" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Recipient Phone</label>
                                            <input type="number" name="recipient_phone" class="form-control" placeholder="Enter Recipient Phone">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="recipient_address" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Recipient Address</label>
                                            <input type="text" name="recipient_address" class="form-control" placeholder="Enter Recipient Address">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="recipient_city" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Recipient City</label>
                                            <input type="text" name="recipient_city" class="form-control" placeholder="Enter Recipient City">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="recipient_zone" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Recipient Zone</label>
                                            <input type="text" name="recipient_zone" class="form-control" placeholder="Enter Recipient Zone">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="recipient_area" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Recipient Area</label>
                                            <input type="text" name="recipient_area" class="form-control" placeholder="Enter Recipient Area">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="delivery_type" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Delivery Type</label>
                                            <input type="text" name="delivery_type" class="form-control" placeholder="Enter Delivery Type">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="item_type" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Item Type</label>
                                            <input type="text" name="item_type" class="form-control" placeholder="Enter Item Type">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="speciali_instruction" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Special Instruction</label>
                                            <input type="text" name="speciali_instruction" class="form-control" placeholder="Enter Special Instruction">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="item_quantity" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Item Quantity</label>
                                            <input type="number" name="item_quantity" class="form-control" placeholder="Enter Item Quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="item_weight" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Item Weight</label>
                                            <input type="number" name="item_weight" class="form-control" placeholder="Enter Item Weight">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="amount_to_collect" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Amount To Collect</label>
                                            <input type="number" name="amount_to_collect" class="form-control" placeholder="Amount To Collect">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="item_description" style="padding-bottom: 5px;font-weight: 600;font-size: 15px;letter-spacing: 1px;">Item Description</label>
                                            <input type="text" name="item_description" class="form-control" placeholder="Enter Item Description">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-success mt-3" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
