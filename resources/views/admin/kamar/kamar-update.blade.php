{{-- 
<div id="updatekamar-{{ $kamar->id }}" tabindex="-1" aria-hidden="true" 
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Edit Kamar
                </h3>
                <button type="button" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="updatekamar-{{ $kamar->id }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <form id="editTenantForm{{ $kamar->id }}" method="POST" action="{{ route('kamar.update', $kamar->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" id="nama" name="nama" value="{{ $kamar->nama }}"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="telphon" class="block text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                        <input type="text" id="telphon" name="telphon" value="{{ $kamar->telphon }}"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <!-- Add other fields as required -->
                </div>
                <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" 
                        class="text-white bg-blue-500 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 

@if(!isset($kamar)) 
    @php
        // Temporary dummy data for testing
        $kamar = (object) [
            'id' => 1,
            'nomer_kamar' => 'A1',
            'jenis_kamar' => 'Deluxe',
            'harga_kamar' => '1000000',
            'status_kamar' => 'available',
            'telphon' => '08123456789',
        ];
    @endphp
@endif



{{-- TIDAK JADI DIPAKAI, SEMUA FORM CRDU ADA DI INDEX --}}

