<x-app-layout>
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary">
                <h2 class="text-center mb-0">Send Message to Tenants via WhatsApp</h2>
            </div>
            <div class="card-body">
                <form id="broadcastForm" method="POST" action="{{ route('broadcast.send') }}">
                    @csrf

                    <!-- Tenant Selection with Checkbox List -->
                    <div class="form-group mb-4">
                        <div class="overflow-x-auto relative">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <input id="selectAll" type="checkbox" class="w-4 h-4 text-primary-500 bg-gray-100 border-gray-300 rounded dark:focus:ring-primary-500 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"> All Tenant
                                        </th>
                                        <th scope="col" class="py-3 px-6">Tenant Name</th>
                                        <th scope="col" class="py-3 px-6">Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenants as $tenant)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="p-4">
                                            <input type="checkbox" name="tenant_ids[]" value="{{ $tenant->id }}" class="tenant-checkbox w-4 h-4 text-primary-500 bg-gray-100 border-gray-300 rounded dark:focus:ring-primary-500 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                        </td>
                                        <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
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
                    <div class="sm:col-span-2 mb-4">
                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Message Content
                        </label>
                        <div class="relative">
                            <textarea id="message" name="message" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type your message here..." required></textarea>
                            <button type="button" id="emojiPicker" class="absolute top-2 right-2 px-3 py-1 text-gray-500 bg-gray-200 rounded-full hover:bg-gray-300 focus:outline-none">
                                😊
                            </button>
                        </div>
                    </div>

                    <!-- Send Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-paper-plane"></i> Send Message
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
