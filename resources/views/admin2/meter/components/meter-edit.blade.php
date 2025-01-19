  {{--- model update/edit kamar---------------------------------------------------------------------------------------------------}}
  {{-- catatan, lebih baik ubah $kamar ke variable beda setiap edit,delete dan view model kah? --}}
  @foreach ($meters as $meter_update)
  <div id="updatemeter-{{ $meter_update->id }}" tabindex="-1" aria-hidden="true" 
      class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-lg">
      <div class="relative w-full max-w-lg max-h-full bg-white rounded-lg shadow dark:bg-gray-700">
          <!-- Modal Header -->
          <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Meters</h3>
              <button type="button" 
                  class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 dark:hover:bg-gray-600 dark:hover:text-white"
                  data-modal-hide="updatemeter-{{ $meter_update->id }}">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
              </button>
          </div>
          
          <!-- Modal Form -->
          <form method="POST" action="{{ route('meter.update', $meter_update->id) }}" class="p-6">
            @csrf
            @method('PUT')
        
            <!-- Tenant Room ID (hidden) -->
            <input type="hidden" name="tenant_room_id" value="{{ $meter_update->tenantRoom ? $meter_update->tenantRoom->id : '' }}">
        
            <!-- Meter Reading -->
            <div class="mb-4">
                <label for="meters-{{ $meter_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Meter Reading
                </label>
                <input type="number" id="meters-{{ $meter_update->id }}" name="kwh_number" value="{{ $meter_update->kwh_number }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        
            <!-- Room number (data from related TenantRoom and Room model) -->
            <div class="mb-4">
                <label for="room_number-{{ $meter_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Room Number
                </label>
                <input type="text" id="room_number-{{ $meter_update->id }}" name="room_number" 
                    value="{{ $meter_update->tenantRoom ? $meter_update->tenantRoom->room->room_number : 'N/A' }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm" readonly>
            </div>
        
            <!-- Price Per KWH -->
            <div class="mb-4">
                <label for="meters-{{ $meter_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Price Per KWH
                </label>
                <input type="number" id="meters-{{ $meter_update->id }}" name="price_per_kwh" value="{{ $meter_update->price_per_kwh }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        
            <!-- Month -->
            <div class="mb-4">
                <label for="month-{{ $meter_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Month
                </label>
                <input type="date" id="month-{{ $meter_update->id }}" name="month" value="{{ $meter_update->month }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        
            <!-- Save Button -->
            <div class="flex items-center justify-end space-x-4">
                <button type="button" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                    data-modal-hide="updatemeter-{{ $meter_update->id }}">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
                    
      </div>
  </div>
  @endforeach