<x-app-layout>
    <div class="container mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Title Section -->
            <div class="bg-blue-600 text-white py-6 px-6">
                <h2 class="text-2xl font-semibold text-center">Broadcast WhatsApp Message to Tenants</h2>
            </div>

            <!-- Form Section -->
            <div class="p-6">
                <form id="broadcastForm" method="POST" action="{{ route('broadcast.send') }}">
                    @csrf

                    <!-- Tenant Selection with Checkbox List -->
                    <div class="mb-6">
                        <div class="overflow-x-auto relative shadow-sm rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <input id="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="selectAll" class="ml-2 text-sm font-medium text-gray-700">Select All</label>
                                        </th>
                                        <th scope="col" class="py-3 px-6">Tenant Name</th>
                                        <th scope="col" class="py-3 px-6">Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenants as $tenant)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition-colors duration-200">
                                        <td class="p-4">
                                            <input type="checkbox" name="tenant_ids[]" value="{{ $tenant->id }}" class="tenant-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        </td>
                                        <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $tenant->name }}
                                        </td>
                                        <td class="py-4 px-6">{{ $tenant->phone }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Message Input with Emoji Picker -->
                    <div class="mb-6">
                        <label for="message" class="block mb-3 text-sm font-medium text-gray-700">
                            Message Content
                        </label>
                        <div class="relative">
                            <textarea id="message" name="message" rows="6" class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none" placeholder="Type your message here..." required></textarea>
                            <button type="button" id="emojiPicker" class="absolute top-4 right-4 px-3 py-1 text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                😊
                            </button>
                        </div>
                    </div>

                    <!-- Send Button -->
                    <div class="text-center">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 w-screen">
                            <i class="fas fa-paper-plane">Send Message</i> 
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Emoji Button Library -->
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.3/dist/index.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.querySelector('#emojiPicker');
            const textArea = document.querySelector('#message');
            const picker = new EmojiButton();

            // Show the picker when the button is clicked
            button.addEventListener('click', () => {
                picker.togglePicker(button);
            });

            // Add emoji to the text area when selected
            picker.on('emoji', emoji => {
                textArea.value += emoji;
            });
        });

        // JavaScript for Select All functionality
        document.getElementById('selectAll').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.tenant-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</x-app-layout>