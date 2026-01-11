<?php
// public/receipt-pdf.php
require_once __DIR__ . '/../includes/libs/fpdf.php';
require_once __DIR__ . '/../config/db.php';

// 1. Validate ID
$id = $_GET['id'] ?? null;
if (!$id) die("Invalid Donation ID");

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT * FROM donations WHERE id = ?");
$stmt->execute([$id]);
$donation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$donation) die("Donation not found");

// 2. Setup FPDF
class ReceiptPDF extends FPDF {
    function Header() {
        // Logo
        // $this->Image('logo.png',10,6,30);
        $this->SetFont('Arial','B',15);
        $this->Cell(80);
        $this->Cell(30,10,'TrustFlow Foundation',0,0,'C');
        $this->Ln(20);
        $this->Line(10, 30, 200, 30);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Thank you for your generosity. This is a computer generated receipt.',0,0,'C');
    }
}

$pdf = new ReceiptPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'OFFICIAL DONATION RECEIPT',0,1,'C');
$pdf->Ln(10);

// Receipt Details Box
$pdf->SetFillColor(245, 247, 250);
$pdf->Rect(10, 50, 190, 80, 'F');

$pdf->SetFont('Arial','B',12);
$pdf->SetXY(20, 60);
$pdf->Cell(50, 10, 'Receipt No:', 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(100, 10, str_pad($donation['id'], 6, '0', STR_PAD_LEFT), 0, 1);

$pdf->SetXY(20, 70);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50, 10, 'Date:', 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(100, 10, date('d M Y', strtotime($donation['created_at'])), 0, 1);

$pdf->SetXY(20, 80);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50, 10, 'Donor Name:', 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(100, 10, $donation['donor_name'], 0, 1);

$pdf->SetXY(20, 90);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50, 10, 'PAN Number:', 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(100, 10, $donation['pan_number'] ?? 'N/A', 0, 1);

$pdf->SetXY(20, 100);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50, 10, 'Amount:', 0, 0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(100, 10, 'INR ' . number_format($donation['amount'], 2), 0, 1);

// Tax Info
$pdf->SetXY(10, 140);
$pdf->SetFont('Arial','I',10);
$pdf->MultiCell(0, 5, "Donations to TrustFlow Foundation are exempt from tax under Section 80G of the Income Tax Act.\n\nRegistration No: TF/MUM/2023/1045", 0, 'C');

// Output
$pdf->Output('D', 'Receipt_' . $donation['id'] . '.pdf');
?>
