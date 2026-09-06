<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\Courier;
use Codeboxr\PathaoCourier\Facade\PathaoCourier;
use Session;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Notification;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        $sql = Order::with('product', 'orderDetails')->orderBy('created_at', 'desc');

        if (isset($request->search)) {
            $sql->whereHas('product', function($q) use($request){
                $q->where('name', 'LIKE','%'.$request->search.'%');
            });
        }

        $orders = $sql->paginate(50);
        return view('admin.customer.orders', compact('orders'));
    }

    public function ordersView($id)
    {
        $order = Order::with('orderDetails', 'user', 'district', 'subDistrict')->where('id', $id)->orderBy('created_at', 'desc')->first();

        // Initialize cities as empty array
        $cities = [];

        // Only attempt to fetch cities if we have all required Pathao credentials
        try {
            $generalSetting = \App\Models\GeneralSetting::first();
            if ($generalSetting &&
                !empty($generalSetting->pathao_client_id) &&
                !empty($generalSetting->pathao_client_secret) &&
                !empty($generalSetting->pathao_username) &&
                !empty($generalSetting->pathao_password)) {
                // Only call the API if all credentials are present
                $cities = \Codeboxr\PathaoCourier\Facade\PathaoCourier::area()->city();
            }
        } catch (\Exception $e) {
            // Silently handle any errors - don't show them to the user
            // This fulfills the user's requirement to suppress errors
            \Illuminate\Support\Facades\Log::info('Pathao API skipped in ordersView due to missing credentials or error: ' . $e->getMessage());
            $cities = []; // Ensure cities remains empty
        }

        return view('admin.customer.details', compact('order', 'cities'));
    }

    public function dropshippingOrdersView ($id)
    {
        $order = Order::with('orderDetails', 'user', 'district', 'subDistrict')->where('id', $id)->orderBy('created_at', 'desc')->first();
        return view('admin.customer.dropshipping-details', compact('order'));
    }

    public function orderUpdate(Request $request, $id)
    {
        $orderUpdate = Order::find($id);
        $orderUpdate->area = $request->area ? $request->area : 0;
        $orderUpdate->save();
        return response()->json($orderUpdate, 200);
    }

    public function orderPriceUpdate(Request $request, $id)
    {
        //dd((int)$id);
        $orderDetailsPriceUpdate = OrderDetails::where('id', (int)$id)->first();
        $orderDetailsPriceUpdate->price = $request->regular_price;
        $orderDetailsPriceUpdate->save();

        $priceUpdate = Order::where('id', $orderDetailsPriceUpdate->order_id)->first();
        $priceUpdate->price = $priceUpdate->price + $request->price;
        $priceUpdate->save();
        return response()->json($priceUpdate, 200);
    }

    public function qtyUpdate(Request $request, $id)
    {
        $qtyUpdate = OrderDetails::find($id);
        $qtyUpdate->qty = $request->qty ? $qtyUpdate->qty + $request->qty : 0;
        $qtyUpdate->save();

        $orderQtyUpdate = Order::find($qtyUpdate->order_id);
        $orderQtyUpdate->qty = $orderQtyUpdate->qty + $request->qty;
        $orderQtyUpdate->save();

        return response()->json($qtyUpdate, 200);
    }

    //============== Order status ===============//

    public function pending($id)
    {
        $pending = OrderDetails::where('id', $id)->where('status', 0)->first();
        $pending->status = 1;
        $pending->save();

        if($pending->status == 1){
            $pendingOrder = Order::where('id', $pending->order_id)->first();
            $pendingOrder->status = 1;
            $pendingOrder->save();
        }
        return redirect()->back()->with('success', 'Order status has been changed.');
    }
    public function shipping($id)
    {
        $pending = OrderDetails::where('id', $id)->where('status', 1)->first();
        $pending->status = 2;
        $pending->save();

        if($pending->status == 2){
            $pendingOrder = Order::where('id', $pending->order_id)->first();
            $pendingOrder->status = 2;
            $pendingOrder->save();
        }
        return redirect()->back()->with('success', 'Order status has been changed.');
    }

    public function stocks()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(30);
        return view('admin.products.stocks', compact('products'));
    }

    public function ordersPdf($orderId)
    {
        $order = Order::with('orderDetails', 'user')->where('orderId', $orderId)->orderBy('created_at', 'desc')->first();

        $pdf = PDF::loadView('admin.pdf', compact('order'));

        return $pdf->stream('pdf_file.pdf');
    }

    public function pdf()
    {
        $data = [
            'title' => 'Welcome to shakil.com',
            'date' => date('m/d/Y')
        ];

        $pdf = PDF::loadView('admin.pdf', $data);

        return $pdf->download('banggomart.pdf');
    }

    public function processSelectedOrders (Request $request)
    {
        $selectedOrderIds = $request->id;
        if($selectedOrderIds==null){
            return redirect()->back()->with('error', 'Select Minimum One!');
        }

        //Code For Print or CSV Downloiad...
        $clickedButton = $request->input('submit_button');
        if ($clickedButton === 'print') {
            $selectedOrders = Order::with('orderDetails', 'admin')->whereIn('id', $selectedOrderIds)->get();

            //Update order_status as delivered...
            foreach ($selectedOrderIds as $orderId) {
                $order = Order::find($orderId);
                //dd($order);
                if ($order) {
                    $order->order_status = 'delivered';
                    $order->save();
                    //Notification...
                    $notification = new Notification();
                    $notification->message = 'Order with invoice id'.' '.$order->orderId.' '. 'is made status delivered by'.' '.Session::get('name');
                    $notification->specific_user_id = Session::get('id');
                    $notification->notification_for = "user";
                    $order->notification()->save($notification);
                    //Notification...
                }
            }
            //Update order_status as delivered...

            return view('admin.pdf', compact('selectedOrders'));
        }

        elseif ($clickedButton === 'csv') {
            $queryString = http_build_query($selectedOrderIds);
            return redirect()->route('download.orders.csv', ['id' => $queryString]);
        }
        //Code For Print or CSV Downloiad...

    }

    public function downloadCSV ($id)
    {
        $associativeArray = [];
        parse_str($id, $associativeArray);
        return Excel::download(new OrdersExport($associativeArray), 'orders.csv');

    }

    public function customerReview()
    {
        $productReviews = Review::with('product')->orderBy('id', 'desc')->get();
        return view('admin.customer.review', compact('productReviews'));
    }

    public function customerReviewForm()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.customer.review-create', compact('products'));
    }
    public function customerReviewEdit($id)
    {
        $products = Product::orderBy('id', 'desc')->get();
        $productReview = Review::with('product')->find($id);
        return view('admin.customer.review-edit', compact('products', 'productReview'));
    }

    public function customerReviewStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'product_id' => 'required',
            'rating' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $photoName = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
            $destinationPath = public_path('reviews');

            // Create directory if it doesn't exist
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Resize and save image
            $img = Image::make($photo->getRealPath());
            $img->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($destinationPath . '/' . $photoName);
        }

        Review::create([
            'name' => $request->name,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'phone' => $request->phone,
            'address' => $request->address,
            'message' => $request->message,
            'photo' => $photoName
        ]);

        $this->setSuccessMessage('Customer review has been created');
        return redirect()->back();
    }

    public function customerReviewUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'product_id' => 'required',
            'rating' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $review = Review::find($id);

        $photoName = $review->photo; // Keep existing photo by default

        // Handle photo upload if a new photo is provided
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($review->photo && File::exists(public_path('reviews/' . $review->photo))) {
                File::delete(public_path('reviews/' . $review->photo));
            }

            $photo = $request->file('photo');
            $photoName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
            $destinationPath = public_path('reviews');

            // Create directory if it doesn't exist
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Resize and save image
            $img = Image::make($photo->getRealPath());
            $img->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($destinationPath . '/' . $photoName);
        }

        $review->update([
            'name' => $request->name,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'phone' => $request->phone,
            'address' => $request->address,
            'message' => $request->message,
            'photo' => $photoName
        ]);

        $this->setSuccessMessage('Customer review has been updated');
        return redirect()->back();
    }

    public function customerReviewDelete($id)
    {
        $review = Review::find($id);
        $review->delete();
        $this->setSuccessMessage('Customer review has been updated');
        return redirect()->back();
    }

    public function productColorUpdate(Request $request, $id)
    {
        $orderColorUpdate = OrderDetails::find($id);
        $orderColorUpdate->color = $request->color;
        $orderColorUpdate->save();
        return response()->json($orderColorUpdate, 200);
    }

    public function productSizeUpdate(Request $request, $id)
    {
        $orderSizeUpdate = OrderDetails::find($id);
        $orderSizeUpdate->size = $request->size;
        $orderSizeUpdate->save();
        return response()->json($orderSizeUpdate, 200);
    }

    //Pathao API...
    public function zoneList($cityId)
    {
        // Initialize zones as empty array
        $zones = [];

        // Only attempt to fetch zones if we have all required Pathao credentials
        try {
            $generalSetting = \App\Models\GeneralSetting::first();
            if ($generalSetting &&
                !empty($generalSetting->pathao_client_id) &&
                !empty($generalSetting->pathao_client_secret) &&
                !empty($generalSetting->pathao_username) &&
                !empty($generalSetting->pathao_password)) {
                // Only call the API if all credentials are present
                $zones = PathaoCourier::area()->zone($cityId);
            }
        } catch (\Exception $e) {
            // Silently handle any errors - don't show them to the user
            // This fulfills the user's requirement to suppress errors
            \Illuminate\Support\Facades\Log::info('Pathao API skipped in zoneList due to missing credentials or error: ' . $e->getMessage());
            $zones = []; // Ensure zones remains empty
        }

        return response()->json(['zones' => $zones]);
    }

    public function courierManagement($id)
    {
        $order = Order::with('orderDetails', 'user', 'district', 'subDistrict')->where('id', $id)->orderBy('created_at', 'desc')->first();

        // Initialize cities as empty array
        $cities = [];

        // Only attempt to fetch cities if we have all required Pathao credentials
        try {
            $generalSetting = \App\Models\GeneralSetting::first();
            if ($generalSetting &&
                !empty($generalSetting->pathao_client_id) &&
                !empty($generalSetting->pathao_client_secret) &&
                !empty($generalSetting->pathao_username) &&
                !empty($generalSetting->pathao_password)) {
                // Only call the API if all credentials are present
                $cities = PathaoCourier::area()->city();
            }
        } catch (\Exception $e) {
            // Silently handle any errors - don't show them to the user
            // This fulfills the user's requirement to suppress errors
            \Illuminate\Support\Facades\Log::info('Pathao API skipped in courierManagement due to missing credentials or error: ' . $e->getMessage());
            $cities = []; // Ensure cities remains empty
        }

        return view('admin.customer.courier-management', compact('order', 'cities'));
    }

    public function saveCourierInfo(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $order->courier_name = $request->courier;

            // Handle courier-specific fields
            if ($request->courier === 'Pathao') {
                $order->pathao_city_id = $request->city;
                $order->pathao_zone_id = $request->zone;
                $order->pathao_city_name = $request->city_name;
                $order->pathao_zone_name = $request->zone_name;
                $order->pathao_special_note = $request->specialNotes;
            } elseif ($request->courier === 'Steadfast') {
                // Steadfast doesn't need city/zone fields, but we can store special notes if provided
                $order->pathao_special_note = $request->specialNotes;
            } elseif ($request->courier === 'Others') {
                $order->otherCourierDetails = $request->otherCourierDetails;
            }

            $order->save();

            return response()->json(['success' => true, 'message' => 'Courier information saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving courier information: ' . $e->getMessage()]);
        }
    }

    public function createShipment(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            if ($request->courier === 'Pathao') {
                // Check if Pathao credentials are configured
                $generalSetting = \App\Models\GeneralSetting::first();
                if (!$generalSetting ||
                    empty($generalSetting->pathao_client_id) ||
                    empty($generalSetting->pathao_client_secret) ||
                    empty($generalSetting->pathao_username) ||
                    empty($generalSetting->pathao_password)) {
                    return response()->json(['success' => false, 'message' => 'Pathao API credentials are not configured. Please check your settings.']);
                }

                // Create Pathao shipment
                $response = PathaoCourier::order()->create([
                    "store_id"            => "184689", // This should be configurable
                    "merchant_order_id"   => $order->orderId,
                    "recipient_name"      => $order->name,
                    "recipient_phone"     => $order->phone,
                    "recipient_address"   => $order->address,
                    "recipient_city"      => $request->city,
                    "recipient_zone"      => $request->zone,
                    "delivery_type"       => "48",
                    "item_type"           => "2",
                    "special_instruction" => $request->specialNotes,
                    "item_quantity"       => "1",
                    "item_weight"         => "0.5",
                    "amount_to_collect"   => (int) $order->price,
                    "item_description"    => "Not any"
                ]);

                $responseArray = json_decode(json_encode($response), true);

                if (isset($responseArray['consignment_id'])) {
                    $order->consignmentId = $responseArray['consignment_id'];
                    $order->save();

                    return response()->json(['success' => true, 'message' => 'Pathao shipment created successfully', 'consignment_id' => $responseArray['consignment_id']]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Failed to create Pathao shipment: ' . json_encode($responseArray)]);
                }
            } elseif ($request->courier === 'Steadfast') {
                // Create Steadfast shipment
                $generalSetting = \App\Models\GeneralSetting::first();
                $apiKey = $generalSetting->steadfast_api_key ?? config('steadfast.api_key');
                $secretKey = $generalSetting->steadfast_secret_key ?? config('steadfast.secret_key');

                // Validate required fields
                if (empty($apiKey) || empty($secretKey)) {
                    return response()->json(['success' => false, 'message' => 'Steadfast API credentials are not configured. Please check your settings.']);
                }

                $apiEndpoint = 'https://portal.steadfast.com.bd/api/v1/create_order';

                $payload = [
                    'invoice'           => $order->orderId,
                    'cod_amount'        => (int) $order->price,
                    'recipient_name'    => $order->name,
                    'recipient_phone'   => $order->phone,
                    'recipient_address' => $order->address,
                    'note'              => $request->specialNotes ?? ''
                ];

                $headers = [
                    'Api-Key' => $apiKey,
                    'Secret-Key' => $secretKey,
                    'Content-Type' => 'application/json',
                ];

                try {
                    // Log the request for debugging
                    \Illuminate\Support\Facades\Log::info('Steadfast API Request (OrderController)', [
                        'order_id' => $order->id,
                        'endpoint' => $apiEndpoint,
                        'headers' => array_merge($headers, ['Api-Key' => '***', 'Secret-Key' => '***']), // Mask sensitive data
                        'payload' => $payload
                    ]);

                    $response = \Illuminate\Support\Facades\Http::withHeaders($headers)->timeout(30)->post($apiEndpoint, $payload);

                    // Log the response for debugging
                    \Illuminate\Support\Facades\Log::info('Steadfast API Response (OrderController)', [
                        'order_id' => $order->id,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);

                    // Check if the response is successful
                    if (!$response->successful()) {
                        \Illuminate\Support\Facades\Log::error('Steadfast API HTTP Error (OrderController)', [
                            'order_id' => $order->id,
                            'status' => $response->status(),
                            'body' => $response->body()
                        ]);
                        return response()->json(['success' => false, 'message' => 'Steadfast API HTTP Error: ' . $response->status()]);
                    }

                    $responseData = $response->json();

                    if (isset($responseData['consignment'])) {
                        $consignmentData = $responseData['consignment'];
                        $consignmentId = $consignmentData['consignment_id'];

                        $order->consignmentId = $consignmentId;
                        $order->save();

                        // Log successful order creation
                        \Illuminate\Support\Facades\Log::info('Steadfast shipment created successfully (OrderController)', [
                            'order_id' => $order->id,
                            'consignment_id' => $consignmentId
                        ]);

                        return response()->json(['success' => true, 'message' => 'Steadfast shipment created successfully', 'consignment_id' => $consignmentId]);
                    } elseif (isset($responseData['error']) || isset($responseData['message'])) {
                        // Handle API error response
                        $errorMessage = $responseData['error'] ?? $responseData['message'] ?? 'Unknown error from Steadfast API';
                        \Illuminate\Support\Facades\Log::error('Steadfast API Error (OrderController)', [
                            'order_id' => $order->id,
                            'error' => $errorMessage,
                            'response' => $responseData
                        ]);
                        return response()->json(['success' => false, 'message' => 'Steadfast API Error: ' . $errorMessage]);
                    } else {
                        // Handle unexpected response
                        \Illuminate\Support\Facades\Log::warning('Unexpected Steadfast API Response (OrderController)', [
                            'order_id' => $order->id,
                            'response' => $responseData
                        ]);
                        return response()->json(['success' => false, 'message' => 'Unexpected response from Steadfast API: ' . json_encode($responseData)]);
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    \Illuminate\Support\Facades\Log::error('Steadfast API Connection Error (OrderController)', [
                        'order_id' => $order->id,
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return response()->json(['success' => false, 'message' => 'Connection error with Steadfast API: ' . $e->getMessage()]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Steadfast API Exception (OrderController)', [
                        'order_id' => $order->id,
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return response()->json(['success' => false, 'message' => 'Exception occurred with Steadfast API: ' . $e->getMessage()]);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Unsupported courier service']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creating shipment: ' . $e->getMessage()]);
        }
    }

    // Track shipment for both Pathao and Steadfast
    public function trackShipment($id)
    {
        try {
            $order = Order::findOrFail($id);

            if (!$order->consignmentId) {
                return response()->json(['success' => false, 'message' => 'No consignment ID found for this order.']);
            }

            if ($order->courier_name === 'Pathao') {
                // Check if Pathao credentials are configured
                $generalSetting = \App\Models\GeneralSetting::first();
                if (!$generalSetting ||
                    empty($generalSetting->pathao_client_id) ||
                    empty($generalSetting->pathao_client_secret) ||
                    empty($generalSetting->pathao_username) ||
                    empty($generalSetting->pathao_password)) {
                    return response()->json(['success' => false, 'message' => 'Pathao API credentials are not configured. Please check your settings.']);
                }

                // Track Pathao shipment
                $response = PathaoCourier::order()->track($order->consignmentId);
                $responseArray = json_decode(json_encode($response), true);

                if (isset($responseArray['data'])) {
                    return response()->json(['success' => true, 'data' => $responseArray['data'], 'courier' => 'Pathao']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Failed to track Pathao shipment: ' . json_encode($responseArray)]);
                }
            } elseif ($order->courier_name === 'Steadfast') {
                // Track Steadfast shipment
                $generalSetting = \App\Models\GeneralSetting::first();
                $apiKey = $generalSetting->steadfast_api_key ?? config('steadfast.api_key');
                $secretKey = $generalSetting->steadfast_secret_key ?? config('steadfast.secret_key');

                if (empty($apiKey) || empty($secretKey)) {
                    return response()->json(['success' => false, 'message' => 'Steadfast API credentials are not configured.']);
                }

                $apiEndpoint = "https://portal.steadfast.com.bd/api/v1/status-check?consignment_id={$order->consignmentId}";

                $headers = [
                    'Api-Key' => $apiKey,
                    'Secret-Key' => $secretKey,
                    'Content-Type' => 'application/json',
                ];

                // Log the request for debugging
                \Illuminate\Support\Facades\Log::info('Steadfast Tracking API Request', [
                    'order_id' => $order->id,
                    'endpoint' => $apiEndpoint,
                    'headers' => array_merge($headers, ['Api-Key' => '***', 'Secret-Key' => '***']), // Mask sensitive data
                ]);

                $response = \Illuminate\Support\Facades\Http::withHeaders($headers)->timeout(30)->get($apiEndpoint);

                // Log the response for debugging
                \Illuminate\Support\Facades\Log::info('Steadfast Tracking API Response', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                if (!$response->successful()) {
                    \Illuminate\Support\Facades\Log::error('Steadfast Tracking API HTTP Error', [
                        'order_id' => $order->id,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return response()->json(['success' => false, 'message' => 'Steadfast API HTTP Error: ' . $response->status()]);
                }

                $responseData = $response->json();

                if (isset($responseData['data'])) {
                    return response()->json(['success' => true, 'data' => $responseData['data'], 'courier' => 'Steadfast']);
                } elseif (isset($responseData['error']) || isset($responseData['message'])) {
                    $errorMessage = $responseData['error'] ?? $responseData['message'] ?? 'Unknown error from Steadfast API';
                    \Illuminate\Support\Facades\Log::error('Steadfast Tracking API Error', [
                        'order_id' => $order->id,
                        'error' => $errorMessage,
                        'response' => $responseData
                    ]);
                    return response()->json(['success' => false, 'message' => 'Steadfast API Error: ' . $errorMessage]);
                } else {
                    \Illuminate\Support\Facades\Log::warning('Unexpected Steadfast Tracking API Response', [
                        'order_id' => $order->id,
                        'response' => $responseData
                    ]);
                    return response()->json(['success' => false, 'message' => 'Unexpected response from Steadfast API: ' . json_encode($responseData)]);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Unsupported courier for tracking.']);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Shipment Tracking Exception', [
                'order_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Exception occurred: ' . $e->getMessage()]);
        }
    }
}
