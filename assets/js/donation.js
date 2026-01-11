document.addEventListener('DOMContentLoaded', function () {
    const donateForm = document.querySelector('form'); // Assuming only one form on donate page

    if (donateForm) {
        donateForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = donateForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Get form values
            const amountInput = document.getElementById('customAmount');
            const amountRadios = document.querySelectorAll('input[name="amount"]');

            // UX Logic: Mutual Exclusivity
            amountRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (radio.checked) {
                        amountInput.value = ''; // Clear custom if preset selected
                    }
                });
            });

            amountInput.addEventListener('input', () => {
                if (amountInput.value) {
                    amountRadios.forEach(r => r.checked = false); // Deselect presets if custom typed
                }
            });
            // If custom amount is visible and filled, use it, else use selected radio or default
            // For now, let's assume we grabbed the value correctly based on the updated logic below

            let amount = 0;
            // Check for toggle content logic from previous setup or standard inputs
            // Let's grab specific inputs assuming standard names
            const amountField = document.querySelector('input[name="amount"]');
            const customAmountField = document.querySelector('input[name="custom_amount"]');

            // Simple logic: If there is a custom amount input and it has value, use it.
            // If there are radio buttons for amount, check checked.

            // Based on typical Donate Page structure:
            // We need to ensure we capture the right amount.

            // Let's look for explicit ID first
            // Capture Amount
            if (amountInput && amountInput.value) {
                amount = amountInput.value;
            } else {
                const checkedRadio = document.querySelector('input[name="amount"]:checked');
                if (checkedRadio) {
                    amount = checkedRadio.value;
                }
            }

            // If "One-Time" / "Monthly" is a tab, we might need logic, but for Payment Gateway we just charge now.

            // Fallback for demo if structure varies
            if (!amount) amount = 100; // Default fallback

            // Get Donor Details
            const name = document.getElementById('name') ? document.getElementById('name').value : 'Anonymous';
            const email = document.getElementById('email') ? document.getElementById('email').value : 'donor@example.com';
            const pan = document.getElementById('pan') ? document.getElementById('pan').value : '';

            // Validation
            if (amount < 1) {
                alert("Please enter a valid amount.");
                return;
            }

            // UI Loading State
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';

            // 1. Create Order
            fetch('/api/payment/create_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ amount: amount, name: name, email: email, pan: pan })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // 2. Open Razorpay
                        const options = {
                            "key": data.key,
                            "amount": data.amount,
                            "currency": data.currency,
                            "name": "TrustFlow Foundation",
                            "description": "Donation",
                            "image": "/assets/img/logo-small.png",
                            "handler": function (response) {
                                // 3. Verify Payment
                                verifyPayment(response, amount, name, email, pan);
                            },
                            "prefill": {
                                "name": data.user_name,
                                "email": data.user_email
                            },
                            "theme": {
                                "color": "#F97316"
                            }
                        };

                        // Only add order_id if it exists (Server Side Order)
                        if (data.order_id) {
                            options.order_id = data.order_id;
                        }

                        const rzp1 = new Razorpay(options);
                        rzp1.open();

                        // Reset button on close without payment
                        rzp1.on('payment.failed', function (response) {
                            alert("Payment Failed: " + response.error.description);
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        });
                    } else {
                        alert('Error creating order: ' + data.message);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        });
    }

    function verifyPayment(response, amount, name, email, pan) {
        fetch('/api/payment/verify.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature,
                amount: amount,
                donor_name: name,
                donor_email: email,
                donor_pan: pan
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '/public/donate-failed.php?reason=' + encodeURIComponent(data.message);
                }
            })
            .catch(err => {
                window.location.href = '/public/donate-failed.php?reason=NetworkError';
            });
    }
});
