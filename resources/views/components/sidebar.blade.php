{{-- TIDAK DIPAKAI


<div x-data="{ open: false }" class="flex h-screen">
    <!-- Sidebar -->
    <div :class="open ? 'block' : 'hidden'"
         class="sticky top-0 w-64 min-h-screen shadow-lg relative overflow-y-auto bg-primary-50 dark:bg-primary-900"
         x-cloak>
        
        <!-- Close Button (Top Right inside sidebar when open) -->
        <button @click="open = !open" 
                class="absolute top-4 right-4 p-2 rounded-full focus:outline-none bg-primary-200 hover:bg-primary-300 dark:bg-primary-800 dark:hover:bg-primary-700 text-primary-900 dark:text-white">
            <!-- X Icon when sidebar is open -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 8.586L3.707 2.293a1 1 0 00-1.414 1.414L8.586 10l-6.293 6.293a1 1 0 001.414 1.414L10 11.414l6.293 6.293a1 1 0 001.414-1.414L11.414 10l6.293-6.293a1 1 0 00-1.414-1.414L10 8.586z" clip-rule="evenodd" />
            </svg>
        </button>

        <div class="p-4 mt-8">
            <h2 class="text-lg font-semibold text-primary-800 dark:text-primary-100">Dashboard Kost Cobra</h2>
        </div>
        <ul class="mt-4 space-y-1">
            <li>
                <a href="{{ route('dashboard') }}" class="block py-2 px-4 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-800 dark:hover:bg-primary-700 text-primary-900 dark:text-primary-100">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('statistik.index') }}" class="block py-2 px-4 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-800 dark:hover:bg-primary-700 text-primary-900 dark:text-primary-100">
                    Statistik
                </a>
            </li>
            <li>
                <a href="{{ route('penghuni.index') }}" class="block py-2 px-4 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-800 dark:hover:bg-primary-700 text-primary-900 dark:text-primary-100">
                    Penghuni
                </a>
            </li>
            <li>
                <a href="{{ route('kamar.index') }}" class="block py-2 px-4 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-800 dark:hover:bg-primary-700 text-primary-900 dark:text-primary-100">
                    Kamar
                </a>
            </li>
            <li>
                <a href="{{ route('meteran.index') }}" class="block py-2 px-4 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-800 dark:hover:bg-primary-700 text-primary-900 dark:text-primary-100">
                    Meteran
                </a>
            </li>
            <li>
                <a href="{{ route('komplain.index') }}" class="block py-2 px-4 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-800 dark:hover:bg-primary-700 text-primary-900 dark:text-primary-100">
                    Komplain
                </a>
            </li>
            <li>
                <a href="{{ route('broadcast.index') }}" class="block py-2 px-4 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-800 dark:hover:bg-primary-700 text-primary-900 dark:text-primary-100">
                    Broadcast
                </a>
            </li>
        </ul>
    </div>

    <!-- Hamburger Button (Top Left when sidebar is closed) -->
    <button x-show="!open" @click="open = !open" 
            class="absolute top-4 left-4 p-2 rounded-full focus:outline-none z-10 bg-primary-300 hover:bg-primary-400 text-white">
        <!-- Hamburger Icon when sidebar is closed -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 5h14a1 1 0 110 2H3a1 1 0 110-2zm0 4h14a1 1 0 110 2H3a1 1 0 110-2zm0 4h14a1 1 0 110 2H3a1 1 0 110-2z" clip-rule="evenodd" />
        </svg>
    </button>
</div> --}}
