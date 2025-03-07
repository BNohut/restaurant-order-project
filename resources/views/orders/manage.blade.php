@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Restaurant Order Management</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courier</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                <a href="{{ route('orders.show', $order->uuid) }}" class="hover:underline">
                                    #{{ substr($order->uuid, 0, 8) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->customer->name ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                      style="background-color: {{ $order->status->color ?? '#ccc' }}20; color: {{ $order->status->color ?? '#666' }}">
                                    {{ $order->status->name ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($order->courier)
                                    {{ $order->courier->name }}
                                @else
                                    <span class="text-yellow-500">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <!-- View Order Details -->
                                    <a href="{{ route('orders.show', $order->uuid) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Details
                                    </a>

                                    @if($order->status->name == 'Pending')
                                        <!-- Approve Order -->
                                        <form action="{{ route('orders.approve', $order->uuid) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                Approve
                                            </button>
                                        </form>

                                        <!-- Reject Order -->
                                        <form action="{{ route('orders.reject', $order->uuid) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->status->name == 'Approved' && !$order->courier)
                                        <!-- Assign Courier -->
                                        <button type="button" class="text-blue-600 hover:text-blue-900" onclick="document.getElementById('assign-courier-modal-{{ $order->uuid }}').classList.remove('hidden')">
                                            Assign Courier
                                        </button>
                                    @endif

                                    @if($order->status->name == 'Preparing')
                                        <!-- Mark as Ready -->
                                        <form action="{{ route('orders.ready', $order->uuid) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                Mark as Ready
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <!-- Assign Courier Modal -->
                                @if($order->status->name == 'Approved' && !$order->courier)
                                <div id="assign-courier-modal-{{ $order->uuid }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                        <div class="mt-3 text-center">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900">Assign Courier</h3>
                                            <div class="mt-2 px-7 py-3">
                                                <form action="{{ route('orders.assign-courier', $order->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label for="courier_uuid" class="block text-gray-700 text-sm font-bold mb-2">Select Courier:</label>
                                                        <select name="courier_uuid" id="courier_uuid" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                            <option value="">Select a courier</option>
                                                            @foreach($couriers as $courier)
                                                                <option value="{{ $courier->uuid }}">{{ $courier->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="flex items-center justify-between">
                                                        <button type="button" onclick="document.getElementById('assign-courier-modal-{{ $order->uuid }}').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                                            Assign
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No orders found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection 