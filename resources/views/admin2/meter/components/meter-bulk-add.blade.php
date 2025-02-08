{{-- Bulk Add Meter Modal --}}
<div id="addbulkmeter" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full backdrop-blur-lg">
    <div class="relative p-4 w-full max-w-5xl bg-white rounded-lg shadow dark:bg-gray-800">
        <!-- Modal Header -->
        <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bulk Add Meters</h3>
            <button type="button" class="text-gray-400 hover:bg-gray-200 rounded-lg p-1.5 dark:hover:bg-gray-600" id="close-bulk-modal-btn">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('meter.bulk_store') }}" method="POST">
            @csrf
            
            <!-- Global Fields -->
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per KWH</label>
                    <input type="number" id="global-price" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Month</label>
                    <input type="date" id="global-month" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600" required>
                </div>
            </div>
            
            <div class="overflow-x-auto max-h-96">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Room</th>
                            <th class="px-4 py-3">KWH Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenantRooms as $index => $tenantRoom)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3">
                                {{ $tenantRoom->room->room_number ?? 'Unknown Room' }}
                                <input type="hidden" name="meters[{{ $index }}][tenant_room_id]" value="{{ $tenantRoom->id }}">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="meters[{{ $index }}][kwh_number]" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600" required>
                            </td>
                            <input type="hidden" name="meters[{{ $index }}][price_per_kwh]" class="price-input">
                            <input type="hidden" name="meters[{{ $index }}][month]" class="month-input">
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Submit Button -->
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-5 py-2.5 text-white bg-primary-700 hover:bg-primary-800 rounded-lg">Submit Meters</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('global-price').addEventListener('input', function() {
        document.querySelectorAll('.price-input').forEach(input => input.value = this.value);
    });
    
    document.getElementById('global-month').addEventListener('input', function() {
        document.querySelectorAll('.month-input').forEach(input => input.value = this.value);
    });
</script>
