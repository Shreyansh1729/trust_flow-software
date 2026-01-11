<?php
// api/admin/project_save.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/slugify.php'; // Ensure simple slug helper exists or use inline

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = cleanInput($_POST['title']);
    $goal_amount = (float)$_POST['goal_amount'];
    $status = cleanInput($_POST['status']);
    $description = cleanInput($_POST['description']);
    $content = $_POST['content']; // Allow HTML or plain text, be careful with XSS if public input allowed (here it's admin)
    $slug = createSlug($title);

    $db = new Database();
    $conn = $db->getConnection();

    // Lazy Create Table (ensure content exists)
    // ... (omitted)

    // Image Upload
    // ... (omitted)

    if ($status === 'completed') {
        $raised_amount = $goal_amount; 
    }

    try {
        if ($id) {
            $sql = "UPDATE projects SET title = :title, slug = :slug, description = :description, content = :content, goal_amount = :goal, status = :status";
            $params = [
                'title' => $title, 'slug' => $slug, 'description' => $description, 'content' => $content,
                'goal' => $goal_amount, 'status' => $status, 'id' => $id
            ];
            
            if ($status === 'completed') {
                $sql .= ", raised_amount = :raised";
                $params['raised'] = $goal_amount;
            }

            if ($imagePath) {
                $sql .= ", image = :image";
                $params['image'] = $imagePath;
            }
            
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO projects (title, slug, description, content, goal_amount, raised_amount, status, image) VALUES (:title, :slug, :description, :content, :goal, :raised, :status, :image)");
            $stmt->execute([
                'title' => $title, 'slug' => $slug, 'description' => $description, 'content' => $content,
                'goal' => $goal_amount, 
                'raised' => ($status === 'completed' ? $goal_amount : 0), 
                'status' => $status, 
                'image' => $imagePath
            ]);
        }
        header("Location: /admin/projects.php?msg=saved");
    } catch (PDOException $e) {
         header("Location: /admin/project-form.php?error=failed");
    }
}
?>
