<?php
// admin/users.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

// Handle Search & Filter
$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';

$sql = "SELECT * FROM users WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (name LIKE :search OR email LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($role) {
    $sql .= " AND role = :role";
    $params[':role'] = $role;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-navy mb-1">User Management</h2>
        <p class="text-muted mb-0">Manage registered donors, volunteers, and admins.</p>
    </div>
</div>

<div class="card border-0 shadow-premium rounded-4">
    <div class="card-header bg-white py-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-navy">All Users</h5>
        
        <form class="d-flex gap-2" method="GET">
            <select name="role" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()" style="width: 150px;">
                <option value="">All Roles</option>
                <option value="donor" <?php echo $role == 'donor' ? 'selected' : ''; ?>>Donors</option>
                <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admins</option>
            </select>
            
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 rounded-end-pill" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div>
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">User</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Joined</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded-circle bg-primary-subtle text-primary me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($user['name']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    $badgeClass = 'bg-secondary-subtle text-secondary';
                                    if ($user['role'] == 'admin') $badgeClass = 'bg-danger-subtle text-danger';
                                    if ($user['role'] == 'donor') $badgeClass = 'bg-success-subtle text-success';
                                    if ($user['role'] == 'volunteer') $badgeClass = 'bg-info-subtle text-info';
                                ?>
                                <span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.7rem;">
                                    <?php echo htmlspecialchars($user['role']); ?>
                                </span>
                            </td>
                            <td class="text-muted small">
                                <i class="far fa-calendar-alt me-1"></i> <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        Manage
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                        <li><h6 class="dropdown-header text-uppercase fw-bold text-xs ls-1 text-muted py-2 px-3">Manage</h6></li>
                                        <li>
                                            <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="#" onclick='viewUser(<?php echo htmlspecialchars(json_encode($user), ENT_QUOTES, "UTF-8"); ?>)'>
                                                <i class="fas fa-eye text-primary me-2"></i> View Details
                                            </a>
                                        </li>
                                        <?php if ($user['role'] !== 'admin'): ?>
                                        <li>
                                            <a class="dropdown-item py-2 px-3 d-flex align-items-center text-danger" href="/api/admin/user_delete.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Permanently delete this user? This cannot be undone.');">
                                                <i class="fas fa-trash-alt me-2"></i> Delete User
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fas fa-users-slash fa-3x opacity-25"></i></div>
                                No users found matching your search.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="modal-title fw-bold text-navy">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar-lg rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3 fw-bold" style="width: 80px; height: 80px; font-size: 2rem;">
                        <span id="modalUserAvatar"></span>
                    </div>
                    <h4 id="modalUserName" class="fw-bold mb-1 text-navy"></h4>
                    <p id="modalUserEmail" class="text-muted mb-2"></p>
                    <span id="modalUserRole" class="badge rounded-pill px-3 py-2"></span>
                </div>
                
                <div class="bg-light rounded-4 p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase">Member Since</span>
                        <span class="fw-bold text-dark" id="modalUserDate"></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small fw-bold text-uppercase">User ID</span>
                        <span class="fw-bold text-dark font-monospace" id="modalUserId"></span>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                     <p class="small text-muted mb-0"><i class="fas fa-shield-alt me-1"></i> Account Status: <span class="text-success fw-bold">Active</span></p>
                </div>
            </div>
            <div class="modal-footer border-top-0 p-4 pt-0 justify-content-center">
                <button type="button" class="btn btn-light rounded-pill px-4 w-100" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="/assets/js/dashboard.js"></script>
<script>
function viewUser(user) {
    document.getElementById('modalUserName').textContent = user.name;
    document.getElementById('modalUserEmail').textContent = user.email;
    document.getElementById('modalUserAvatar').textContent = user.name.charAt(0).toUpperCase();
    document.getElementById('modalUserId').textContent = '#' + user.id;
    document.getElementById('modalUserDate').textContent = new Date(user.created_at).toLocaleDateString("en-US", { year: 'numeric', month: 'long', day: 'numeric' });
    
    const roleEl = document.getElementById('modalUserRole');
    roleEl.textContent = user.role.toUpperCase();
    roleEl.className = 'badge rounded-pill px-3 py-2 ';
    
    if(user.role === 'admin') roleEl.classList.add('bg-danger-subtle', 'text-danger');
    else if(user.role === 'volunteer') roleEl.classList.add('bg-info-subtle', 'text-info');
    else roleEl.classList.add('bg-success-subtle', 'text-success');
    
    new bootstrap.Modal(document.getElementById('userDetailsModal')).show();
}
</script>
</body>
</html>
