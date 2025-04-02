<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Processing...</h1>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
{{-- <script>
    var options = {
        "key": "{{ env('RAZORPAY_KEY') }}",
        "amount": {{ $amount }},
        "currency": "INR",
        "name": "Acme Corp",
        "description": "Test Transaction",
        "handler": function(response){
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "{{ route('products') }}";
                } else {
                    window.location.href = "{{ route('cart') }}";
                }
            })
            .catch(error => console.error("Payment verification failed:", error));
        },
        "order_id": "{{ $orderId }}",
        "prefill": {
            "name": "John Doe",
            "email": "johndoe@example.com",
            "contact": "9000090000"
        },
        "theme": {
            "color": "#3399cc"
        }
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
    </script> --}}
    <script>
        var options = {
            "key": "{{ env('RAZORPAY_KEY') }}",
            "amount": {{ $amount }},
            "currency": "INR",
            "name": "Acme Corp",
            "description": "Test Transaction",
            "order_id": "{{ $orderId }}",
            "prefill": {
                "name": "John Doe",
                "email": "johndoe@example.com",
                "contact": "9000090000"
            },
            "theme": {
                "color": "#3399cc"
            },
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "{{ route('products') }}";
                    } else {
                        window.location.href = "{{ route('products') }}?payment_status=failed";
                    }
                })
                .catch(error => {
                    window.location.href = "{{ route('products') }}?payment_status=failed";
                });
            },
            "modal": {
                "ondismiss": function() {
                    window.location.href = "{{ route('products') }}?payment_status=failed";
                }
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
        </script>



</body>
</html>
