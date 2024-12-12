{{-- Modal for adding penghuni --}}
<div id="addpenghuni" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-lg">
    
    <div class="relative p-4 w-full max-w-2xl max-h-full ">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Penghuni</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="addpenghuni" data-modal-toggle="addpenghuni" id="close-modal-btn">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
                     
 
            <!-- Modal body -->
            <form action="{{ route('penghuni.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" name="nama" id="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter Penghuni name" required>
                    </div>
                    
                    <div>
                        <label for="telphon" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                        <input 
                            type="tel" 
                            name="telphon" 
                            id="telphon" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                            placeholder="Enter phone number" 
                            pattern="\d{13}" 
                            maxlength="13" 
                            minlength="13" 
                            required>
                    </div>
                    
 
                    <div>
                        <label for="tanggal_masuk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    </div>
 
                    <div>
                        <label for="tanggal_keluar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    </div>
 
                    <div>
                        <label for="dp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">DP</label>
                        <input type="text" name="dp" id="dp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter DP amount" required>
                    </div>

                    <div>
                        <label for="note" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NOTE</label>
                        <input type="text" name="note" id="note" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter Notes for tenant" >
                    </div>
 
                    <div class="sm:col-span-2">
                        <label for="ktp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload KTP</label>
                        <input type="file" name="ktp" id="ktp" accept="image/*" onchange="previewImage(event, 'preview-penghuni')"  class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 focus:outline-none" required>
                        
                        <div id="image-preview" class="mt-4">
                            <img id="preview-penghuni" class="w-full max-h-60 rounded-lg object-cover" style="display: none;">
                        </div>
                    </div>
                </div>
 
                <button type="submit" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Penghuni
                </button>
            </form>
        </div>
    </div>
 </div>
 
 <script>
    // Get references to modal and form elements
    const modal = document.getElementById('addpenghuni');
    const closeButton = document.getElementById('close-modal-btn');
    const form = modal.querySelector('form');

    // Close modal and clear form fields
    function closeModal() {
        // Close the modal by hiding it
        modal.classList.add('hidden');

        // Clear all input fields inside the form
        form.reset();

        // Clear image preview
        const preview = document.getElementById('preview');
        preview.style.display = 'none';
        preview.src = "";
    }

    // Event listener for the close button
    closeButton.addEventListener('click', closeModal);

    // Event listener for clicking outside the modal to close it
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });



    
    const telInput = document.getElementById('telphon');

// Sanitize the phone number by removing unwanted characters
function sanitizePhoneNumber(value) {
    // Remove non-numeric characters, including +, -, and spaces
    value = value.replace(/[^+\d]/g, '');

    // Convert '+62' or '0' prefix to '62'
    if (value.startsWith('+62')) {
        return '62' + value.slice(3);
    } else if (value.startsWith('0')) {
        return '62' + value.slice(1);
    }
    return value;
}

// Handle the 'input' event to sanitize the number while typing
telInput.addEventListener('input', () => {
    telInput.value = sanitizePhoneNumber(telInput.value);
});

// Handle the 'paste' event to sanitize the pasted content
telInput.addEventListener('paste', (event) => {
    // Prevent the default paste behavior
    event.preventDefault();

    // Get the pasted content from the clipboard
    const pastedData = (event.clipboardData || window.clipboardData).getData('text');

    // Sanitize the pasted content
    const sanitizedData = sanitizePhoneNumber(pastedData);

    // Set the sanitized value to the input
    telInput.value = sanitizedData;
});




    // Function to preview image
    // ini function previewImage(event, previewId), bergunanya, previewimg(event) tidak apa sama, yang pentingnya data yang dimasukin previewIdnya, contoh input diatas manggil data preview-penghuni di input fieldnya, lalu id img nya juga preview-penghuni, jadi ditulis id yang mana harus dipaste fotonya
    function previewImage(event, previewId) {
        console.log("Function previewImage triggered.");
        const input = event.target;
        const preview = document.getElementById(previewId);

        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = "none";
            preview.src = "";
        }
    }
</script>

 