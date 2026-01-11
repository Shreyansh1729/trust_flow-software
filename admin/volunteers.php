<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

$db = new Database();
$conn = $db->getConnection();

// Lazy Create Table if not exists (redundant if public page hit first, but safe)
$conn->exec("CREATE TABLE IF NOT EXISTS volunteers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    skills TEXT,
    availability VARCHAR(100),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Fetch Volunteers
$stmt = $conn->prepare("SELECT * FROM volunteers ORDER BY created_at DESC");
$stmt->execute();
$volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Volunteer Applications</h2>
    <span class="badge bg-primary rounded-pill px-3 py-2"><?php echo count($volunteers); ?> Total</span>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Name & Contact</th>
                        <th>Skills</th>
                        <th>Availability</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Date</th>
                        <th class="text-end pe-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($volunteers) > 0): ?>
                        <?php foreach ($volunteers as $v): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?php echo $v['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded-circle bg-primary-subtle text-primary me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <?php echo strtoupper(substr($v['name'] ?? 'U', 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($v['name'] ?? 'Unknown'); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($v['email'] ?? ''); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-muted small"><?php echo htmlspecialchars(substr($v['skills'], 0, 30)) . (strlen($v['skills']) > 30 ? '...' : ''); ?></span></td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($v['availability']); ?></span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle rounded-pill px-3 <?php 
                                        echo ($v['status'] == 'approved') ? 'btn-success-subtle text-success' : 
                                             (($v['status'] == 'rejected') ? 'btn-danger-subtle text-danger' : 'btn-warning-subtle text-warning'); 
                                    ?>" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php echo ucfirst($v['status']); ?>
                                    </button>
                                    <ul class="dropdown-menu shadow-sm border-0">
                                        <li><a class="dropdown-item small" href="#" onclick="updateStatus(<?php echo $v['id']; ?>, 'pending')"><i class="fas fa-clock text-warning me-2"></i>Pending</a></li>
                                        <li><a class="dropdown-item small" href="#" onclick="updateStatus(<?php echo $v['id']; ?>, 'approved')"><i class="fas fa-check text-success me-2"></i>Approved</a></li>
                                        <li><a class="dropdown-item small" href="#" onclick="updateStatus(<?php echo $v['id']; ?>, 'rejected')"><i class="fas fa-times text-danger me-2"></i>Rejected</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="text-muted small"><?php echo date('M d, Y', strtotime($v['created_at'])); ?></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary rounded-circle me-1" title="View Details" 
                                        onclick='viewVolunteer(<?php echo json_encode($v); ?>)'>
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="/api/admin/volunteer_delete.php?id=<?php echo $v['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" 
                                   title="Delete" onclick="return confirm('Permanently delete this application?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fas fa-user-friends fa-3x opacity-25"></i></div>
                                No applications received yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div> <!-- End Container -->
</div> <!-- End Main Content -->

<!-- Volunteer Details Modal -->
<div class="modal fade" id="volunteerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-navy">Volunteer Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar-lg rounded-circle bg-orange-light text-orange d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: bold;">
                        <span id="modalAvatar">U</span>
                    </div>
                    <h4 id="modalName" class="fw-bold mb-0"></h4>
                    <p id="modalEmail" class="text-muted mb-0"></p>
                    <span id="modalStatus" class="badge rounded-pill mt-2"></span>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <label class="small text-uppercase text-muted fw-bold">Phone</label>
                        <p id="modalPhone" class="fw-medium"></p>
                    </div>
                    <div class="col-6">
                        <label class="small text-uppercase text-muted fw-bold">Availability</label>
                        <p id="modalAvailability" class="fw-medium"></p>
                    </div>
                    <div class="col-12">
                        <label class="small text-uppercase text-muted fw-bold">Skills & Interests</label>
                        <div class="bg-light p-3 rounded-3 mt-1">
                            <p id="modalSkills" class="mb-0 text-secondary" style="white-space: pre-wrap;"></p>
                        </div>
                    </div>
                     <div class="col-12">
                        <label class="small text-uppercase text-muted fw-bold">Applied On</label>
                        <p id="modalDate" class="fw-medium"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dashboard.js"></script>
<script>
function viewVolunteer(data) {
    document.getElementById('modalName').textContent = data.name;
    document.getElementById('modalEmail').textContent = data.email;
    document.getElementById('modalAvatar').textContent = data.name.charAt(0).toUpperCase();
    document.getElementById('modalPhone').textContent = data.phone || 'N/A';
    document.getElementById('modalAvailability').textContent = data.availability || 'N/A';
    document.getElementById('modalSkills').textContent = data.skills || 'No details provided.';
    document.getElementById('modalDate').textContent = new Date(data.created_at).toLocaleDateString("en-US", { year: 'numeric', month: 'long', day: 'numeric' });
    
    const statusEl = document.getElementById('modalStatus');
    statusEl.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
    statusEl.className = 'badge rounded-pill mt-2'; // reset
    if(data.status === 'approved') statusEl.classList.add('bg-success-subtle', 'text-success');
    else if(data.status === 'rejected') statusEl.classList.add('bg-danger-subtle', 'text-danger');
    else statusEl.classList.add('bg-warning-subtle', 'text-warning');
    
    new bootstrap.Modal(document.getElementById('volunteerModal')).show();
}

function updateStatus(id, status) {
    if(confirm('Change status to ' + status + '?')) {
        fetch('/api/admin/volunteer_update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, status: status })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed'));
            }
        });
    }
}
</script>
</body>
</html>
