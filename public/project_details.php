<?php
// public/project_details.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>window.location.href='projects.php';</script>";
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = :id");
$stmt->execute(['id' => $id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    echo "<div class='container py-5 text-center'><h3>Project not found.</h3></div>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Image Logic
$imgSrc = '/assets/img/project_default_placeholder.png';
if (!empty($project['image'])) {
    if (strpos($project['image'], '/') !== false) {
        $imgSrc = $project['image'];
    } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/img/' . $project['image'])) {
        $imgSrc = '/assets/img/' . $project['image'];
    } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/projects/' . $project['image'])) {
        $imgSrc = '/assets/uploads/projects/' . $project['image'];
    }
}

$percent = ($project['goal_amount'] > 0) ? round(($project['raised_amount'] / $project['goal_amount']) * 100) : 0;
?>

<!-- Hero -->
<section class="position-relative" style="height: 50vh; min-height: 400px; background-color: var(--navy-dark);">
    <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="w-100 h-100 object-fit-cover opacity-50" style="filter: brightness(0.4);">
    <div class="position-absolute top-50 start-50 translate-middle text-center w-100 px-3">
        <h1 class="display-3 fw-bold mb-3 text-white"><?php echo htmlspecialchars($project['title']); ?></h1>
        <span class="badge bg-orange rounded-pill px-4 py-2 text-uppercase fs-6 shadow-sm"><?php echo ucfirst($project['status']); ?></span>
    </div>
</section>

<!-- Content -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 mt-n5 position-relative">
                    
                    <!-- Progress Stats -->
                    <div class="row g-4 mb-4 text-center">
                        <div class="col-md-6 border-end">
                            <h3 class="fw-bold text-success mb-0">₹<?php echo number_format($project['raised_amount']); ?></h3>
                            <small class="text-muted text-uppercase fw-bold">Raised</small>
                        </div>
                        <div class="col-md-6">
                            <h3 class="fw-bold text-navy mb-0">₹<?php echo number_format($project['goal_amount']); ?></h3>
                            <small class="text-muted text-uppercase fw-bold">Goal</small>
                        </div>
                    </div>
                    
                    <div class="progress rounded-pill bg-light mb-4" style="height: 15px;">
                        <div class="progress-bar bg-gradient-orange rounded-pill" role="progressbar" style="width: <?php echo $percent; ?>%;">
                            <?php echo $percent; ?>%
                        </div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <h4 class="fw-bold text-navy mb-3">About the Project</h4>
                    <div class="text-muted lh-lg mb-5" style="white-space: pre-wrap;">
                        <?php 
                            // Display Full Content if exists, else short description
                            echo !empty($project['content']) ? $project['content'] : htmlspecialchars($project['description']); 
                        ?>
                    </div>

                    <?php if($project['status'] == 'active'): ?>
                    <a href="donate.php?project=<?php echo $project['id']; ?>" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm">
                        Donate to this Cause <i class="fas fa-heart ms-2"></i>
                    </a>
                    <?php else: ?>
                    <button disabled class="btn btn-secondary w-100 py-3 rounded-pill fw-bold fs-5">
                        Project <?php echo ucfirst($project['status']); ?>
                    </button>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>

<style>
.bg-gradient-orange { background: linear-gradient(90deg, var(--orange-vibrant) 0%, #fb923c 100%); }
.mt-n5 { margin-top: -5rem; }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
