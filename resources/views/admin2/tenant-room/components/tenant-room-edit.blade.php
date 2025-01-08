@foreach ($tenantRooms as $tenantRoom)
<div id="updatetenantroom-{{ $tenantRoom->id }}" tabindex="-1" aria-hidden="true" 
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-lg">
    <div class="relative w-full max-w-lg max-h-full bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Tenant Room Assignment</h3>
            <button type="button" 
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-hide="updatetenantroom-{{ $tenantRoom->id }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Form -->
        <form method="POST" action="{{ route('tenant-room.update', $tenantRoom->id) }}" class="p-6">
            @csrf
            @method('PUT')

        <!-- Primary Tenant -->
        <div class="mb-4">
            <label for="primary_tenant_id-{{ $tenantRoom->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Primary Tenant
            </label>
            <select id="primary_tenant_id-{{ $tenantRoom->id }}" name="primary_tenant_id" 
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
                <option value="">Select Primary Tenant</option>
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}" 
                        {{ $tenantRoom->primary_tenant_id == $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Secondary Tenant -->
        <div class="mb-4">
            <label for="secondary_tenant_id-{{ $tenantRoom->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Secondary Tenant
            </label>
            <select id="secondary_tenant_id-{{ $tenantRoom->id }}" name="secondary_tenant_id" 
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
                <option value="">(None)</option>
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}" 
                        {{ $tenantRoom->secondary_tenant_id == $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->name }}
                    </option>
                @endforeach
            </select>
        </div>


            <!-- Room -->
            <div class="mb-4">
                <label for="room_id-{{ $tenantRoom->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Room
                </label>
                <select id="room_id-{{ $tenantRoom->id }}" name="room_id" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" 
                            {{ $tenantRoom->room_id == $room->id ? 'selected' : '' }}>
                            {{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status-{{ $tenantRoom->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Status
                </label>
                <select id="status-{{ $tenantRoom->id }}" name="status" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
                    <option value="active" {{ $tenantRoom->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $tenantRoom->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <!-- Add more statuses as needed -->
                </select>
            </div>

            <!-- Check-in Date -->
            <div class="mb-4">
                <label for="start_date-{{ $tenantRoom->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Check-in Date
                </label>
                <input type="date" id="start_date-{{ $tenantRoom->id }}" name="start_date" value="{{ $tenantRoom->start_date }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>

            <!-- Check-out Date -->
            <div class="mb-4">
                <label for="end_date-{{ $tenantRoom->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Check-out Date
                </label>
                <input type="date" id="end_date-{{ $tenantRoom->id }}" name="end_date" value="{{ $tenantRoom->end_date }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label for="note-{{ $tenantRoom->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Notes
                </label>
                <textarea id="note-{{ $tenantRoom->id }}" name="note" rows="3" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">{{ $tenantRoom->note }}</textarea>
            </div>

            <!-- Save Button -->
            <div class="flex items-center justify-end space-x-4">
                <button type="button" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                    data-modal-hide="updatetenantroom-{{ $tenantRoom->id }}">
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
