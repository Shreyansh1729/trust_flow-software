<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$query = $_GET['q'] ?? '';
$results = [];

if ($query) {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Search Projects
    $stmt1 = $conn->prepare("SELECT id, title, description, 'Project' as type, 'projects.php' as link FROM projects WHERE title LIKE :q OR description LIKE :q");
    $stmt1->execute(['q' => "%$query%"]);
    $projects = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Search Blogs
    $stmt2 = $conn->prepare("SELECT id, title, content as description, 'Blog Post' as type, 'blog-read.php' as link FROM blogs WHERE title LIKE :q OR content LIKE :q");
    $stmt2->execute(['q' => "%$query%"]);
    $blogs = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $results = array_merge($projects, $blogs);
}
?>

<div class="bg-primary-dark text-white py-5">
    <div class="container py-4 text-center">
        <h1 class="fw-bold">Search Results</h1>
        <p class="lead mb-0">Showing results for: "<?php echo htmlspecialchars($query); ?>"</p>
    </div>
</div>

<section class="py-5 bg-white" style="min-height: 60vh;">
    <div class="container">
        
        <!-- Search Bar -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <form action="search.php" method="GET" class="input-group shadow-sm">
                    <input type="text" class="form-control border-0 py-3 ps-4" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search projects, blogs, etc...">
                    <button class="btn btn-primary-custom px-4" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <!-- Results -->
        <div class="row g-4 justify-content-center">
            <div class="col-md-8">
                <?php if (empty($query)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-keyboard fa-3x mb-3 opacity-25"></i>
                        <p>Type above to start searching.</p>
                    </div>
                <?php elseif (empty($results)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="far fa-folder-open fa-3x mb-3 opacity-25"></i>
                        <p>No results found for "<?php echo htmlspecialchars($query); ?>". Try different keywords.</p>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4"><?php echo count($results); ?> matches found.</p>
                    
                    <?php foreach ($results as $item): ?>
                    <div class="card border-0 shadow-sm mb-3 hover-lift transition-base">
                        <div class="card-body p-4">
                            <span class="badge bg-light text-secondary mb-2"><?php echo $item['type']; ?></span>
                            <h4 class="card-title fw-bold">
                                <a href="<?php echo $item['link'] . ($item['type'] == 'Project' ? '?id=' : '?id=') . $item['id']; ?>" class="text-navy text-decoration-none stretched-link">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </a>
                            </h4>
                            <p class="card-text text-muted">
                                <?php 
                                    $desc = strip_tags($item['description']);
                                    echo strlen($desc) > 150 ? substr($desc, 0, 150) . '...' : $desc; 
                                ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
