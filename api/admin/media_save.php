<?php
// api/admin/media_save.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection (Assuming global CSRF check or adding it here if missing in form)
    // verifyCsrfToken($_POST['csrf_token']); // Todo: ensure form sends it

    $title = cleanInput($_POST['title']);
    
    // Image Upload Logic
    $imagePath = null;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileName = $_FILES['image']['name'];
        
        // 1. Validate MIME Type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($fileTmp);
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            redirect('/admin/media.php', 'Invalid file type. Only JPG, PNG, GIF, and WebP allowed.', 'danger');
        }
        
        // 2. Validate Extension
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            redirect('/admin/media.php', 'Invalid file extension.', 'danger');
        }
        
        // 3. Limit File Size (e.g., 5MB)
        if ($fileSize > 5 * 1024 * 1024) {
            redirect('/admin/media.php', 'File too large. Max 5MB.', 'danger');
        }

        // 4. Generate Safe Filename
        $uploadDir = __DIR__ . '/../../assets/uploads/gallery/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        // Use hash of raw file content + timestamp for uniqueness and safety
        $newFileName = 'img_' . hash_file('sha256', $fileTmp) . '.' . $extension;
        
        if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
            $imagePath = $newFileName;
        } else {
             redirect('/admin/media.php', 'Failed to save file.', 'danger');
        }
    } else {
        redirect('/admin/media.php', 'No file uploaded or upload error.', 'danger');
    }

    if ($imagePath) {
        try {
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->prepare("INSERT INTO media_gallery (title, image) VALUES (:title, :image)");
            $stmt->execute(['title' => $title, 'image' => $imagePath]);
            redirect('/admin/media.php', 'Media uploaded successfully.', 'success');
        } catch (PDOException $e) {
            // Log error internally
            error_log("DB Error: " . $e->getMessage());
            redirect('/admin/media.php', 'Database error occurred.', 'danger');
        }
    }
}
?>
