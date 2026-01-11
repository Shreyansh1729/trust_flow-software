<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

$db = new Database();
$conn = $db->getConnection();

// Lazy Create Table check (redundant but safe)
$conn->exec("CREATE TABLE IF NOT EXISTS inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(200),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Fetch Inquiries
$stmt = $conn->prepare("SELECT * FROM inquiries ORDER BY created_at DESC");
$stmt->execute();
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0">Inquiries & Messages</h2>
    <span class="badge bg-primary rounded-pill px-3 py-2"><?php echo count($inquiries); ?> Total</span>
</div>

<div class="card border-0 shadow-premium rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Date</th>
                        <th class="py-3">Name</th>
                        <th class="py-3">Subject</th>
                        <th class="py-3">Message</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($inquiries) > 0): ?>
                        <?php foreach ($inquiries as $row): ?>
                        <tr>
                            <td class="ps-4 text-muted small" style="white-space: nowrap;">
                                <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                            </td>
                            <td class="fw-bold text-navy">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded-circle bg-orange-light text-orange me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <?php echo htmlspecialchars($row['name']); ?>
                                        <div class="small text-muted fw-normal"><?php echo htmlspecialchars($row['email']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border fw-normal"><?php echo htmlspecialchars(substr($row['subject'], 0, 20)); ?></span></td>
                            <td>
                                <div class="text-muted small" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($row['message']); ?>">
                                    <?php echo htmlspecialchars($row['message']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle rounded-pill px-3 <?php 
                                        echo ($row['status'] == 'replied') ? 'btn-success-subtle text-success' : 
                                             (($row['status'] == 'read') ? 'btn-info-subtle text-info' : 'btn-warning-subtle text-warning'); 
                                    ?>" type="button" data-bs-toggle="dropdown">
                                        <?php echo ucfirst($row['status'] ?? 'new'); ?>
                                    </button>
                                    <ul class="dropdown-menu shadow-sm border-0">
                                        <li><a class="dropdown-item small" href="#" onclick="updateInquiryStatus(<?php echo $row['id']; ?>, 'new')"><i class="fas fa-star text-warning me-2"></i>New</a></li>
                                        <li><a class="dropdown-item small" href="#" onclick="updateInquiryStatus(<?php echo $row['id']; ?>, 'read')"><i class="fas fa-check-double text-info me-2"></i>Read</a></li>
                                        <li><a class="dropdown-item small" href="#" onclick="updateInquiryStatus(<?php echo $row['id']; ?>, 'replied')"><i class="fas fa-reply text-success me-2"></i>Replied</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v fa-sm"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg animate__animated animate__fadeIn">
                                        <li><h6 class="dropdown-header text-uppercase fw-bold text-xs ls-1 text-muted py-2 px-3">Actions</h6></li>
                                        <li>
                                            <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="#" onclick='viewInquiry(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, "UTF-8"); ?>)'>
                                                <i class="fas fa-envelope-open text-primary me-2"></i> Read Message
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item py-2 px-3 d-flex align-items-center text-danger" href="/api/admin/inquiry_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this inquiry permanently?');">
                                                <i class="fas fa-trash-alt me-2"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fas fa-inbox fa-3x opacity-25"></i></div>
                                <p>No inquiries received yet.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function updateInquiryStatus(id, status) {
    fetch('/api/admin/inquiry_update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, status: status })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) location.reload();
        else alert('Error updating status');
    });
}
</script>

</div> <!-- End Container -->
</div> <!-- End Main Content -->

<!-- Inquiry Details Modal -->
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="modal-title fw-bold text-navy">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar-md rounded-circle bg-orange-light text-orange me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px; font-size: 1.2rem;">
                        <span id="modalAvatar">A</span>
                    </div>
                    <div>
                        <h5 id="modalName" class="fw-bold mb-0 text-dark"></h5>
                        <p id="modalEmail" class="text-muted mb-0 small"></p>
                    </div>
                    <div class="ms-auto text-end">
                        <small class="text-muted fw-bold text-uppercase d-block">Received On</small>
                        <span id="modalDate" class="text-dark fw-bold"></span>
                    </div>
                </div>
                
                <div class="bg-light rounded-4 p-4 mb-3">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">Subject</h6>
                    <p id="modalSubject" class="fw-bold text-navy mb-3"></p>
                    
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">Message</h6>
                    <p id="modalMessage" class="text-secondary mb-0" style="white-space: pre-wrap; line-height: 1.6;"></p>
                </div>
            </div>
            <div class="modal-footer border-top-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <a href="#" id="modalReplyBtn" class="btn btn-primary-custom rounded-pill px-4">
                    <i class="fas fa-reply me-2"></i> Reply
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dashboard.js"></script>
<script>
function viewInquiry(data) {
    document.getElementById('modalName').textContent = data.name;
    document.getElementById('modalEmail').textContent = data.email;
    document.getElementById('modalAvatar').textContent = data.name.charAt(0).toUpperCase();
    document.getElementById('modalSubject').textContent = data.subject;
    document.getElementById('modalMessage').textContent = data.message;
    document.getElementById('modalDate').textContent = new Date(data.created_at).toLocaleDateString("en-US", { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    
    document.getElementById('modalReplyBtn').href = "mailto:" + data.email + "?subject=Re: " + encodeURIComponent(data.subject);
    
    new bootstrap.Modal(document.getElementById('inquiryModal')).show();
}
</script>
</body>
</html>
