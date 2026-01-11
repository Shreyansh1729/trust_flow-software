<?php
// admin/settings.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$message = '';
$messageType = '';

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Password Change
    if (isset($_POST['action']) && $_POST['action'] === 'password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $message = "All password fields are required.";
            $messageType = "danger";
        } elseif ($new_password !== $confirm_password) {
            $message = "New passwords do not match.";
            $messageType = "danger";
        } else {
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            if ($user && password_verify($current_password, $user['password'])) {
                $hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                if ($update->execute([$hashed, $_SESSION['user_id']])) {
                    $message = "Password updated successfully.";
                    $messageType = "success";
                }
            } else {
                $message = "Incorrect current password.";
                $messageType = "danger";
            }
        }
    }

    // 2. Organization Settings
    if (isset($_POST['action']) && $_POST['action'] === 'settings') {
        $db = new Database();
        $conn = $db->getConnection();
        $settings = ['legal_name', 'auditor_name', 'registration_no', 'fcra_status'];
        foreach ($settings as $key) {
            if (isset($_POST[$key])) {
                $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$_POST[$key], $key]);
                // If not exists, insert? The migration seeded them, but safe to INSERT IGNORE or ON DUPLICATE
            }
        }
        $message = "Organization details updated.";
        $messageType = "success";
    }

    // 3. Document Upload
    if (isset($_POST['action']) && $_POST['action'] === 'upload_doc') {
        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? 'report';
        $year = $_POST['year'] ?? date('Y');
        
        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../assets/uploads/docs/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $filename = uniqid() . '_' . basename($_FILES['document']['name']);
            if (move_uploaded_file($_FILES['document']['tmp_name'], $uploadDir . $filename)) {
                $db = new Database();
                $conn = $db->getConnection();
                $stmt = $conn->prepare("INSERT INTO documents (title, category, file_path, year) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $category, '/assets/uploads/docs/' . $filename, $year]);
                $message = "Document uploaded successfully.";
                $messageType = "success";
            } else {
                $message = "Failed to move uploaded file.";
                $messageType = "danger";
            }
        } else {
            $message = "Please select a valid file.";
            $messageType = "danger";
        }
    }
    
    // 4. Delete Document
    if (isset($_POST['action']) && $_POST['action'] === 'delete_doc') {
        $doc_id = $_POST['doc_id'];
        $db = new Database();
        $conn = $db->getConnection();
        // Get path to delete file
        $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = ?");
        $stmt->execute([$doc_id]);
        $doc = $stmt->fetch();
        if ($doc) {
            $filepath = __DIR__ . '/..' . $doc['file_path'];
            if (file_exists($filepath)) unlink($filepath);
            
            $del = $conn->prepare("DELETE FROM documents WHERE id = ?");
            $del->execute([$doc_id]);
            $message = "Document deleted.";
            $messageType = "success";
        }
    }
}

// Fetch Current Data
$db = new Database();
$conn = $db->getConnection();

// Settings
$settings_stmt = $conn->query("SELECT setting_key, setting_value FROM settings");
$settings_data = [];
while ($row = $settings_stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings_data[$row['setting_key']] = $row['setting_value'];
}
// Defaults if missing
$settings_data['legal_name'] = $settings_data['legal_name'] ?? '';
$settings_data['auditor_name'] = $settings_data['auditor_name'] ?? '';
$settings_data['registration_no'] = $settings_data['registration_no'] ?? '';
$settings_data['fcra_status'] = $settings_data['fcra_status'] ?? '';


// Documents
$docs_stmt = $conn->query("SELECT * FROM documents ORDER BY created_at DESC");
$documents = $docs_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-navy mb-1">Settings & Financials</h2>
        <p class="text-muted mb-0">Manage account, legal details, and public documents.</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show mb-4" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="list-group shadow-sm rounded-4 overflow-hidden">
            <a href="#account" class="list-group-item list-group-item-action active py-3 px-4 fw-bold" data-bs-toggle="list">
                <i class="fas fa-user-cog me-2"></i> Account
            </a>
            <a href="#legal" class="list-group-item list-group-item-action py-3 px-4 fw-bold" data-bs-toggle="list">
                <i class="fas fa-building me-2"></i> Organization
            </a>
            <a href="#docs" class="list-group-item list-group-item-action py-3 px-4 fw-bold" data-bs-toggle="list">
                <i class="fas fa-file-pdf me-2"></i> Documents
            </a>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="tab-content">
            
            <!-- Account Tab -->
            <div class="tab-pane fade show active" id="account">
                <div class="card border-0 shadow-premium rounded-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-navy">Change Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <input type="hidden" name="action" value="password">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Current Password</label>
                                <input type="password" name="current_password" class="form-control bg-light border-0 py-2" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase">New Password</label>
                                    <input type="password" name="new_password" class="form-control bg-light border-0 py-2" required minlength="6">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control bg-light border-0 py-2" required>
                                </div>
                            </div>
                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary-custom rounded-pill">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Legal Settings Tab -->
            <div class="tab-pane fade" id="legal">
                <div class="card border-0 shadow-premium rounded-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-navy">Legal & Compliance Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <input type="hidden" name="action" value="settings">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Legal Entity Name</label>
                                    <input type="text" name="legal_name" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($settings_data['legal_name']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Registration No</label>
                                    <input type="text" name="registration_no" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($settings_data['registration_no']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Auditor Name</label>
                                    <input type="text" name="auditor_name" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($settings_data['auditor_name']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">FCRA Status</label>
                                    <input type="text" name="fcra_status" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($settings_data['fcra_status']); ?>">
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary-custom rounded-pill">Save Details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="docs">
                <!-- Upload Form -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Upload New Document</h6>
                        <form method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
                            <input type="hidden" name="action" value="upload_doc">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Title / Year</label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Annual Report 2024" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Category</label>
                                <select name="category" class="form-select form-select-sm">
                                    <option value="report">Annual Report</option>
                                    <option value="legal">Legal Certificate</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">File (PDF)</label>
                                <input type="file" name="document" class="form-control form-control-sm" accept=".pdf" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fas fa-upload me-1"></i> Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Documents List -->
                <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Title</th>
                                    <th>Category</th>
                                    <th>Link</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($documents as $doc): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?php echo htmlspecialchars($doc['title']); ?></td>
                                    <td>
                                        <?php if($doc['category'] == 'legal'): ?>
                                            <span class="badge bg-warning-subtle text-warning-emphasis">Legal Cert</span>
                                        <?php else: ?>
                                            <span class="badge bg-info-subtle text-info-emphasis">Annual Report</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><a href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="text-primary text-decoration-none small"><i class="fas fa-external-link-alt"></i> View</a></td>
                                    <td class="text-end pe-4">
                                        <form method="POST" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="action" value="delete_doc">
                                            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-link text-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dashboard.js"></script>
</body>
</html>
