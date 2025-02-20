<x-app-layout>
    <div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-blue shadow-lg rounded-lg overflow-hidden">

            <div class="bg-blue-600 text-white py-6 px-6">
                <h2 class="text-2xl font-semibold text-center">Complaints</h2>
            </div>
            <!-- Card Body -->
            <div class="p-6">
                {{-- <!-- Pagination Links (Top) -->
                <div class="mb-6 flex justify-center">
                    {{ $complaints->links() }}
                </div> --}}

                <!-- Complaints Table -->
                <div class="overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="py-4 px-6">Tenant Name</th>
                                <th scope="col" class="py-4 px-6">Phone Number</th>
                                <th scope="col" class="py-4 px-6">Room</th>
                                <th scope="col" class="py-4 px-6">Message</th>
                                <th scope="col" class="py-4 px-6">Status</th>
                                <th scope="col" class="py-4 px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($complaints as $complaint)
                                <tr class="bg-white border-b hover:bg-gray-50 transition-colors duration-200">
                                    <!-- Tenant Name -->
                                    <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $complaint->tenant->name }}
                                    </td>
                                    <!-- Phone Number -->
                                    <td class="py-4 px-6">
                                        {{ $complaint->tenant->phone }}
                                    </td>
                                    <!-- Room Number -->
                                    <td class="py-4 px-6">
                                        {{ $complaint->room->room_number ?? 'N/A' }}
                                    </td>
                                    <!-- Message -->
                                    <td class="py-4 px-6">
                                        <p class="max-w-xs truncate">{{ $complaint->message }}</p>
                                    </td>
                                    <!-- Status -->
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 text-sm rounded-full text-white {{ 
                                            strtolower($complaint->status) === 'pending' ? 'bg-yellow-500' : (
                                            strtolower($complaint->status) === 'completed' ? 'bg-green-500' : 'bg-gray-500') }}">
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                    </td>
                                    <!-- Actions -->
                                    <td class="py-4 px-6">
                                        <form action="{{ route('complaints.complete', $complaint->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-white bg-green-500 hover:bg-green-600 rounded-lg px-4 py-2 text-sm transition-colors duration-200" onclick="return confirm('Are you sure you want to mark this complaint as completed?')">
                                                Complete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links (Bottom) -->
                <div class="mt-6 flex justify-center">
                    {{ $complaints->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>