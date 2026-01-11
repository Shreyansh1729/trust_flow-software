<?php
// api/public/testimonial_submit.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    $name = cleanInput($_POST['name']);
    $role = cleanInput($_POST['role']);
    $message = cleanInput($_POST['message']);
    
    // Image Upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] < 5000000) { // 5MB limit
            $uploadDir = __DIR__ . '/../../assets/uploads/testimonials/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $imagePath = $fileName;
            }
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO testimonials (name, role, message, image, status) VALUES (:name, :role, :message, :image, 'pending')");
        $stmt->execute([
            'name' => $name, 
            'role' => $role, 
            'message' => $message, 
            'image' => $imagePath
        ]);
        header("Location: /public/testimonials.php?msg=submitted");
    } catch (PDOException $e) {
        header("Location: /public/testimonials.php?error=failed");
    }
}
?>
