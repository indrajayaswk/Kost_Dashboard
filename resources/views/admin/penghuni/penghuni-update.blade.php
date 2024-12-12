<div id="updatepenghuni" tabindex="-1" aria-hidden="true" 
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal Content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Edit Tenant admin1
                </h3>
                <button type="button" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" 
                    data-modal-hide="updatepenghuni">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal Form -->
            <form id="editTenantForm" action="{{ route('penghuni.update', ''    ) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    <input type="hidden" id="tenantId" name="id">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" id="nama" name="nama" 
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label for="telphon" class="block text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                        <input type="text" id="telphon" name="telphon" 
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="ktp" class="block text-sm font-medium text-gray-900 dark:text-white">KTP Photo</label>
                        <input type="file" id="ktp" name="ktp" 
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <!-- Preview Image -->
                        <img id="foto_ktp_preview" class="mt-3 max-w-full max-h-32 rounded shadow-sm" alt="KTP Preview" />
                    </div>
                    
                    <div>
                        <label for="dp" class="block text-sm font-medium text-gray-900 dark:text-white">Deposit</label>
                        <input type="number" id="dp" name="dp" 
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="tanggal_masuk" class="block text-sm font-medium text-gray-900 dark:text-white">Check-in Date</label>
                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" 
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="tanggal_keluar" class="block text-sm font-medium text-gray-900 dark:text-white">Check-out Date</label>
                        <input type="date" id="tanggal_keluar" name="tanggal_keluar" 
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-900 dark:text-white">Note</label>
                        <textarea id="note" name="note" rows="3"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" 
                        class="text-white bg-blue-500 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    //// HATI HATI DENGAN TYPOS! SEPASI BISA NGERUSAK INI!!!!!!
    // document.addEventListener('DOMContentLoaded', () => {
    // const editButtons = document.querySelectorAll('.open-edit-modal');
    // const modal = document.getElementById('updatepenghuni');
    // const form = document.getElementById('editTenantForm');
    // const fotoInput = form.querySelector('#ktp');
    // const fotoPreview = document.getElementById('foto_ktp_preview');

    // editButtons.forEach(button => {
    //     button.addEventListener('click', () => {
    //         const id = button.getAttribute('data-id');
    //         const nama = button.getAttribute('data-nama');
    //         const telphon = button.getAttribute('data-telphon');
    //         const fotoKtp = button.getAttribute('data-foto-ktp');
    //         const dp = button.getAttribute('data-dp');
    //         const tanggalMasuk = button.getAttribute('data-tanggal-masuk');
    //         const tanggalKeluar = button.getAttribute('data-tanggal-keluar');
    //         const note = button.getAttribute('data-note');

    //         form.action = `/penghuni/${id}`;
    //         form.querySelector('#tenantId').value = id;
    //         form.querySelector('#nama').value = nama;
    //         form.querySelector('#telphon').value = telphon;
    //         form.querySelector('#dp').value = dp;
    //         form.querySelector('#tanggal_masuk').value = tanggalMasuk;
    //         form.querySelector('#tanggal_keluar').value = tanggalKeluar;
    //         form.querySelector('#note').value = note;

    //         if (fotoKtp) {
    //             fotoPreview.src = `/public/ktp_images/${fotoKtp}`; // Use Laravel's /storage/ path
    //             fotoPreview.style.display = 'block';
    //         } else {
    //             fotoPreview.style.display = 'none';
    //         }


    //         modal.classList.remove('hidden');
    //     });
    // });
    ///---------------------versi2, code ini engga trigger open-edit-modal lalu show admin1 tenant form editnya---------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.open-edit-modal');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-target'); // Dynamically get target modal
            const modal = document.getElementById(modalId); // Target the correct modal
            const form = modal.querySelector('form');
            const fotoInput = modal.querySelector('[id^="ktp-"]');
            const fotoPreview = modal.querySelector('[id^="foto_ktp_preview"]');

            // Get data attributes
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const telphon = button.getAttribute('data-telphon');
            const fotoKtp = button.getAttribute('data-foto-ktp');
            const dp = button.getAttribute('data-dp');
            const tanggalMasuk = button.getAttribute('data-tanggal-masuk');
            const tanggalKeluar = button.getAttribute('data-tanggal-keluar');
            const note = button.getAttribute('data-note');

            // Populate form fields
            form.action = `/penghuni/${id}`;
            form.querySelector('#tenantId').value = id;
            form.querySelector('[id^="nama"]').value = nama;
            form.querySelector('[id^="telphon"]').value = telphon;
            form.querySelector('[id^="dp"]').value = dp;
            form.querySelector('[id^="tanggal_masuk"]').value = tanggalMasuk;
            form.querySelector('[id^="tanggal_keluar"]').value = tanggalKeluar;
            form.querySelector('[id^="note"]').value = note;

            if (fotoKtp) {
                fotoPreview.src = `/storage/ktp_images/${fotoKtp}`;
                fotoPreview.style.display = 'block';
            } else {
                fotoPreview.style.display = 'none';
            }

            // Show the modal
            modal.classList.remove('hidden');
        });
    });
});


    // Add an event listener to update the preview when a file is selected
    fotoInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                fotoPreview.src = e.target.result;
                fotoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            fotoPreview.style.display = 'none';
        }
    });
});


</script>   





  