<x-app-layout>
  <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden ">
      <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4 ">
          <div class="w-full md:w-1/2">
              <form action="" method="GET" class="flex items-center">
                  <label for="simple-search" class="sr-only">Search</label>
                  <div class="relative w-full">
                      <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                          <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                          </svg>
                      </div>
                      <input type="text" name="search" id="simple-search" value="{{ request()->get('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search" >
                  </div>
              </form>
          </div>

          <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
              <button type="button" id="*" data-modal-target="addmeter" data-modal-toggle="addmeter" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                  <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                  </svg>
                  Add Meter
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
                        <th scope="col" class="px-4 py-4">METERS</th>
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
                          <td class="px-4 py-3">
                              {{ $meters->firstItem() + $index }}
                          </td>
                          <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                              {{ $meter->id }}
                          </th>
                          <td class="px-4 py-3">{{ $meter->meter_number }}</td>
                          <td class="px-4 py-3">{{ $meter->month }}</td>
                          <td class="px-4 py-3">{{$meter->created_at}}</td>
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
              
                  <!-- Meter Reading -->
                <div class="mb-4">
                    <label for="meters-{{ $meter_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Meter Reading
                    </label>
                    <input type="number" id="meters-{{ $meter_update->id }}" name="meter_number" value="{{ $meter_update->meter_number }}" 
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
            
                <!-- Month -->
                <div class="mb-4">
                    <label for="month-{{ $meter_update->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Month
                    </label>
                    <input type="date" id="month-{{ $meter_update->id }}" name="month" value="{{$meter_update->month}}"
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
  {{-- model custom filter---------------------------------------------------------------------------------- --}}
        </div>
    </div>
    {{-- <div id="filterDropdown" class=" z-[90] hidden w-96 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Filters</h6>
  
        <form id="filter-form" action="{{ route('kamar.index') }}" method="GET">
            <!-- Nama -->
            <div class="space-y-2">
                <label for="nama" class="text-sm font-medium text-gray-900 dark:text-gray-100">Nama:</label>
                <input id="nama" name="nama" type="text" class="w-full p-2 bg-gray-100 border border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-primary-600" value="{{ request('nama') }}" />
            </div>
  
            <!-- Telphon -->
            <div class="space-y-2">
                <label for="telphon" class="text-sm font-medium text-gray-900 dark:text-gray-100">Telphon:</label>
                <input id="telphon" name="telphon" type="text" class="w-full p-2 bg-gray-100 border border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-primary-600" value="{{ request('telphon') }}" />
            </div>
  
            <!-- DP -->
            <div class="space-y-2">
                <label for="dp" class="text-sm font-medium text-gray-900 dark:text-gray-100">DP:</label>
                <input id="dp" name="dp" type="number" class="w-full p-2 bg-gray-100 border border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-primary-600" value="{{ request('dp') }}" />
            </div>
  
            <!-- Note -->
            <div class="space-y-2">
                <label for="note" class="text-sm font-medium text-gray-900 dark:text-gray-100">Note:</label>
                <input id="note" name="note" type="text" class="w-full p-2 bg-gray-100 border border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-primary-600" value="{{ request('note') }}" />
            </div>
  
            <!-- Checkbox for no Tanggal Keluar -->
            <div class="mt-3">
                <label for="no_tanggal_keluar" class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
                    <input id="no_tanggal_keluar" name="no_tanggal_keluar" type="checkbox" value="1" 
                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                        {{ request('no_tanggal_keluar') == '1' ? 'checked' : '' }}>
                    <span class="ml-2">Show Only No Tanggal Keluar</span>
                </label>
            </div>
  
            <div class="mt-3 flex justify-between items-center">
                <button type="submit" class="py-2 px-4 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-200 dark:bg-primary-500 dark:hover:bg-primary-600 dark:focus:ring-primary-600">Apply</button>
                <button type="button" id="clearFilterBtn" class="text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Clear</button>
            </div>
        </form>
    </div> --}}
  
  {{-- model delete kamar --------------------------------------------------------------------------}}
    @foreach ($meters as $meter)
    <div id="deleteModalMeter-{{ $meter->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModalMeter-{{$meter->id }}">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <p class="mb-4 text-gray-500 dark:text-gray-300">Are you sure you want to delete this meter?</p>
                <p class="mb-4 text-gray-500 dark:text-gray-300">Meter ID: "{{$meter->id}}"</p>
                <form action="{{ route('meter.destroy', $meter->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center items-center space-x-4">
                        <button data-modal-hide="deleteModalMeter-{{$meter->id }}" type="button" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            No, cancel
                        </button>
                        <button type="submit" class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                            Yes, I'm sure
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
  
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
  
  </main>
  </x-app-layout>
      