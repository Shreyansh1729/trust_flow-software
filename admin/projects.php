<?php
// admin/projects.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

// Fetch Projects
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0">Projects & Campaigns</h2>
    <a href="project-form.php" class="btn btn-primary-custom shadow-sm">
        <i class="fas fa-plus me-2"></i> Add New Project
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr class="bg-gray-light">
                        <th class="ps-4" width="50">ID</th>
                        <th width="100">Image</th>
                        <th>Title / Slug</th>
                        <th>Goal</th>
                        <th>Raised</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($projects) > 0): ?>
                        <?php foreach ($projects as $row): ?>
                        <?php 
                            $percent = ($row['goal_amount'] > 0) ? round(($row['raised_amount'] / $row['goal_amount']) * 100) : 0;
                        ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?php echo $row['id']; ?></td>
                            <td>
                                <img src="<?php echo !empty($row['image']) ? '/assets/uploads/projects/' . $row['image'] : '/assets/img/placeholder.jpg'; ?>" 
                                     class="rounded-3 object-fit-cover" width="60" height="60" alt="Thumb">
                            </td>
                            <td>
                                <div class="fw-bold text-navy"><?php echo htmlspecialchars($row['title']); ?></div>
                                <small class="text-muted">/<?php echo htmlspecialchars($row['slug']); ?></small>
                            </td>
                            <td class="fw-bold">₹<?php echo number_format($row['goal_amount']); ?></td>
                            <td>
                                <div class="fw-bold text-success">₹<?php echo number_format($row['raised_amount']); ?></div>
                                <div class="progress mt-1" style="height: 4px; width: 80px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo $percent; ?>%"></div>
                                </div>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'active'): ?>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">Active</span>
                                <?php elseif ($row['status'] == 'completed'): ?>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Completed</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Paused</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="project-form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-outline-danger" onclick="if(confirm('Are you sure?')) window.location.href='../api/admin/project_delete.php?id=<?php echo $row['id']; ?>'"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No projects created yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
