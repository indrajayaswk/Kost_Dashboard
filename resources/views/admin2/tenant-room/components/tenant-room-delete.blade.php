@foreach ($tenantRooms as $tenantRoom)
<div id="deleteModalTenantRoom-{{ $tenantRoom->id }}" tabindex="-1" aria-hidden="true" 
    class="fixed inset-0 z-50 flex items-center justify-center hidden w-full h-full bg-black bg-opacity-50">
    <div class="relative w-full max-w-md p-6 bg-white rounded-lg shadow dark:bg-gray-800">
        <!-- Close Button -->
        <button type="button" 
            class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 dark:hover:bg-gray-600 dark:hover:text-white" 
            data-modal-hide="deleteModalTenantRoom-{{ $tenantRoom->id }}">
            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" 
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" 
                    clip-rule="evenodd" />
            </svg>
            <span class="sr-only">Close modal</span>
        </button>

        <!-- Modal Content -->
        <div class="text-center">
            <!-- Warning Icon -->
            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" aria-hidden="true" fill="currentColor" 
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" 
                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" 
                    clip-rule="evenodd" />
            </svg>
            <!-- Warning Text -->
            <h3 class="mt-4 text-lg font-medium text-gray-800 dark:text-white">
                Are you sure you want to delete this tenant-room assignment?
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                @if ($tenantRoom->primaryTenant || $tenantRoom->secondaryTenant)
                    Assigned to: 
                    {{ optional($tenantRoom->primaryTenant)->name ?? 'N/A' }}
                    @if ($tenantRoom->secondaryTenant)
                        & {{ optional($tenantRoom->secondaryTenant)->name }}
                    @endif
                @else
                    No tenants assigned
                @endif
                in room "{{ optional($tenantRoom->room)->room_number }}"
            </p>

            <!-- Form Buttons -->
            <form action="{{ route('tenant-room.destroy', $tenantRoom->id) }}" method="POST" class="mt-6">
                @csrf
                @method('DELETE')
                <div class="flex items-center justify-center space-x-4">
                    <!-- Cancel Button -->
                    <button type="button" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                        data-modal-hide="deleteModalTenantRoom-{{ $tenantRoom->id }}">
                        Cancel
                    </button>
                    <!-- Confirm Delete Button -->
                    <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
                        Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
