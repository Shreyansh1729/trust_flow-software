<?php
// api/payment/create_order.php
require_once __DIR__ . '/../../config/razorpay.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$amount = $input['amount'] ?? 0;

if ($amount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid amount']);
    exit;
}

// Razorpay Order API Endpoint
$url = "https://api.razorpay.com/v1/orders";

$data = [
    'amount' => $amount * 100, // Amount in paise
    'currency' => 'INR',
    'payment_capture' => 1
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, RAZORPAY_KEY_ID . ":" . RAZORPAY_KEY_SECRET);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// curl_close($ch); // Deprecated in recent PHP

if ($httpCode === 200) {
    $order = json_decode($response, true);
    echo json_encode([
        'status' => 'success',
        'order_id' => $order['id'],
        'amount' => $amount * 100,
        'currency' => 'INR',
        'key' => RAZORPAY_KEY_ID,
        'user_name' => $input['name'] ?? '',
        'user_email' => $input['email'] ?? ''
    ]);
} else {
    // Fallback for demo if API fails
    // DO NOT pass a fake order_id starting with 'order_', Razorpay will validate it.
    // Pass null/empty order_id to trigger Client-Side Order flow (Standard Checkout)
    echo json_encode([
        'status' => 'success',
        'order_id' => null, 
        'amount' => $amount * 100,
        'currency' => 'INR',
        'key' => RAZORPAY_KEY_ID,
        'user_name' => $input['name'] ?? '',
        'user_email' => $input['email'] ?? '',
        'warning' => 'Mock Mode: API Key invalid or API failed'
    ]);
}
?>
