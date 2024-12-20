<x-app-layout>
    <div>
        <h1>Assigned Tenants to Rooms</h1>

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-4">No</th>
                    <th scope="col" class="px-4 py-4">Tenant Name</th>
                    <th scope="col" class="px-4 py-3">Phone</th>
                    <th scope="col" class="px-4 py-3">Room Number</th>
                    <th scope="col" class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenantRooms as $index => $tenantRoom)
                    <tr>
                        <td class="px-4 py-4">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">{{ $tenantRoom->tenant->name }}</td>
                        <td class="px-4 py-4">{{ $tenantRoom->tenant->phone }}</td>
                        <td class="px-4 py-4">{{ $tenantRoom->room->room_number }}</td>
                        <td class="px-4 py-4">{{ ucfirst($tenantRoom->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('tenant-room.create') }}" class="mt-4 text-blue-500">Assign Tenant to Room</a>
    </div>
</x-app-layout>
