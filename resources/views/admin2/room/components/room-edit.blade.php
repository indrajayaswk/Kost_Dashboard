{{--- model update/edit kamar---------------------------------------------------------------------------------------------------}}
  {{-- catatan, lebih baik ubah $kamar ke variable beda setiap edit,delete dan view model kah? --}}
  @foreach ($rooms as $kamar)
  <div id="updateroom-{{ $kamar->id }}" tabindex="-1" aria-hidden="true" 
      class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-lg">
      <div class="relative w-full max-w-lg max-h-full bg-white rounded-lg shadow dark:bg-gray-700">
          <!-- Modal Header -->
          <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Room</h3>
              <button type="button" 
                  class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 dark:hover:bg-gray-600 dark:hover:text-white"
                  data-modal-hide="updateroom-{{ $kamar->id }}">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
              </button>
          </div>
          
          <!-- Modal Form -->
          <form method="POST" action="{{ route('room.update', $kamar->id) }}" class="p-6">
              @csrf
              @method('PUT')
          
              <!-- Room Name -->
              <div class="mb-4">
                  <label for="room_number-{{ $kamar->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                      Room Name
                  </label>
                  <input type="text" id="room_number-{{ $kamar->id }}" name="room_number" value="{{ $kamar->room_number }}" 
                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
              </div>
          
              <!-- Room Type -->
              <div class="mb-4">
                  <label for="room_type-{{ $kamar->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                      Room Type
                  </label>
                  <input type="text" id="room_type-{{ $kamar->id }}" name="room_type" value="{{ $kamar->room_type }}" 
                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
              </div>
          
              <!-- Room Status -->
              <div class="mb-4">
                  <label for="room_status-{{ $kamar->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                      Room Status
                  </label>
                  <select id="room_status-{{ $kamar->id }}" name="room_status" 
                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
                      <option value="available" {{ $kamar->room_status == 'available' ? 'selected' : '' }}>Available</option>
                      <option value="occupied" {{ $kamar->room_status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                  </select>
              </div>
          
              <!-- Room Price -->
              <div class="mb-4">
                  <label for="room_price-{{ $kamar->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                      Room Price
                  </label>
                  <input type="number" id="room_price-{{ $kamar->id }}" name="room_price" value="{{ $kamar->room_price }}" 
                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
              </div>
          
              <!-- Save Button -->
              <div class="flex items-center justify-end space-x-4">
                  <button type="button" 
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                      data-modal-hide="updateroom-{{ $kamar->id }}">
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