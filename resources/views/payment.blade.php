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
        var options = {
            "key": "{{ env('RAZORPAY_KEY') }}",
            "amount": {{ $amount }},
            "currency": "INR",
            "name": "Your Store",
            "order_id": "{{ $orderId }}",
            "handler": function(response) {
                fetch("{{ route('razorpay.callback') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Payment response:', data);
                    if (data.success) {
                        // Show success message
                        alert(data.message);
                        // Redirect to order history
                        window.location.href = data.redirect;
                    } else {
                        throw new Error(data.message || 'Payment failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred: ' + error.message);
                    window.location.href = "{{ route('cart') }}";
                });
            },
            "theme": {
                "color": "#3399cc"
            },
            "modal": {
                "ondismiss": function() {
                    window.location.href = "{{ route('cart') }}";
                }
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>
</body>
</html>
