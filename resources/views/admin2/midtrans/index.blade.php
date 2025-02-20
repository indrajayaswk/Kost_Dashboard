<x-app-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Filter Meter Data</h2>
            <form action="{{ route('midtrans.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Room Filter -->
                <div>
                    <label for="tenant_room_id" class="block text-sm font-medium text-gray-700">Room</label>
                    <select name="tenant_room_id" id="tenant_room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Rooms</option>
                        @foreach ($tenantRooms as $room)
                            <option value="{{ $room->id }}" {{ request('tenant_room_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->room->room_number }} - {{ $room->primaryTenant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <!-- Month Filter -->
                <div>
                    <label for="meter_month" class="block text-sm font-medium text-gray-700">Month</label>
                    <input type="month" name="meter_month" id="meter_month" 
                           value="{{ request('meter_month') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Filter Button -->
                <div class="self-end">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Meter Data Table -->
        @if ($meters->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-600">No meter data found matching your criteria.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total KWH</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price/KWH</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Electric Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($meters as $meter)
                            <tr class="hover:bg-gray-50">
                                <!-- Room Number -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $meter->tenantRoom->room->room_number }}
                                </td>

                                <!-- Tenant Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $meter->tenantRoom->primaryTenant->name }}
                                </td>

                                <!-- Phone Number -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $meter->tenantRoom->primaryTenant->phone }}
                                </td>

                                <!-- Month -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($meter->meter_month)->format('F Y') }}
                                </td>

                                <!-- Total KWH -->
                                <td class="px-6 py-4 whitespace-nowrap">{{ $meter->total_kwh }}</td>

                                <!-- Price/KWH -->
                                <td class="px-6 py-4 whitespace-nowrap">RP {{ number_format($meter->price_per_kwh, 0, ',', '.') }}</td>

                                <!-- Electric Cost -->
                                <td class="px-6 py-4 whitespace-nowrap">RP {{ number_format($meter->total_price, 0, ',', '.') }}</td>

                                <!-- Room Price -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    RP {{ number_format($meter->tenantRoom->room->room_price, 0, ',', '.') }}
                                </td>

                                <!-- Total -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    RP {{ number_format($meter->tenantRoom->room->room_price + $meter->total_price, 0, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $meter->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($meter->status) }}
                                    </span>
                                </td>

                                <!-- URL -->
                                <td class="px-6 py-4 whitespace-nowrap max-w-[200px] truncate">
                                    @if($meter->pay_proof)
                                        <a href="{{ $meter->pay_proof }}" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline">
                                            {{ Str::limit($meter->pay_proof, 30) }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">N/A</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($meter->status === 'unpaid')
                                        <form action="{{ route('midtrans.create-invoice') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="tenant_room_id" value="{{ $meter->tenant_room_id }}">
                                            <input type="hidden" name="meter_id" value="{{ $meter->id }}">
                                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                                Create Invoice
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $meters->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-app-layout>