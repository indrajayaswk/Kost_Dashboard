<x-app-layout>
    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden ">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4 ">
            <div class="w-full md:w-1/2">
                <form action="" method="GET" class="flex items-center space-x-2">
                    <div class="relative w-full">
                        <input type="text" name="search" id="simple-search" value="{{ request()->get('search') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Search">
                    </div>
                
                    <select name="filter_by" class="border border-gray-300 text-sm rounded-lg p-2 dark:bg-gray-700 dark:text-white">
                        <option value="">Filter By</option>
                        <option value="room_number" {{ request('filter_by') == 'room_number' ? 'selected' : '' }}>Room Number</option>
                        <option value="kwh_number" {{ request('filter_by') == 'kwh_number' ? 'selected' : '' }}>KWH Number</option>
                        <option value="meter_month" {{ request('filter_by') == 'meter_month' ? 'selected' : '' }}>Month</option>
                        <option value="total_kwh" {{ request('filter_by') == 'total_kwh' ? 'selected' : '' }}>Total KWH</option>
                    </select>
                
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-primary-500">
                        Search
                    </button>
                </form>                
            </div>
  
            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                <button type="button" id="*" data-modal-target="addmeter" data-modal-toggle="addmeter" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Add Meter
                </button>
                <button type="button" id="*" data-modal-target="addbulkmeter" data-modal-toggle="addbulkmeter" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Add Bulk Meter
                </button>
            </div>
        </div>  
        <div class="overflow-x-auto">
          <div class="relative max-w-full">
              <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                  <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                      <tr>
                        <th scope="col" class="px-4 py-4">No</th>
                        <th scope="col" class="px-4 py-4">Meter ID</th>
                        <th scope="col" class="px-4 py-4">Room Number</th>
                        <th scope="col" class="px-4 py-4">Previous KWH</th>
                        <th scope="col" class="px-4 py-4">KWH Number</th>
                        <th scope="col" class="px-4 py-4">Total KWH</th>
                        <th scope="col" class="px-4 py-4">Total Price</th>
                        <th scope="col" class="px-4 py-4">Price per KWH</th>
                        <th scope="col" class="px-4 py-4">Month</th>
                        <th scope="col" class="px-4 py-4">Created At</th>
                        <th scope="col" class="px-4 py-4">Updated At</th>
                        <th scope="col" class="px-4 py-4 text-right">
                            <span class="sr-only">Actions</span>
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($meters as $index => $meter)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3">{{ $meters->firstItem() + $index }}</td>
                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $meter->id }}
                        </th>
                        <td class="px-4 py-3">
                            {{ $meter->tenantRoom ? $meter->tenantRoom->room->room_number : 'N/A' }}
                        </td>
                        <td class="px-4 py-3">{{ $meter->previous_kwh }}</td>
                        <td class="px-4 py-3">{{ $meter->kwh_number }}</td>
                        <td class="px-4 py-3">{{ $meter->total_kwh }}</td>
                        <td class="px-4 py-3">{{ $meter->total_price }}</td>
                        <td class="px-4 py-3">{{ $meter->price_per_kwh }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($meter->meter_month)->format('m-Y') }}</td>
                        <td class="px-4 py-3">{{ $meter->created_at }}</td>
                        <td class="px-4 py-3">{{ $meter->updated_at }}</td>
                        <td class="px-4 py-3 flex items-center justify-end">
                            <button id="action-dropdown-meter{{ $index }}" 
                                data-dropdown-toggle="dropdown-meter{{ $index }}" 
                                class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                                type="button">
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </button>
  
                                <div id="dropdown-meter{{ $index }}" 
                                    class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                  <ul class="py-1 text-sm" aria-labelledby="action-dropdown-meter{{ $index }}">
                                      <li>
                                          <button 
                                              type="button"  
                                              data-modal-target="updatemeter-{{ $meter->id }}"
                                              data-modal-toggle="updatemeter-{{ $meter->id }}"
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
                                                    data-modal-target="deleteModalMeter-{{ $meter->id }}" 
                                                    data-modal-toggle="deleteModalMeter-{{ $meter->id }}" 
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
            
                    <!-- Pagination -->
                    <div class="flex justify-between items-center p-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $meters->firstItem() }}-{{ $meters->lastItem() }}</span>
                            of <span class="font-semibold text-gray-900 dark:text-white">{{ $meters->total() }}</span>
                        </span>
                        <div class="flex items-center space-x-2">
                            <!-- Previous Page Link -->
                            <a href="{{ $meters->previousPageUrl() }}" 
                                class="px-3 py-2 text-sm font-medium {{ $meters->onFirstPage() ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'text-gray-500 bg-gray-100 hover:bg-gray-200' }} rounded dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                Previous
                            </a>
                            <!-- Page Numbers -->
                            @foreach ($meters->getUrlRange(1, $meters->lastPage()) as $page => $url)
                            <a href="{{ $url }}" 
                                class="px-3 py-2 text-sm font-medium {{ $page == $meters->currentPage() ? 'text-white bg-primary-600' : 'text-gray-500 bg-gray-100 hover:bg-gray-200' }} rounded dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ $page }}
                            </a>
                            @endforeach
                            <!-- Next Page Link -->
                            <a href="{{ $meters->nextPageUrl() }}" 
                                class="px-3 py-2 text-sm font-medium {{ !$meters->hasMorePages() ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'text-gray-500 bg-gray-100 hover:bg-gray-200' }} rounded dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                Next
                            </a>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    </div>
  
    
          </div>
      </div>
    
     
  {{-- include for create,edit adn delete models here, DO NOT PUT IN app.blade!z --}}
      @include('admin2.meter.components.meter-add')
      @include('admin2.meter.components.meter-edit')
      @include('admin2.meter.components.meter-delete')
      @include('admin2.meter.components.meter-bulk-add')
  
  {{---------------- trouble shooting untuk error add/edit----------------- --}}
    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif
    
  {{-- --------clearbuton filter------------------------------ --}}
      <script>
          document.addEventListener('DOMContentLoaded', () => {
              const clearFilterBtn = document.getElementById('clearFilterBtn');
              const filterForm = document.getElementById('filter-form');
      
              clearFilterBtn.addEventListener('click', () => {
                  // Get all input fields in the form
                  const inputs = filterForm.querySelectorAll('input');
      
                  inputs.forEach(input => {
                      if (input.type === 'checkbox' || input.type === 'radio') {
                          input.checked = false; // Uncheck checkboxes and radio buttons
                      } else {
                          input.value = ''; // Clear text, number, and date inputs
                      }
                  });
      
                  // Optionally, reset dropdowns if any
                  const selects = filterForm.querySelectorAll('select');
                  selects.forEach(select => {
                      select.selectedIndex = 0; // Reset to the first option
                  });
              });
          });
      </script>
    
    
    </x-app-layout>
        