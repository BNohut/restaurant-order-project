@extends('layouts.app')

@section('title', 'Order #' . substr($order->uuid, 0, 8))

@section('content')
<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="section-title">Order Details</h2>
        @if(Auth::user()->hasRole('manager'))
            <a href="{{ route('orders.manage') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Order Management
            </a>
        @elseif(Auth::user()->hasRole('courier'))
            <a href="{{ route('orders.courier') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Deliveries
            </a>
        @else
            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Status -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle"></i> Order #{{ substr($order->uuid, 0, 8) }}
                </h5>
                @if($order->status->name === 'Pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @elseif($order->status->name === 'Approved')
                    <span class="badge bg-info">Approved</span>
                @elseif($order->status->name === 'Preparing')
                    <span class="badge bg-primary">Preparing</span>
                @elseif($order->status->name === 'Ready for pickup')
                    <span class="badge bg-success">Ready for Pickup</span>
                @elseif($order->status->name === 'On the way')
                    <span class="badge bg-info">On the Way</span>
                @elseif($order->status->name === 'Delivered')
                    <span class="badge bg-success">Delivered</span>
                @elseif($order->status->name === 'Cancelled')
                    <span class="badge bg-danger">Cancelled</span>
                @else
                    <span class="badge bg-secondary">{{ $order->status->name }}</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Order ID:</strong> {{ substr($order->uuid, -6) }}</p>
                        <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        <p class="mb-1"><strong>Payment Method:</strong> 
                            @if($order->payment_method === 'cash')
                                Cash on Delivery
                            @elseif($order->payment_method === 'credit_card')
                                Credit Card
                            @elseif($order->payment_method === 'online')
                                Online Payment
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </p>
                        <p class="mb-0"><strong>Payment Status:</strong> 
                            @if($order->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($order->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        @if($order->status->name === 'Pending')
                            <div class="alert alert-warning">
                                <p class="mb-2"><i class="bi bi-clock"></i> <strong>Your order is pending confirmation</strong></p>
                                <p class="mb-0 small">The restaurant will confirm your order shortly.</p>
                            </div>
                            @if(Auth::user()->hasRole('client'))
                                <form action="{{ route('orders.cancel', $order->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-x-circle"></i> Cancel Order
                                    </button>
                                </form>
                            @endif
                        @elseif($order->status->name === 'Approved')
                            <div class="alert alert-info">
                                <p class="mb-2"><i class="bi bi-check-circle"></i> <strong>Your order is confirmed</strong></p>
                                <p class="mb-0 small">The restaurant is preparing your order.</p>
                            </div>
                        @elseif($order->status->name === 'Preparing')
                            <div class="alert alert-primary">
                                <p class="mb-2"><i class="bi bi-fire"></i> <strong>Your order is being prepared</strong></p>
                                <p class="mb-0 small">Your delicious food is being prepared fresh.</p>
                            </div>
                        @elseif($order->status->name === 'Ready for pickup')
                            <div class="alert alert-success">
                                <p class="mb-2"><i class="bi bi-bag-check"></i> <strong>Your order is ready</strong></p>
                                <p class="mb-0 small">Your food is ready for pickup/delivery.</p>
                            </div>
                        @elseif($order->status->name === 'On the way')
                            <div class="alert alert-info">
                                <p class="mb-2"><i class="bi bi-truck"></i> <strong>Your order is on the way</strong></p>
                                <p class="mb-0 small">Your food is on the way to you!</p>
                            </div>
                        @elseif($order->status->name === 'Delivered')
                            <div class="alert alert-success">
                                <p class="mb-2"><i class="bi bi-check-circle-fill"></i> <strong>Your order has been delivered</strong></p>
                                <p class="mb-0 small">Enjoy your meal!</p>
                            </div>
                        @elseif($order->status->name === 'Cancelled')
                            <div class="alert alert-danger">
                                <p class="mb-2"><i class="bi bi-x-circle-fill"></i> <strong>Your order has been canceled</strong></p>
                                <p class="mb-0 small">We're sorry for the inconvenience.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($order->special_instructions)
                    <div class="mt-3 p-3 bg-light rounded">
                        <p class="mb-1"><strong><i class="bi bi-chat-left-text"></i> Special Instructions:</strong></p>
                        <p class="mb-0">{{ $order->special_instructions }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bag"></i> Order Items</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $items = json_decode($order->items_snapshot, true) ?? [];
                            @endphp
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $item['name'] }}</div>
                                    @if(!empty($item['special_instructions'] ?? $item['notes'] ?? null))
                                        <div class="small text-muted">Note: {{ $item['special_instructions'] ?? $item['notes'] }}</div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item['quantity'] }}</td>
                                <td class="text-end">₺{{ number_format($item['unit_price'] ?? $item['price'], 2) }}</td>
                                <td class="text-end fw-bold">₺{{ number_format($item['subtotal'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Restaurant Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-shop"></i> Restaurant Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ $order->company->name }}</h5>
                        <p class="text-muted">{{ $order->company->description }}</p>
                        
                        @if($order->company->address)
                        <p class="mb-2">
                            <i class="bi bi-geo-alt text-primary"></i> 
                            {{ $order->company->address->address_line1 }}, 
                            {{ $order->company->address->district }}, 
                            {{ $order->company->address->city }}
                        </p>
                        @endif
                        
                        @if($order->company->phone)
                        <p class="mb-2">
                            <i class="bi bi-telephone text-primary"></i> {{ $order->company->phone }}
                        </p>
                        @endif
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('restaurants.show', $order->company->uuid) }}" class="btn btn-primary">
                            <i class="bi bi-shop"></i> View Restaurant
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courier Information -->
        @if($order->courier && in_array($order->status->name, ['Preparing', 'Ready for pickup', 'On the way', 'Delivered']))
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-truck"></i> Delivery Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ $order->courier->name }}</h5>
                        @if($order->courier->phone)
                        <p class="mb-2">
                            <i class="bi bi-telephone text-primary"></i> {{ $order->courier->phone }}
                        </p>
                        @endif
                        <p class="mb-0">
                            <i class="bi bi-clock text-primary"></i> Estimated delivery time: 30-45 minutes
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        @if(Auth::user()->hasRole('manager') && $order->status->name === 'Preparing')
                            <form action="{{ route('orders.ready', $order->uuid) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Mark as Ready
                                </button>
                            </form>
                        @elseif(Auth::user()->hasRole('courier') && $order->status->name === 'Ready for pickup')
                            <form action="{{ route('orders.on-the-way', $order->uuid) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-truck"></i> Mark as On The Way
                                </button>
                            </form>
                        @elseif(Auth::user()->hasRole('courier') && $order->status->name === 'On the way')
                            <form action="{{ route('orders.delivered', $order->uuid) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Mark as Delivered
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Delivery Address -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Delivery Address</h5>
            </div>
            <div class="card-body">
                <h6>{{ $order->address->title }}</h6>
                <p class="mb-1">{{ $order->address->address_line1 }}</p>
                @if($order->address->address_line2)
                    <p class="mb-1">{{ $order->address->address_line2 }}</p>
                @endif
                <p class="mb-1">{{ $order->address->district }}, {{ $order->address->city }}</p>
                <p class="mb-0">{{ $order->address->country }} {{ $order->address->postal_code }}</p>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-receipt"></i> Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₺{{ number_format($order->subtotal, 2) }}</span>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery Fee:</span>
                    <span>₺{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax:</span>
                    <span>₺{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span class="fs-5">₺{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Track Order -->
        @if(!in_array($order->status->name, ['Delivered', 'Cancelled']))
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Track Order</h5>
            </div>
            <div class="card-body">
                <div class="track-order">
                    <div class="step {{ in_array($order->status->name, ['Pending', 'Approved', 'Preparing', 'Ready for pickup', 'On the way', 'Delivered']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-clipboard-check"></i></div>
                        <div class="step-text">Order Placed</div>
                    </div>
                    <div class="step {{ in_array($order->status->name, ['Approved', 'Preparing', 'Ready for pickup', 'On the way', 'Delivered']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                        <div class="step-text">Confirmed</div>
                    </div>
                    <div class="step {{ in_array($order->status->name, ['Preparing', 'Ready for pickup', 'On the way', 'Delivered']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-fire"></i></div>
                        <div class="step-text">Preparing</div>
                    </div>
                    <div class="step {{ in_array($order->status->name, ['Ready for pickup', 'On the way', 'Delivered']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-bag-check"></i></div>
                        <div class="step-text">Ready</div>
                    </div>
                    <div class="step {{ in_array($order->status->name, ['On the way', 'Delivered']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-truck"></i></div>
                        <div class="step-text">On the Way</div>
                    </div>
                    <div class="step {{ in_array($order->status->name, ['Delivered']) ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-house-door"></i></div>
                        <div class="step-text">Delivered</div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <p class="small text-muted">Estimated delivery: 30-45 minutes</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Manager Actions -->
        @if(Auth::user()->hasRole('manager') && $order->status->name === 'Pending')
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Manager Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('orders.approve', $order->uuid) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Approve Order
                        </button>
                    </form>
                    <form action="{{ route('orders.reject', $order->uuid) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-x-circle"></i> Reject Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @elseif(Auth::user()->hasRole('manager') && $order->status->name === 'Approved')
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Manager Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.assign-courier', $order->uuid) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="courier_uuid" class="form-label">Assign Courier</label>
                        <select name="courier_uuid" id="courier_uuid" class="form-select" required>
                            <option value="">Select a courier</option>
                            @foreach(App\Models\User::role('courier')->get() as $courier)
                                <option value="{{ $courier->uuid }}">{{ $courier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-truck"></i> Assign Courier
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .track-order {
        display: flex;
        flex-direction: column;
        padding: 0;
    }
    .step {
        display: flex;
        align-items: center;
        padding: 10px 0;
        color: #6c757d;
    }
    .step.active {
        color: #764ba2;
    }
    .step-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid currentColor;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        position: relative;
    }
    .step.active .step-icon {
        background-color: #764ba2;
        color: white;
        border-color: #764ba2;
    }
    .step:not(:last-child) .step-icon:after {
        content: '';
        position: absolute;
        top: 28px;
        left: 13px;
        height: 20px;
        border-left: 2px solid currentColor;
    }
    .step-text {
        font-weight: 600;
    }
</style>
@endsection 