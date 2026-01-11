<?php
// public/receipt-print.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = $_GET['id'] ?? 0;

if (!$id) {
    die("Invalid Receipt ID");
}

$db = new Database();
$conn = $db->getConnection();

// Fetch Donation
$stmt = $conn->prepare("SELECT * FROM donations WHERE id = :id AND payment_status = 'completed'");
$stmt->execute(['id' => $id]);
$donation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$donation) {
    die("Receipt not found or payment incomplete.");
}

// Simple HTML Receipt
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Receipt #<?php echo str_pad($donation['id'], 6, '0', STR_PAD_LEFT); ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; }
        .receipt-container { max-width: 800px; margin: 40px auto; padding: 40px; border: 1px solid #ddd; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f2cfac; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e3a8a; }
        .title { text-align: right; }
        .title h1 { margin: 0; font-size: 28px; color: #1e3a8a; }
        .title p { margin: 5px 0 0; color: #666; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px; }
        .label { font-size: 12px; text-transform: uppercase; color: #666; letter-spacing: 1px; margin-bottom: 5px; }
        .value { font-size: 16px; font-weight: 600; }
        .amount-box { background: #fdf2f8; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 40px; }
        .amount-val { font-size: 36px; font-weight: bold; color: #be185d; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px; }
        .btn-print { background: #1e3a8a; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        @media print { .btn-print { display: none; } .receipt-container { border: none; box-shadow: none; margin: 0; width: 100%; } }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;" class="no-print">
        <button onclick="window.print()" class="btn-print">Print Receipt</button>
        <a href="/" class="btn-print" style="margin-left: 10px; background: #666;">Home</a>
    </div>

    <div class="receipt-container">
        <div class="header">
            <div class="logo">TrustFlow Foundation</div>
            <div class="title">
                <h1>OFFICIAL RECEIPT</h1>
                <p>Receipt #: <?php echo str_pad($donation['id'], 6, '0', STR_PAD_LEFT); ?></p>
                <p>Date: <?php echo date('F d, Y', strtotime($donation['created_at'])); ?></p>
            </div>
        </div>

        <div class="details-grid">
            <div>
                <div class="label">Donor Name</div>
                <div class="value"><?php echo htmlspecialchars($donation['donor_name']); ?></div>
            </div>
            <div>
                <div class="label">Email Address</div>
                <div class="value"><?php echo htmlspecialchars($donation['donor_email']); ?></div>
            </div>
            <div>
                <div class="label">PAN Number</div>
                <div class="value"><?php echo $donation['pan_number'] ? htmlspecialchars($donation['pan_number']) : 'N/A'; ?></div>
            </div>
            <div>
                <div class="label">Payment ID</div>
                <div class="value"><?php echo htmlspecialchars($donation['transaction_id']); ?></div>
            </div>
        </div>

        <div class="amount-box">
            <div class="label">Amount Donated</div>
            <div class="amount-val">₹<?php echo number_format($donation['amount'], 2); ?></div>
            <div style="margin-top: 10px; color: #666; font-style: italic;">
                (Tax Deductible under Section 80G)
            </div>
        </div>

        <div class="footer">
            <p>TrustFlow Foundation | 123 Charity Lane, Mumbai, MH 400001 | +91 98765 43210</p>
            <p>Registration No: TF-12345-6789 | 80G Certificate: AAATF1234F</p>
            <p>This is a computer-generated receipt and does not require a physical signature.</p>
        </div>
    </div>
</body>
</html>
