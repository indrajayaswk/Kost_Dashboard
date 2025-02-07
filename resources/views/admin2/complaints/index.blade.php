<x-app-layout>
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-center">
                <h2 class="mb-0 text-white">Complaints Index</h2>
            </div>
            <div class="card-body">
                <!-- Pagination links -->
                <div class="mb-4">
                    {{ $complaints->links() }}
                </div>

                <!-- Complaints Table -->
                <div class="overflow-x-auto relative">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">Tenant Name</th>
                                <th scope="col" class="py-3 px-6">Phone Number</th>
                                <th scope="col" class="py-3 px-6">Room</th>
                                <th scope="col" class="py-3 px-6">Message</th>
                                <th scope="col" class="py-3 px-6">Status</th>
                                <th scope="col" class="py-3 px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($complaints as $complaint)
                                <tr class="bg-white border-b">
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        {{ $complaint->tenant->name }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $complaint->tenant->phone }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $complaint->room->room_number ?? 'N/A' }}
                                    </td>
                                    <td class="py-4 px-6">{{ $complaint->message }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-2 py-1 rounded text-white {{ 
                                        strtolower($complaint->status) === 'pending' ? 'bg-yellow-500' : (
                                        strtolower($complaint->status) === 'completed' ? 'bg-green-500' : 'bg-gray-500') }}">
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <!-- Complete Button -->
                                        <form action="{{ route('complaints.complete', $complaint->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-white bg-green-500 hover:bg-green-700 rounded px-4 py-2 inline-block" onclick="return confirm('Are you sure you want to mark this complaint as completed?')">
                                                Complete
                                            </button>
                                        </form>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $complaints->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>