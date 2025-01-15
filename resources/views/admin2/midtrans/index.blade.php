<x-app-layout>
    <div>
        <h2>Generate Midtrans Payment Link</h2>
        
        <form action="{{ route('midtrans.show-meter') }}" method="GET">
            @csrf
            <div>
                <label for="tenant_room_id">Room:</label>
                <select name="tenant_room_id" id="tenant_room_id" required>
                    @foreach ($tenantRooms as $tenantRoom)
                        <option value="{{ $tenantRoom->id }}">
                            {{ $tenantRoom->room->room_number }} - {{ $tenantRoom->primaryTenant->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Select Room</button>
        </form>
    </div>
</x-app-layout>
