<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Good morning, James!</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
          <!-- Card 1 -->
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center">
              <i data-lucide="credit-card" class="w-6 h-6 text-blue-600"></i>
              <button class="text-gray-400">
                <i data-lucide="more-vertical"></i>
              </button>
            </div>
            <h2 class="text-2xl font-bold mt-4">$143,624</h2>
            <p class="text-gray-500">Your bank balance</p>
          </div>
      
          <!-- Card 2 -->
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center">
              <i data-lucide="clock" class="w-6 h-6 text-blue-600"></i>
              <button class="text-gray-400">
                <i data-lucide="more-vertical"></i>
              </button>
            </div>
            <h2 class="text-2xl font-bold mt-4">7</h2>
            <p class="text-gray-500">Employees working today</p>
          </div>
      
          <!-- Card 3 -->
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center">
              <i data-lucide="dollar-sign" class="w-6 h-6 text-blue-600"></i>
              <button class="text-gray-400">
                <i data-lucide="more-vertical"></i>
              </button>
            </div>
            <h2 class="text-2xl font-bold mt-4">$3,287.49</h2>
            <p class="text-gray-500">This week's card spending</p>
          </div>
      
          <!-- Card 4 -->
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center">
              <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
              <button class="text-gray-400">
                <i data-lucide="more-vertical"></i>
              </button>
            </div>
            <h2 class="text-2xl font-bold mt-4">54</h2>
            <p class="text-green-500 text-sm font-medium">+18.7%</p>
            <p class="text-gray-500">New clients</p>
          </div>
        </div>
      
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
          <!-- Card 5 -->
          <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4">Revenue</h2>
            <div class="w-full h-40 bg-gray-100 rounded-lg"></div>
            <p class="text-gray-500 mt-4 text-sm">Last 7 days VS prior week</p>
          </div>
      
          <!-- Card 6 -->
          <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4">Invoices overdue</h2>
            <div class="flex justify-between items-center">
              <h2 class="text-4xl font-bold text-red-600">6</h2>
              <p class="text-red-500 text-sm font-medium">+2.7%</p>
            </div>
          </div>
        </div>
      </div>
      
      <script>
        lucide.createIcons();
      </script>
      
</x-app-layout>
