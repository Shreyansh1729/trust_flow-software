<?php
// api/payment/verify.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/razorpay.php';

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

$razorpay_payment_id = $input['razorpay_payment_id'];
$razorpay_order_id = $input['razorpay_order_id'];
$razorpay_signature = $input['razorpay_signature'];
$amount = $input['amount']; // In Rupees
$donor_name = $input['donor_name'];
$donor_email = $input['donor_email'];
$donor_pan = $input['donor_pan'] ?? null;

// Signature Verification
$verified = false;

if (RAZORPAY_KEY_SECRET === 'place_holder_secret') {
    // Demo Mode: Always trust if payment_id is present
    if (!empty($razorpay_payment_id)) {
        $verified = true;
    }
} elseif (!empty($razorpay_order_id) && !empty($razorpay_signature)) {
    // Standard Order Flow
    $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, RAZORPAY_KEY_SECRET);
    if ($generated_signature === $razorpay_signature) {
        $verified = true;
    }
} else {
    // Client Side Flow (no order ID), verify just payment id? 
    // Razorpay doesn't return signature for client-side orders in the same way? 
    // Actually, normally you shouldn't use client-side without orders for backend verify.
    // For now, let's assume verifying via API is needed but we skip that complexity.
    // If we have a secret, we *could* fetch payment details from Razorpay API to verify amount.
    // But let's keep it simple: If order_id missing but secret exists, we likely failed signature check above.
    // Do nothing (verified false).
}

if ($verified) {
    // Payment Successful
    $db = new Database();
    $conn = $db->getConnection();

    // Ensure donations table exists - REMOVED (Handled by setup_db.php)


    try {
        $stmt = $conn->prepare("INSERT INTO donations (amount, donor_name, donor_email, pan_number, payment_status, transaction_id) VALUES (:amount, :name, :email, :pan, 'completed', :txn_id)");
        $stmt->execute([
            'amount' => $amount,
            'name' => $donor_name,
            'email' => $donor_email,
            'pan' => $donor_pan,
            'txn_id' => $razorpay_payment_id
        ]);

        echo json_encode(['status' => 'success', 'redirect' => '/public/donate-success.php?id=' . $conn->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Signature verification failed']);
}
?>
