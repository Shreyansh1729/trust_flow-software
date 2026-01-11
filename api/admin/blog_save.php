<?php
// api/admin/blog_save.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST['id'] ?? '';
    $title = cleanInput($_POST['title']);
    $content = $_POST['content']; // Don't clean HTML aggressively if we want formatting
    $status = $_POST['status'];
    $author = cleanInput($_POST['author']);
    
    // Auto Generate Slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    $db = new Database();
    $conn = $db->getConnection();
    
    // Handle File Upload
    $image_name = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid('blog_') . '.' . $ext;
            $upload_path = __DIR__ . '/../../assets/uploads/blog/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_name = $new_filename;
            }
        }
    }

    try {
        if (!empty($id)) {
            // UDPATE
            $sql = "UPDATE blogs SET title = :title, slug = :slug, content = :content, status = :status, author = :author";
            $params = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status,
                'author' => $author,
                'id' => $id
            ];
            
            if ($image_name) {
                $sql .= ", image = :img";
                $params['img'] = $image_name;
            }
            
            $sql .= " WHERE id = :id";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            redirect('/admin/blogs.php', 'Article updated!', 'success');
            
        } else {
            // INSERT
            $sql = "INSERT INTO blogs (title, slug, content, image, status, author, created_at) VALUES (:title, :slug, :content, :img, :status, :author, NOW())";
            $params = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'img' => $image_name,
                'status' => $status,
                'author' => $author
            ];
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            redirect('/admin/blogs.php', 'Article published!', 'success');
        }
        
    } catch (PDOException $e) {
        redirect('/admin/blog-form.php', 'Error: ' . $e->getMessage(), 'danger');
    }

} else {
    redirect('/admin/blogs.php', 'Invalid request', 'danger');
}
