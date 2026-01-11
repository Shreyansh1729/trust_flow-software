<?php
// api/admin/testimonial_save.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    // Lazy Create Table
    $conn->exec("CREATE TABLE IF NOT EXISTS testimonials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        role VARCHAR(255) DEFAULT 'Supporter',
        message TEXT,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $id = $_POST['id'] ?? null;
    $name = cleanInput($_POST['name']);
    $role = cleanInput($_POST['role']);
    $message = cleanInput($_POST['message']); // cleanInput handles stripping tags, but for testimonials we might want plain text anyway

    // Image Upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = __DIR__ . '/../../assets/uploads/testimonials/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
            $imagePath = $fileName;
        }
    }

    try {
        if ($id) {
            $sql = "UPDATE testimonials SET name = :name, role = :role, message = :message";
            $params = ['name' => $name, 'role' => $role, 'message' => $message, 'id' => $id];
            
            if ($imagePath) {
                $sql .= ", image = :image";
                $params['image'] = $imagePath;
            }
            
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
        } else {
            $stmt = $conn->prepare("INSERT INTO testimonials (name, role, message, image) VALUES (:name, :role, :message, :image)");
            $stmt->execute(['name' => $name, 'role' => $role, 'message' => $message, 'image' => $imagePath]);
        }
        header("Location: /admin/testimonials.php?msg=saved");
    } catch (PDOException $e) {
        header("Location: /admin/testimonial-form.php?error=failed");
    }
}
?>
