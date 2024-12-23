{{-- ---Model edit/update tenant--------------------------------------------- --}}
@foreach ($tenants as $tenant_update)
<div id="updatetenant-{{ $tenant_update->id }}" tabindex="-1" aria-hidden="true" 
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-lg">
    <div class="relative w-full max-w-lg max-h-full bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Tenant admin2</h3>
            <button type="button" 
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-hide="updatetenant-{{ $tenant_update->id }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Form -->
        <form method="POST" action="{{ route('tenant.update', $tenant_update->id) }}" enctype="multipart/form-data" class="p-6">

            @csrf
            @method('PUT')
        
            <!-- Tenant Name -->
            <div class="mb-4">
                <label for="name-{{ $tenant_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Tenant Name
                </label>
                <input type="text" id="name-{{ $tenant_update->id }}" name="name" value="{{ $tenant_update->name }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        
            <!-- Phone Number -->
            <div class="mb-4">
                <label for="phone-{{ $tenant_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Phone Number
                </label>
                <input type="text" id="phone-{{ $tenant_update->id }}" name="phone" value="{{ $tenant_update->phone }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        
            <!-- KTP Photo -->
            <div class="mb-4">
                <label for="ktp-{{ $tenant_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    KTP Photo
                </label>
                <input type="file" id="ktp-{{ $tenant_update->id }}" name="ktp" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
                <!-- Preview Image -->
                <img id="foto_ktp_preview-{{ $tenant_update->id }}" class="mt-3 max-w-full max-h-32 rounded shadow-sm" alt="KTP Preview" />
            </div>
        
            <!-- Deposit -->
            <div class="mb-4">
                <label for="dp-{{ $tenant_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Deposit
                </label>
                <input type="number" id="dp-{{ $tenant_update->id }}" name="dp" value="{{ $tenant_update->dp }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        
            <!-- Check-in Date -->
            <div class="mb-4">
                <label for="start_date-{{ $tenant_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Check-in Date
                </label>
                <input type="date" id="start_date-{{ $tenant_update->id }}" name="start_date" value="{{ $tenant_update->start_date }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        
            <!-- Check-out Date -->
            <div class="mb-4">
                <label for="end_date-{{ $tenant_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Check-out Date
                </label>
                <input type="date" id="end_date-{{ $tenant_update->id }}" name="end_date" value="{{ $tenant_update->end_date }}" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        
            <!-- Notes -->
            <div class="mb-4">
                <label for="note-{{ $tenant_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Notes
                </label>
                <textarea id="note-{{ $tenant_update->id }}" name="note" rows="3" 
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">{{ $tenant_update->note }}</textarea>
            </div>
        
            <!-- Save Button -->
            <div class="flex items-center justify-end space-x-4">
                <button type="button" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                    data-modal-hide="updatetenant-{{ $tenant_update->id }}">
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fotoInputs = document.querySelectorAll('[id^="ktp-"]');
        fotoInputs.forEach(input => {
            input.addEventListener('change', (event) => {
                const tenantId = input.id.split('-')[1];
                const fotoPreview = document.getElementById(`foto_ktp_preview-${tenantId}`);
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        fotoPreview.src = e.target.result;
                        fotoPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    fotoPreview.style.display = 'none';
                }
            });
        });
    });
</script>

