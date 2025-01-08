<x-app-layout>
    <div>
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4 ">
            <div class="w-full md:w-1/2">
                <form action="{{ route('tenant.index') }}" method="GET" class="flex items-center">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="simple-search" value="{{ request()->get('search') }}" class="phone bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search" >
                    </div>
                {{-- Custom Filters --}}
                    <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown" class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filters
                        <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </form>
            </div>
            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                <button type="button" id="*" data-modal-target="addtenant-room" data-modal-toggle="addtenant-room" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Add tenant for room
                </button>
            </div>
        </div>

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-4">No</th>
                    <th scope="col" class="px-4 py-4">primary Tenant Name</th>
                    <th scope="col" class="px-4 py-4">secondary Tenant Name</th>
                    {{-- <th scope="col" class="px-4 py-3">Phone</th> --}}
                    <th scope="col" class="px-4 py-3">Room Number</th>
                    <th scope="col" class="px-4 py-3">Status</th>
                    <th scope="col" class="px-4 py-4">Notes</th>
                    <th scope="col" class="px-4 py-3">start At</th>
                    <th scope="col" class="px-4 py-3">End At</th>
                    <th scope="col" class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenantRooms as $index => $tenantRoom)
                    <tr>
                        <td class="px-4 py-4">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">{{ $tenantRoom->primaryTenant->name; }}</td>
                        <td class="px-4 py-4">{{ $tenantRoom->secondaryTenant->name ?? 'N/A'; }}</td>
                        {{-- <td class="px-4 py-4">{{ $tenantRoom->tenant->phone }}</td> --}}
                        <td class="px-4 py-4">{{ $tenantRoom->room->room_number }}</td>
                        <td class="px-4 py-4">{{ ucfirst($tenantRoom->status) }}</td>
                        <td class="px-4 py-4">{{$tenantRoom->note}}</td>
                        <td class="px-4 py-4">{{$tenantRoom->start_date}}</td>
                        <td class="px-4 py-4">{{$tenantRoom->end_date}}</td>
                        <td class="px-4 py-3 flex items-center justify-end">
                            <!-- Action Button -->
                            <button id="action-dropdown-room{{ $index }}" 
                                data-dropdown-toggle="dropdown-room{{ $index }}" 
                                class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                                type="button">
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="dropdown-room{{ $index }}" 
                                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                              <ul class="py-1 text-sm" aria-labelledby="action-dropdown-room{{ $index }}">
                                  <li>
                                      <button 
                                          type="button"  
                                          data-modal-target="updatetenantroom-{{ $tenantRoom->id }}"
                                          data-modal-toggle="updatetenantroom-{{ $tenantRoom->id }}"
                                          class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                          <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                              <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                              <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                          </svg>
                                          Edit
                                      </button>
                                  </li>
                                  <li>
                                      <button 
                                          type="button" 
                                          data-modal-target="deleteModalTenantRoom-{{ $tenantRoom->id }}" 
                                          data-modal-toggle="deleteModalTenantRoom-{{ $tenantRoom->id }}" 
                                          class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 text-red-500 dark:hover:text-red-400">
                                          <svg class="w-4 h-4 mr-2" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                              <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor" 
                                                  d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.06632 0.30087 7.89922 0.300781H6.09922ZM8.20322 2.10078L7.85522 1.34878C7.8282 1.29081 7.78682 1.2397 7.73502 1.20099C7.68321 1.16229 7.62293 1.13694 7.55922 1.12798H6.43922C6.37551 1.13694 6.31523 1.16229 6.26343 1.20099C6.21163 1.2397 6.17025 1.29081 6.14322 1.34878L5.79522 2.10078H8.20322Z" />
                                          </svg>
                                          Delete
                                      </button>
                                  </li>
                              </ul>
                          </div>
                      </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}
        <!-- Pagination -->
        <div class="mt-4">
            {{ $tenantRooms->links() }}
        </div>
    </div>
@include('admin2.tenant-room.components.tenant-room-add')
@include('admin2.tenant-room.components.tenant-room-delete')
@include('admin2.tenant-room.components.tenant-room-edit')
</x-app-layout>
