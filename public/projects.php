<?php
// public/projects.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$db = new Database();
$conn = $db->getConnection();

$filter = $_GET['filter'] ?? 'active';
$status = $filter; // active, completed, paused

$sql = "SELECT * FROM projects WHERE status = :status ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['status' => $status]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="hero-section" style="background-image: url('/assets/img/hero-home.png'); height: 40vh; min-height: 300px;">
    <div class="container hero-content text-center">
        <h1 class="animate__animated animate__fadeInUp">Our Projects</h1>
        <p class="lead text-white animate__animated animate__fadeInUp animate__delay-1s">
            Real stories, real impact. Choose a cause to support today.
        </p>
    </div>
</section>

<!-- Projects Grid -->
<section class="section-padding bg-gray-light">
    <div class="container">
        
        <!-- Filters -->
        <div class="text-center mb-5">
            <a href="projects.php?filter=active" class="btn <?php echo $filter == 'active' ? 'btn-primary-custom' : 'btn-outline-custom'; ?> m-1">Active Projects</a>
            <a href="projects.php?filter=completed" class="btn <?php echo $filter == 'completed' ? 'btn-primary-custom' : 'btn-outline-custom'; ?> m-1">Completed</a>
            <a href="projects.php?filter=paused" class="btn <?php echo $filter == 'paused' ? 'btn-primary-custom' : 'btn-outline-custom'; ?> m-1">Paused/Pending</a>
        </div>
        
        <div class="row g-4">
            <?php if (count($projects) > 0): ?>
                <?php foreach ($projects as $row): ?>
                    <?php 
                        $percent = ($row['goal_amount'] > 0) ? round(($row['raised_amount'] / $row['goal_amount']) * 100) : 0;
                        
                        // Robust Image Logic
                        $imgSrc = '/assets/img/project_default_placeholder.png'; // Default
                        if (!empty($row['image'])) {
                            if (strpos($row['image'], '/') !== false) {
                                $imgSrc = $row['image'];
                            } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/img/' . $row['image'])) {
                                $imgSrc = '/assets/img/' . $row['image'];
                            } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/projects/' . $row['image'])) {
                                $imgSrc = '/assets/uploads/projects/' . $row['image'];
                            } else {
                                $imgSrc = '/assets/img/' . $row['image']; // Fallback
                            }
                        }
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-hover h-100 border-0 shadow-sm overflow-hidden" onclick="window.location.href='project_details.php?id=<?php echo $row['id']; ?>'" style="cursor: pointer;">
                            <div class="position-relative overflow-hidden">
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="card-img-top object-fit-cover transition-zoom" alt="<?php echo htmlspecialchars($row['title']); ?>" style="height: 250px;">

                                <div class="badge bg-orange position-absolute top-0 end-0 m-3 px-3 py-2 text-white" style="background-color: var(--orange-vibrant);">
                                    <?php echo ucfirst($row['status']); ?>
                                </div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h4 class="card-title fw-bold mb-3 text-navy"><?php echo htmlspecialchars($row['title']); ?></h4>
                                <p class="card-text text-muted mb-4 flex-grow-1">
                                    <?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?>
                                </p>
                                
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between small fw-bold mb-2">
                                        <span class="text-navy">Raised: ₹<?php echo number_format($row['raised_amount']); ?></span>
                                        <span class="text-muted">Goal: ₹<?php echo number_format($row['goal_amount']); ?></span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 8px; background-color: var(--gray-200);">
                                        <div class="progress-bar rounded-pill bg-success" role="progressbar" style="width: <?php echo $percent; ?>%" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                
                                <a href="donate.php?project=<?php echo $row['id']; ?>" class="btn btn-outline-custom w-100 mt-auto">Donate to this Cause</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="text-muted mb-3">
                        <i class="fas fa-folder-open fa-3x"></i>
                    </div>
                    <h4>No active projects found.</h4>
                    <p>Please check back later or view our completed campaigns.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
