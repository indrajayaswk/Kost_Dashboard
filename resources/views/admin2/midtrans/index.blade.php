<x-app-layout>
    <div>
        <h2>Generate Midtrans Payment Link</h2>
        <!-- Form to accept user input for payment details -->
        <form action="{{ route('midtrans.create-payment') }}" method="POST">
            @csrf
            <div>
                <label for="gross_amount">Amount:</label>
                <input type="number" name="gross_amount" id="gross_amount" required>
            </div>
            <div>
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" required>
            </div>
            <button type="submit">Generate Payment Link</button>
        </form>
    </div>

    @if (isset($snapToken))
        <h3>Payment Link:</h3>
        <a href="{{ $paymentLink }}" target="_blank">{{ $paymentLink }}</a>

        <h3>Debug Information:</h3>
        <p><strong>Snap Token:</strong> {{ $snapToken }}</p>
        <p><strong>Transaction Details:</strong> {{ json_encode($params['transaction_details']) }}</p>
        <p><strong>Customer Details:</strong> {{ json_encode($params['customer_details']) }}</p>
        <p><strong>Expiry:</strong> {{ json_encode($params['expiry']) }}</p>
    @endif

    @if (isset($error))
        <h3>Error:</h3>
        <p>{{ $error }}</p>
    @endif
</x-app-layout>
