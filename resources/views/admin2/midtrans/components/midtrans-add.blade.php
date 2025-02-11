<x-app-layout>
    <div class="container mx-auto py-8">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">
            Meter Data for Room: {{ $tenantRoom->room->room_number }} - {{ $tenantRoom->primaryTenant->name }}
        </h2>
        <button onclick="refreshMeterData()" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
            Refresh Data
        </button>
        
        @if ($meters->isEmpty())
            <p class="text-gray-600">No meter data found for this room.</p>
        @else
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="py-2 px-4 border-b">Month</th>
                            <th class="py-2 px-4 border-b">Total KWH</th>
                            <th class="py-2 px-4 border-b">Total Price</th>
                            <th class="py-2 px-4 border-b">Price Per KWH</th>
                            <th class="py-2 px-4 border-b">Payment URL</th>
                            <th class="py-2 px-4 border-b">Status</th>
                            <th class="py-2 px-4 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($meters as $meter)
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">{{ $meter->meter_month }}</td>
                                <td class="py-2 px-4 border-b">{{ $meter->total_kwh }}</td>
                                <td class="py-2 px-4 border-b">{{ $meter->total_price }}</td>
                                <td class="py-2 px-4 border-b">{{ $meter->price_per_kwh }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if ($meter->pay_proof)
                                        <a href="{{ $meter->pay_proof }}" target="_blank" class="text-blue-500 hover:underline">Pay Now</a>
                                    @else
                                        <span class="text-gray-500">Not Available</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <span class="px-2 py-1 rounded text-white {{ $meter->status === 'paid' ? 'bg-green-500' : 'bg-red-500' }}">
                                        {{ ucfirst($meter->status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <form action="{{ route('midtrans.create-invoice') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="tenant_room_id" value="{{ $tenantRoom->id }}">
                                        <input type="hidden" name="meter_id" value="{{ $meter->id }}">
                                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Create Invoice</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Display Debugging Data -->
        @isset($debugData)
            <h3 class="mt-8 text-lg font-semibold text-gray-800">Debugging Data</h3>
            <div class="bg-gray-100 p-4 rounded-lg">
                <pre>{{ json_encode($debugData, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endisset

        <!-- Display Error Message -->
        @if (isset($error))
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mt-4">
                <strong>Error:</strong> {{ $error }}
            </div>
        @endif

        @isset($debugData)
        <h3 class="mt-8 text-lg font-semibold text-gray-800">Debugging Information</h3>
        <div class="bg-gray-100 p-4 rounded-lg">
            <pre>{{ json_encode($debugData, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endisset

    </div>
</x-app-layout>

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
        background-color: #f3f4f6;
    }

    tr:nth-child(even) {
        background-color: #f9fafb;
    }

    tr:hover {
        background-color: #f1f5f9;
    }

    .bg-white {
        background-color: white;
    }

    .shadow {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .rounded-lg {
        border-radius: 8px;
    }

    .text-gray-600 {
        color: #4b5563;
    }

    .bg-gray-100 {
        background-color: #f7fafc;
    }
</style>

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
                        let row = `<tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b">${meter.month}</td>
                            <td class="py-2 px-4 border-b">${meter.total_kwh}</td>
                            <td class="py-2 px-4 border-b">${meter.total_price}</td>
                            <td class="py-2 px-4 border-b">${meter.price_per_kwh}</td>
                            <td class="py-2 px-4 border-b">
                                ${meter.pay_proof ? `<a href="${meter.pay_proof}" target="_blank" class="text-blue-500 hover:underline">Pay Now</a>` : '<span class="text-gray-500">Not Available</span>'}
                            </td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 rounded text-white ${meter.status === 'paid' ? 'bg-green-500' : 'bg-red-500'}">
                                    ${meter.status.charAt(0).toUpperCase() + meter.status.slice(1)}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <form action="{{ route('midtrans.create-invoice') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tenant_room_id" value="${tenantRoomId}">
                                    <input type="hidden" name="meter_id" value="${meter.id}">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Create Invoice</button>
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
</script>

