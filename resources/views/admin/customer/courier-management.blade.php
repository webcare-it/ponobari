@extends('admin.master')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-radius-10">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-1">Courier Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Courier Selection Form -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Select Courier</strong>
                                    </div>
                                    <div class="card-body">
                                        <form id="courierForm">
                                            @csrf
                                            <input type="hidden" id="orderId" value="{{ $order->id }}">
                                            
                                            <div class="form-group mb-3">
                                                <label for="courier">Courier Service</label>
                                                <select name="courier" id="courier" class="form-control">
                                                    <option value="">-- Select Courier --</option>
                                                    <option value="Steadfast" {{ $order->courier_name === 'Steadfast' ? 'selected' : '' }}>Steadfast</option>
                                                    <option value="Pathao" {{ $order->courier_name === 'Pathao' ? 'selected' : '' }}>Pathao</option>
                                                    <option value="Others" {{ $order->courier_name === 'Others' ? 'selected' : '' }}>Others</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Pathao Specific Fields -->
                                            <div id="pathaoFields" style="display: {{ $order->courier_name === 'Pathao' ? 'block' : 'none' }};">
                                                <div class="form-group mb-3">
                                                    <label for="city">City</label>
                                                    <select name="city" id="city" class="form-control">
                                                        <option value="">-- Select City --</option>
                                                        @if(is_array($cities) && count($cities) > 0)
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city['city_id'] }}" {{ ($order->pathao_city_id ?? '') == ($city['city_id'] ?? '') ? 'selected' : '' }}>{{ $city['city_name'] }}</option>
                                                            @endforeach
                                                        @elseif(isset($cities->data))
                                                            @foreach ($cities->data as $city)
                                                                <option value="{{ $city->city_id }}" {{ ($order->pathao_city_id ?? '') == ($city->city_id ?? '') ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <input type="hidden" name="city_name" id="city_name" value="{{ $order->pathao_city_name ?? '' }}">
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="zone">Zone</label>
                                                    <select name="zone" id="zone" class="form-control">
                                                        <option value="">-- Select Zone --</option>
                                                        @if($order->pathao_zone_id != null)
                                                            <option value="{{ $order->pathao_zone_id }}" selected>{{ $order->pathao_zone_name }}</option>
                                                        @endif
                                                    </select>
                                                    <input type="hidden" name="zone_name" id="zone_name" value="{{ $order->pathao_zone_name ?? '' }}">
                                                </div>
                                            </div>
                                            
                                            <!-- Other Courier Details -->
                                            <div id="otherCourierFields" style="display: {{ $order->courier_name === 'Others' ? 'block' : 'none' }};">
                                                <div class="form-group mb-3">
                                                    <label for="otherCourierDetails">Other Courier Details</label>
                                                    <textarea name="otherCourierDetails" id="otherCourierDetails" class="form-control" rows="3">{{ $order->otherCourierDetails ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            
                                            <!-- Common Fields -->
                                            <div class="form-group mb-3">
                                                <label for="specialNotes">Special Notes</label>
                                                <textarea name="specialNotes" id="specialNotes" class="form-control" rows="3">{{ $order->pathao_special_note ?? '' }}</textarea>
                                            </div>
                                            
                                            <button type="button" id="saveCourierBtn" class="btn btn-primary">Save Courier Info</button>
                                            <button type="button" id="createShipmentBtn" class="btn btn-success">Create Shipment</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Order Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Order Information</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Order ID:</strong> {{ $order->orderId }}</p>
                                                <p><strong>Customer Name:</strong> {{ $order->name }}</p>
                                                <p><strong>Phone:</strong> {{ $order->phone }}</p>
                                                <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Address:</strong> {{ $order->address }}</p>
                                                <p><strong>Amount:</strong> {{ $order->price }} Tk</p>
                                                <p><strong>Delivery Charge:</strong> {{ $order->area ?? 0 }} Tk</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge 
                                                        @if($order->order_status == 'complete') bg-success 
                                                        @elseif($order->order_status == 'pending') bg-warning 
                                                        @elseif($order->order_status == 'cancel') bg-danger 
                                                        @else bg-info @endif">
                                                        {{ ucfirst($order->order_status) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <!-- Shipment Information -->
                                        <h6>Shipment Information</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Courier:</strong> {{ $order->courier_name ?? 'Not assigned' }}</p>
                                                @if($order->courier_name === 'Pathao')
                                                    <p><strong>City:</strong> {{ $order->pathao_city_name ?? 'N/A' }}</p>
                                                    <p><strong>Zone:</strong> {{ $order->pathao_zone_name ?? 'N/A' }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Consignment ID:</strong> {{ $order->consignmentId ?? 'N/A' }}</p>
                                                @if($order->courier_name === 'Pathao')
                                                    <p><strong>Pathao Status:</strong> 
                                                        <span class="badge 
                                                            @if($order->pathao_order_status == 'Delivered') bg-success 
                                                            @elseif($order->pathao_order_status == 'Pending') bg-warning 
                                                            @elseif($order->pathao_order_status == 'Cancelled') bg-danger 
                                                            @else bg-info @endif">
                                                            {{ $order->pathao_order_status ?? 'N/A' }}
                                                        </span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="mt-3">
                                            @if($order->consignmentId)
                                                <button type="button" id="trackShipmentBtn" class="btn btn-info">Track Shipment</button>
                                                <button type="button" id="updateStatusBtn" class="btn btn-secondary">Update Status</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Recent Activity -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <strong>Recent Activity</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date & Time</th>
                                                        <th>Action</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                                        <td>Order Created</td>
                                                        <td><span class="badge bg-success">Completed</span></td>
                                                    </tr>
                                                    @if($order->courier_name)
                                                    <tr>
                                                        <td>{{ $order->updated_at->format('d M Y, H:i') }}</td>
                                                        <td>Courier Assigned: {{ $order->courier_name }}</td>
                                                        <td><span class="badge bg-success">Completed</span></td>
                                                    </tr>
                                                    @endif
                                                    @if($order->consignmentId)
                                                    <tr>
                                                        <td>{{ $order->updated_at->format('d M Y, H:i') }}</td>
                                                        <td>Shipment Created</td>
                                                        <td><span class="badge bg-success">Completed</span></td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Courier selection change handler
    const courierSelect = document.getElementById('courier');
    const pathaoFields = document.getElementById('pathaoFields');
    const otherCourierFields = document.getElementById('otherCourierFields');
    
    courierSelect.addEventListener('change', function() {
        const selectedCourier = this.value;
        
        // Hide all courier-specific fields first
        pathaoFields.style.display = 'none';
        otherCourierFields.style.display = 'none';
        
        // Show fields based on selected courier
        if (selectedCourier === 'Pathao') {
            pathaoFields.style.display = 'block';
        } else if (selectedCourier === 'Others') {
            otherCourierFields.style.display = 'block';
        }
    });
    
    // City selection handler for Pathao
    const citySelect = document.getElementById('city');
    const zoneSelect = document.getElementById('zone');
    const cityNameInput = document.getElementById('city_name');
    const zoneNameInput = document.getElementById('zone_name');
    
    // Function to fetch zones for a selected city
    function fetchZones(selectedCityId) {
        if (selectedCityId) {
            // Fetch zones for selected city
            fetch(`/get-zones/${selectedCityId}`)
                .then(response => response.json())
                .then(data => {
                    // Handle both cases: when zones is an array (no credentials) or object (with credentials)
                    let zonesData = [];
                    if (Array.isArray(data.zones)) {
                        // When no credentials, zones is an empty array
                        zonesData = data.zones;
                    } else if (data.zones && data.zones.data) {
                        // When credentials exist, zones is an object with data property
                        zonesData = data.zones.data;
                    }
                    
                    // Clear existing options
                    zoneSelect.innerHTML = '<option value="">-- Select Zone --</option>';
                    
                    // Add new zone options
                    zonesData.forEach(zone => {
                        const option = document.createElement("option");
                        option.value = zone.zone_id || zone['zone_id'];
                        option.textContent = zone.zone_name || zone['zone_name'];
                        zoneSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching zones:', error));
        } else {
            // Reset zone select if no city is selected
            zoneSelect.innerHTML = '<option value="">-- Select Zone --</option>';
        }
    }
    
    // Handle city selection change
    citySelect.addEventListener('change', function() {
        const selectedCityId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        cityNameInput.value = selectedOption.text;
        fetchZones(selectedCityId);
    });
    
    // Auto-fetch zones if a city is already selected on page load
    const initialSelectedCity = citySelect.value;
    if (initialSelectedCity) {
        // Set the city name input value
        const initialSelectedOption = citySelect.options[citySelect.selectedIndex];
        cityNameInput.value = initialSelectedOption.text;
        // Fetch zones for the initially selected city
        fetchZones(initialSelectedCity);
    }
    
    // Zone selection handler
    zoneSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        zoneNameInput.value = selectedOption.text;
    });
    
    // Save courier information
    document.getElementById('saveCourierBtn').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('courierForm'));
        const orderId = document.getElementById('orderId').value;
        
        fetch(`/admin/order/${orderId}/courier/save`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Courier information saved successfully!');
                location.reload();
            } else {
                alert('Error saving courier information: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving courier information.');
        });
    });
    
    // Create shipment
    document.getElementById('createShipmentBtn').addEventListener('click', function() {
        const courier = document.getElementById('courier').value;
        const orderId = document.getElementById('orderId').value;
        
        if (!courier) {
            alert('Please select a courier first.');
            return;
        }
        
        if (confirm('Are you sure you want to create a shipment with ' + courier + '?')) {
            fetch(`/admin/order/${orderId}/shipment/create`, {
                method: 'POST',
                body: new FormData(document.getElementById('courierForm')),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Shipment created successfully!');
                    location.reload();
                } else {
                    alert('Error creating shipment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating shipment.');
            });
        }
    });
    
    // Track shipment
    document.getElementById('trackShipmentBtn').addEventListener('click', function() {
        const consignmentId = '{{ $order->consignmentId }}';
        const orderId = document.getElementById('orderId').value;
        
        if (consignmentId && orderId) {
            // Show loading indicator
            const trackBtn = this;
            const originalText = trackBtn.textContent;
            trackBtn.textContent = 'Tracking...';
            trackBtn.disabled = true;
            
            // Call the tracking API
            fetch(`/admin/order/${orderId}/shipment/track`)
                .then(response => response.json())
                .then(data => {
                    trackBtn.textContent = originalText;
                    trackBtn.disabled = false;
                    
                    if (data.success) {
                        // Display tracking information
                        let trackingInfo = `Courier: ${data.courier}\n`;
                        if (data.courier === 'Pathao') {
                            trackingInfo += `Status: ${data.data.status}\n`;
                            trackingInfo += `Delivery Time: ${data.data.delivery_time ?? 'N/A'}\n`;
                            trackingInfo += `Recipient: ${data.data.recipient_name ?? 'N/A'}\n`;
                            trackingInfo += `Address: ${data.data.recipient_address ?? 'N/A'}\n`;
                        } else if (data.courier === 'Steadfast') {
                            trackingInfo += `Status: ${data.data.status ?? 'N/A'}\n`;
                            trackingInfo += `Delivery Date: ${data.data.delivery_date ?? 'N/A'}\n`;
                            trackingInfo += `Recipient: ${data.data.recipient_name ?? 'N/A'}\n`;
                            trackingInfo += `Address: ${data.data.recipient_address ?? 'N/A'}\n`;
                        }
                        alert(`Tracking Information:\n${trackingInfo}`);
                    } else {
                        alert('Error tracking shipment: ' + data.message);
                    }
                })
                .catch(error => {
                    trackBtn.textContent = originalText;
                    trackBtn.disabled = false;
                    console.error('Error:', error);
                    alert('An error occurred while tracking shipment.');
                });
        } else {
            alert('No consignment ID found for this order.');
        }
    });
    
    // Update status
    document.getElementById('updateStatusBtn').addEventListener('click', function() {
        const orderId = document.getElementById('orderId').value;
        
        // In a real implementation, this would show a modal or form to update status
        alert('In a real implementation, this would allow updating the shipment status.');
    });
});
</script>
@endpush