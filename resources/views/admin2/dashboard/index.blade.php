<x-app-layout>
  <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4 p-4 grid-flow-row">
      <!-- Active Tenants -->
      <a href="{{ route('tenant.index') }}">
        <x-dashboard-card title="Active Tenants" subtitle="{{ $activeTenantCount }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.25-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z" />
          </svg>
      </x-dashboard-card>      
      </a>

      <!-- Available Room -->
      <a href="{{route('room.index')}}">
        <x-dashboard-card title="Available Room" subtitle="{{$availableRoomCount}}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-6m0 6V3m0 18a9 9 0 1 0 0-18 9 9 0 0 0 0 18z" />
            </svg>
        </x-dashboard-card>
      </a>

      <!-- Current Meter's Month -->
      <a href="{{route('meter.index')}}">
        <x-dashboard-card title="Current Meter's Month" subtitle="{{$latestMonthYear}}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c.41 0 .75-.34.75-.75v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .41.34.75.75.75Zm0 8c.41 0 .75-.34.75-.75v-5.5a.75.75 0 0 0-1.5 0v5.5c0 .41.34.75.75.75Zm0 4.5a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
            </svg>
        </x-dashboard-card>
      </a>

      <!-- Complaints -->
      <a href="{{route('complaint.index')}}">
        <x-dashboard-card title="Complaints" subtitle="{{$pendingComplaintCount}} pending">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01m-6.93 6.243a9 9 0 1 1 13.84 0M12 5.25a3 3 0 0 1 0-6 3 3 0 0 1 0 6Z" />
            </svg>
        </x-dashboard-card>
      </a>

      <!-- Assign Tenant Room -->
      <a href="{{route('tenant-room.index')}}">
        <x-dashboard-card title="Assign Tenant Room" subtitle="10 Pending">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9.75h1.5a1.5 1.5 0 1 0 0-3h-1.5a1.5 1.5 0 0 0 0 3Zm-9 0h1.5a1.5 1.5 0 1 0 0-3H6.75a1.5 1.5 0 0 0 0 3Zm1.5 9h7.5a1.5 1.5 0 0 0 0-3h-7.5a1.5 1.5 0 0 0 0 3Z" />
            </svg>
        </x-dashboard-card>
      </a>

      <!-- Pending Payment -->
      <a href="{{route('midtrans.index')}}">
        <x-dashboard-card title="Pending Payment" subtitle="3 Tenants">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6c.41 0 .75-.34.75-.75v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .41.34.75.75.75Zm0 10c.41 0 .75-.34.75-.75v-5.5a.75.75 0 0 0-1.5 0v5.5c0 .41.34.75.75.75Zm0 4.5a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
            </svg>
        </x-dashboard-card>
      </a>

      <!-- Broadcasts -->
      <a href="{{route('broadcast.index')}}">
        <x-dashboard-card title="Broadcasts" subtitle="2 Sent Today">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 0-6 0 3 3 0 0 0 6 0Zm7.125-1.125a9 9 0 1 0-12.15 12.15 9 9 0 0 0 12.15-12.15ZM15.75 9v6m-7.5-6v6" />
            </svg>
        </x-dashboard-card>
      </a>

      <!-- Midtrans -->
      <a href="#">
        <x-dashboard-card title="Midtrans" subtitle="Connected">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 6H6m12 6H9m6 6H6" />
            </svg>
        </x-dashboard-card>
      </a>
  </div>
  <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4 p-4 grid-flow-row">
<h1>table</h1>
  </div>
</div>

</x-app-layout>
