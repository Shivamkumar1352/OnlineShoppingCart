<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Processing</title>
</head>
<body>
    <h1>Processing...</h1>

    <!-- Include Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        // Initialize the Razorpay payment options
        var options = {
            "key": "{{ env('RAZORPAY_KEY') }}",  // Your Razorpay Key
            "amount": {{ $amount }},  // Amount in paise
            "currency": "INR",  // Currency Type
            "name": "MyStore",  // Your Company Name
            "description": "Test Transaction",  // Description of the Transaction
            "order_id": "{{ $orderId }}",  // Order ID generated from your server
            "prefill": {
                "name": "John Doe",  // Prefilled User Data
                "email": "johndoe@example.com",
                "contact": "9000090000"
            },
            "theme": {
                "color": "#3399cc"  // Color for Razorpay Modal
            },
            // Handler to process the payment response
            "handler": function(response) {
                // Send payment data to your backend for verification
                fetch("{{ route('razorpay.callback') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"  // CSRF Token for security
                    },
                    body: JSON.stringify({
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // If the payment is successful
                    if (data.success) {
                        window.location.href = "{{ route('products') }}";  // Redirect to products page
                    } else {
                        window.location.href = "{{ route('products') }}?payment_status=failed";  // Redirect with failure
                    }
                })
                .catch(error => {
                    // If there's an error in communication
                    window.location.href = "{{ route('products') }}?payment_status=failed";
                });
            },
            // Modal dismiss behavior
            "modal": {
                "ondismiss": function() {
                    window.location.href = "{{ route('products') }}?payment_status=failed";  // Redirect if user dismisses the modal
                }
            }
        };

        // Open the Razorpay payment modal
        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>
</body>
</html>
