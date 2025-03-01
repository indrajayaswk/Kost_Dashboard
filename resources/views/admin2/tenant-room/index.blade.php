<x-app-layout>
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Tenant Room Management</h2>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <!-- Search Form -->
                <form action="{{ route('tenant-room.index') }}" method="GET" class="flex items-center">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="simple-search" value="{{ request()->get('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search">
                    </div>
                </form>
                <!-- Add Tenant Button -->
                <button type="button" id="*" data-modal-target="addtenant-room" data-modal-toggle="addtenant-room" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Add Tenant for Room
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table Section -->
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Primary Tenant</th>
                        <th scope="col" class="px-6 py-3">Secondary Tenant</th>
                        <th scope="col" class="px-6 py-3">Room Number</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Notes</th>
                        <th scope="col" class="px-6 py-3">Start Date</th>
                        <th scope="col" class="px-6 py-3">End Date</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenantRooms as $index => $tenantRoom)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $tenantRoom->primaryTenant->name }}</td>
                            <td class="px-6 py-4">{{ $tenantRoom->secondaryTenant->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $tenantRoom->room->room_number }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $tenantRoom->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-sm font-medium px-2.5 py-0.5 rounded-full">
                                    {{ ucfirst($tenantRoom->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $tenantRoom->note }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($tenantRoom->start_date)->format('m-Y') }}</td>
                            {{-- <td class="px-6 py-4">{{ $tenantRoom->start_date }}</td> --}}
                            <td class="px-6 py-4">{{ $tenantRoom->end_date }}</td>
                            <td class="px-6 py-4">
                                <!-- Action Dropdown -->
                                <div class="flex items-center space-x-2">
                                    <button id="action-dropdown-room{{ $index }}" data-dropdown-toggle="dropdown-room{{ $index }}" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-room{{ $index }}" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                            <li>
                                                <button type="button" data-modal-target="updatetenantroom-{{ $tenantRoom->id }}" data-modal-toggle="updatetenantroom-{{ $tenantRoom->id }}" class="w-full flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" data-modal-target="deleteModalTenantRoom-{{ $tenantRoom->id }}" data-modal-toggle="deleteModalTenantRoom-{{ $tenantRoom->id }}" class="w-full flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 text-red-500 dark:hover:text-red-400">
                                                    <svg class="w-4 h-4 mr-2" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor" d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.06632 0.30087 7.89922 0.300781H6.09922ZM8.20322 2.10078L7.85522 1.34878C7.8282 1.29081 7.78682 1.2397 7.73502 1.20099C7.68321 1.16229 7.62293 1.13694 7.55922 1.12798H6.43922C6.37551 1.13694 6.31523 1.16229 6.26343 1.20099C6.21163 1.2397 6.17025 1.29081 6.14322 1.34878L5.79522 2.10078H8.20322Z" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->

        <div class="mt-6 flex justify-center">
            {{ $tenantRooms->links() }}
        </div>
    </div>

    <!-- Modals -->
    @include('admin2.tenant-room.components.tenant-room-add')
    @include('admin2.tenant-room.components.tenant-room-delete')
    @include('admin2.tenant-room.components.tenant-room-edit')
</x-app-layout>