<x-app-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Meter Data for Room: {{ $tenantRoom->room->room_number }} - {{ $tenantRoom->primaryTenant->name }}
            </h2>
            <button onclick="refreshMeterData()" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-300">
                Refresh Data
            </button>
        </div>

        <!-- No Data Message -->
        @if ($meters->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-600">No meter data found for this room.</p>
            </div>
        @else
            <!-- Meter Data Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total KWH</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Eletric Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Per KWH</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Price </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sum Total </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment URL</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($meters as $meter)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $meter->meter_month }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $meter->total_kwh }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RP {{ number_format($meter->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RP {{ number_format($meter->price_per_kwh, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RP {{ number_format($tenantRoom->room->room_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RP {{ number_format($tenantRoom->room->room_price + $meter->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 hover:text-blue-700">
                                    @if ($meter->pay_proof)
                                        <a href="{{ $meter->pay_proof }}" target="_blank" class="hover:underline">Pay Now</a>
                                    @else
                                        <span class="text-gray-500">Not Available</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $meter->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($meter->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('midtrans.create-invoice') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="tenant_room_id" value="{{ $tenantRoom->id }}">
                                        <input type="hidden" name="meter_id" value="{{ $meter->id }}">
                                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">
                                            Create Invoice
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Debugging Data Section -->
        @isset($debugData)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Debugging Information</h3>
                <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                    <pre class="text-sm text-gray-700">{{ json_encode($debugData, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endisset

        <!-- Error Message Section -->
        @if (isset($error))
            <div class="mt-8 bg-red-50 p-6 rounded-lg shadow-md">
                <p class="text-red-800"><strong>Error:</strong> {{ $error }}</p>
            </div>
        @endif
    </div>
</x-app-layout>

<!-- Custom Styles -->
<style>
    .container {
        max-width: 1200px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        text-align: left;
        padding: 12px 16px;
    }

    th {
        background-color: #f9fafb;
    }

    tr:nth-child(even) {
        background-color: #f9fafb;
    }

    tr:hover {
        background-color: #f3f4f6;
    }

    .bg-white {
        background-color: white;
    }

    .shadow-md {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .rounded-lg {
        border-radius: 8px;
    }

    .text-gray-600 {
        color: #4b5563;
    }

    .bg-gray-50 {
        background-color: #f9fafb;
    }
</style>

<!-- JavaScript for Refreshing Meter Data -->
<script>
    function refreshMeterData() {
        const tenantRoomId = "{{ $tenantRoom->id }}";
        
        fetch(`/meters/${tenantRoomId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let tableBody = document.querySelector("tbody");
                    tableBody.innerHTML = ""; // Clear the table

                    data.meters.forEach(meter => {
                        let row = `<tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${meter.meter_month}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${meter.total_kwh}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RP ${formatPrice(meter.total_price)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RP ${formatPrice(meter.price_per_kwh)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RP ${formatPrice({{ $tenantRoom->room->room_price }})}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RP ${formatPrice({{ $tenantRoom->room->room_price }} + meter.total_price)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 hover:text-blue-700">
                                ${meter.pay_proof ? `<a href="${meter.pay_proof}" target="_blank" class="hover:underline">Pay Now</a>` : '<span class="text-gray-500">Not Available</span>'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${meter.status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${meter.status.charAt(0).toUpperCase() + meter.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('midtrans.create-invoice') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tenant_room_id" value="${tenantRoomId}">
                                    <input type="hidden" name="meter_id" value="${meter.id}">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">
                                        Create Invoice
                                    </button>
                                </form>
                            </td>
                        </tr>`;
                        tableBody.innerHTML += row;
                    });
                } else {
                    console.error("Failed to fetch meters:", data.error);
                }
            })
            .catch(error => console.error("Error fetching meter data:", error));
    }

    // Helper function to format prices with thousand separators
    function formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
</script>