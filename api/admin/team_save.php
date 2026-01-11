<?php
// api/admin/team_save.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = cleanInput($_POST['name']);
    $role = cleanInput($_POST['role']);
    $bio = cleanInput($_POST['bio'] ?? '');
    $category = $_POST['category'] ?? 'volunteer'; // trustee or volunteer

    if (empty($name) || empty($role)) {
        header("Location: /admin/team-form.php?error=Missing fields");
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    // Custom SQL to create table if not exists (Lazy Init)
    $conn->exec("CREATE TABLE IF NOT EXISTS team_members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(100) NOT NULL,
        bio TEXT,
        image VARCHAR(255),
        category ENUM('trustee', 'volunteer') DEFAULT 'trustee',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Handle Image Upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $filename = uniqid() . '.' . $ext;
            $uploadDir = __DIR__ . '/../../assets/uploads/team/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                $imagePath = '/assets/uploads/team/' . $filename;
            }
        }
    }

    try {
        if ($id) {
            // Update
            $sql = "UPDATE team_members SET name = :name, role = :role, bio = :bio, category = :category";
            $params = ['name' => $name, 'role' => $role, 'bio' => $bio, 'category' => $category, 'id' => $id];
            
            if ($imagePath) {
                $sql .= ", image = :image";
                $params['image'] = $imagePath;
            }
            $sql .= " WHERE id = :id";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO team_members (name, role, bio, category, image) VALUES (:name, :role, :bio, :category, :image)");
            $stmt->execute(['name' => $name, 'role' => $role, 'bio' => $bio, 'category' => $category, 'image' => $imagePath]);
        }
        
        header("Location: /admin/team.php?msg=saved");
    } catch (PDOException $e) {
        header("Location: /admin/team-form.php?error=" . urlencode($e->getMessage()));
    }
}
?>
