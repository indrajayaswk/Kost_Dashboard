<x-app-layout>
  <div class="container">
    <h2>Send Message to Tenants via WhatsApp</h2>

    <form id="broadcastForm" method="POST" action="{{ route('broadcast.send') }}">
        @csrf
        <!-- Tenant Selection with Checkboxes -->
        <div class="form-group">
            <label for="tenants">Select Tenants</label>
            <div id="tenants" class="form-check">
                @foreach($tenants as $tenant)
                    <div class="form-check">
                        <input type="checkbox" name="tenant_ids[]" value="{{ $tenant->id }}" class="form-check-input" id="tenant{{ $tenant->id }}">
                        <label class="form-check-label" for="tenant{{ $tenant->id }}">
                            {{ $tenant->nama }} ({{ $tenant->telphon }})
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Message Input -->
        <div class="form-group">
            <label for="message">Message Content</label>
            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
        </div>

        <!-- Send Button -->
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>
</x-app-layout>
