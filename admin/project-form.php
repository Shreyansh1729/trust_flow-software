<?php
// admin/project-form.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

$project = null;
$is_edit = false;

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($project) $is_edit = true;
}
?>

<script>
function checkStatus(select) {
    if (select.value === 'completed') {
        alert("NOTE: Marking this project as 'Completed' will automatically set the 'Raised Amount' to 100% of the Goal Amount.");
    }
}
</script>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-navy mb-0"><?php echo $is_edit ? 'Edit Project' : 'Create New Project'; ?></h2>
            <a href="projects.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card border-0 shadow-premium rounded-4">
            <div class="card-body p-5">
                <form action="../api/admin/project_save.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $project['id'] ?? ''; ?>">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Project Title</label>
                        <input type="text" class="form-control py-3" name="title" value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>" required>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Goal Amount (₹)</label>
                            <input type="number" class="form-control py-3" name="goal_amount" value="<?php echo htmlspecialchars($project['goal_amount'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <select class="form-select py-3" name="status" onchange="checkStatus(this)">
                                <option value="active" <?php echo ($project['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="completed" <?php echo ($project['status'] ?? '') == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="paused" <?php echo ($project['status'] ?? '') == 'paused' ? 'selected' : ''; ?>>Paused</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Short Description (Excerpt)</label>
                         <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
                         <div class="form-text">Shown on card previews.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Full Project Details</label>
                         <textarea class="form-control" name="content" rows="10"><?php echo htmlspecialchars($project['content'] ?? ''); ?></textarea>
                         <div class="form-text">Shown on the detailed project page. You can use HTML/Text.</div>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label fw-bold">Cover Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <?php if($is_edit && !empty($project['image'])): ?>
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">Current Image:</small>
                                <img src="/assets/uploads/projects/<?php echo $project['image']; ?>" class="rounded-3 shadow-sm" width="150" alt="Current">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-custom py-3 fw-bold">
                            <?php echo $is_edit ? 'Update Project' : 'Create Project'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
