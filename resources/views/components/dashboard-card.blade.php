<div class="flex items-center p-4 bg-white border rounded-lg shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-gray-100">
    <!-- Icon Slot -->
    <div class="p-3 bg-gray-100 rounded-lg">
        {{ $slot }}
    </div>

    <!-- Content -->
    <div class="ml-4">
        <h4 class="text-xl font-bold text-gray-700">{{ $title }}</h4>
        <p class="text-base font-semibold text-gray-500">{{ $subtitle }}</p>
    </div>
</div>

