{{-- Modal for adding meter --}}
<div id="addmeter" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-lg">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Meter</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" id="close-modal-btn">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal body -->
            <form action="{{ route('meter.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <!-- Room Number -->
                    <label for="tenant_room_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tenant Room</label>
                    <select id="tenant_room_id" name="tenant_room_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        <option value="" disabled selected>Select a tenant room</option>
                        @foreach($tenantRooms as $tenantRoom)
                            <option value="{{ $tenantRoom->id }}">
                                {{-- {{ $tenantRoom->primaryTenant->name ?? 'Unknown Tenant' }} & {{ $tenantRoom->secondaryTenant->name ?? 'none' }} -  --}}
                                {{ $tenantRoom->room->room_number ?? 'Unknown Room' }}
                            </option>
                        @endforeach
                    </select>

                    <!-- KWH Number -->
                    <div>
                        <label for="kwh_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">KWH Number</label>
                        <input type="text" id="kwh_number" name="kwh_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter KWH number" required>
                    </div>

                    <!-- Price per KWH -->
                    <div>
                        <label for="price_per_kwh" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price per KWH</label>
                        <input type="number" id="price_per_kwh" name="price_per_kwh" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter price per KWH" required>
                    </div>

                    <!-- Month -->
                    <div>
                        <label for="month" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of Month</label>
                        <input type="date" id="month" name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    </div>
                </div>

                <button type="submit" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Add Meter
                </button>
            </form>
        </div>
    </div>
</div>