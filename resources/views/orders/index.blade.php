@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="section-title">My Orders</h2>
    </div>
</div>

@if($orders->isEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-4">You don't have any orders yet</h3>
                    <p class="text-muted">Explore our restaurants and place your first order today!</p>
                    <a href="{{ route('restaurants.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-shop"></i> Browse Restaurants
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Restaurant</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <span class="fw-bold">
                                            #{{ substr($order->uuid, 0, 8) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $order->company->name }}
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td>
                                        @if($order->status->name === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($order->status->name === 'confirmed')
                                            <span class="badge bg-info">Confirmed</span>
                                        @elseif($order->status->name === 'preparing')
                                            <span class="badge bg-primary">Preparing</span>
                                        @elseif($order->status->name === 'ready')
                                            <span class="badge bg-success">Ready</span>
                                        @elseif($order->status->name === 'out_for_delivery')
                                            <span class="badge bg-info">Out for Delivery</span>
                                        @elseif($order->status->name === 'delivered')
                                            <span class="badge bg-success">Delivered</span>
                                        @elseif($order->status->name === 'canceled')
                                            <span class="badge bg-danger">Canceled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $order->status->name }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">₺{{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endif
@endsection 