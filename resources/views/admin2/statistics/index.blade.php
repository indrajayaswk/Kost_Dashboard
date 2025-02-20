<x-app-layout>
    <div class="container mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <!-- Title Section -->
        <div class="text-center mb-12">
            <h1 class="text-xl font-bold text-gray-800">Statistics Dashboard</h1>
            <p class="text-base text-gray-600 mt-3">Visualize and analyze your data with interactive charts.</p>
        </div>

        <!-- Filter Section -->
        <div class="flex justify-center mb-6">
            <form method="GET" action="{{route('statistics.index')}}" class="flex items-center gap-4">
                <label for="filter_month" class="text-gray-700 font-semibold">Select Month & Year:</label>
                <input type="month" id="filter_month" name="filter_month" 
                    value="{{ request('filter_month', now()->format('Y-m')) }}"
                    class="border border-gray-300 rounded-lg p-2 text-gray-700 shadow-sm">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </form>
            
        </div>
        <div class="flex justify-end mb-6">
            <a href="{{ route('statistics.pdf', ['filter_month' => request('filter_month', now()->format('Y-m'))]) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Download PDF Report
            </a>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <!-- Card 1: Sum of Unpaid Total Prices -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Unpaid Eletricity</h2>
                        <p class="text-2xl font-bold text-red-600">
                            Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Sum of unpaid total prices</p>
            </div>

            <!-- Card 2: Sum of Room Prices -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Unpaid Room Prices</h2>
                        <p class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($totalRoomPrices, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Sum of all room prices</p>
            </div>

            <!-- Card 3: Percentage of Unpaid and Paid -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Payment Status</h2>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $paidPercentage }}% Paid
                        </p>
                        <p class="text-sm text-gray-500 mt-1">{{ $unpaidPercentage }}% Unpaid</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total kWh and Cost for Current Month -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Current Month Usage</h2>
                        <p class="text-2xl font-bold text-purple-600">
                            {{ number_format($totalKwhCurrentMonth, 0, ',', '.') }} kWh
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Rp {{ number_format($totalCostCurrentMonth, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Total kWh and cost for current month</p>
            </div>
        </div>

        <!-- Chart Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Chart Card 1: Meter Payments (Paid vs Unpaid) -->
            <div class="bg-white rounded-xl shadow-xl p-6 hover:shadow-2xl transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $chart1->options['chart_title'] }}</h2>
                <div class="w-full h-96">
                    {!! $chart1->renderHtml() !!}
                </div>
            </div>

            <!-- Chart Card 2: Tenant Counts -->
            <div class="bg-white rounded-xl shadow-xl p-6 hover:shadow-2xl transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $chart2->options['chart_title'] }}</h2>
                <div class="w-full h-96">
                    {!! $chart2->renderHtml() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js Library and Render Chart JS -->
    {!! $chart1->renderChartJsLibrary() !!}
    {!! $chart1->renderJs() !!}
    {!! $chart2->renderJs() !!}
</x-app-layout>