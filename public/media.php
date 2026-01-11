<?php
// public/media.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<section class="hero-section position-relative d-flex align-items-center justify-content-center" style="background-image: url('/assets/img/hero_media.png'); background-size: cover; background-position: center; height: 50vh; min-height: 350px;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-navy" style="opacity: 0.85; background: linear-gradient(rgba(10, 25, 47, 0.9), rgba(10, 25, 47, 0.7)); z-index: 1;"></div>
    <div class="container position-relative text-center text-white" style="z-index: 2;">
        <h1 class="display-3 fw-bold mb-3 animate__animated animate__fadeInUp" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Impact Gallery</h1>
        <p class="lead text-white-50 mx-auto animate__animated animate__fadeInUp animate__delay-1s" style="max-width: 600px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            A visual journey through our fieldwork, community events, and the smiles we help create.
        </p>
    </div>
</section>

<!-- Gallery Grid -->
<section class="section-padding bg-white">
    <div class="container">
        <?php
        $db = new Database();
        $conn = $db->getConnection();

        // Pagination Config
        $limit = 9;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Count Total
        $countStmt = $conn->query("SELECT COUNT(*) FROM media_gallery");
        $total_rows = $countStmt->fetchColumn();
        $total_pages = ceil($total_rows / $limit);

        // Fetch Data
        $stmt = $conn->prepare("SELECT * FROM media_gallery ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Masonry-like CSS Grid -->
        <div class="row g-3" id="gallery">
            <?php if (count($media) > 0): ?>
                <?php foreach ($media as $row): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="position-relative gallery-item h-100 rounded-3 overflow-hidden shadow-sm" style="cursor: pointer;">
                        <img src="/assets/uploads/gallery/<?php echo htmlspecialchars($row['image']); ?>" class="img-fluid w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <div class="gallery-overlay d-flex align-items-center justify-content-center flex-column">
                            <i class="fas fa-expand-alt text-white fa-2x mb-2"></i>
                            <?php if($row['title']): ?>
                            <span class="text-white fw-bold small text-center px-3"><?php echo htmlspecialchars($row['title']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">No images uploaded yet.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-lg">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link border-0 shadow-sm rounded-circle mx-1" href="?page=<?php echo $page - 1; ?>"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link border-0 shadow-sm rounded-circle mx-1" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link border-0 shadow-sm rounded-circle mx-1" href="?page=<?php echo $page + 1; ?>"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<style>
.gallery-item { overflow: hidden; height: 300px; transition: all 0.3s; }
.gallery-item:hover { transform: scale(1.02); z-index: 10; cursor: pointer; }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
