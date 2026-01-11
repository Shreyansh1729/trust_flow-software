<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$message_sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = cleanInput($_POST['name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $phone = cleanInput($_POST['phone'] ?? '');
    $skills = cleanInput($_POST['skills'] ?? '');
    $availability = cleanInput($_POST['availability'] ?? ''); // e.g., Weekends, Weekdays

    if($name && $email) {
        $db = new Database();
        $conn = $db->getConnection();

        try {
            // Ensure table exists (Fixed syntax)
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

            $stmt = $conn->prepare("INSERT INTO volunteers (name, email, phone, skills, availability) VALUES (:name, :email, :phone, :skills, :availability)");
            $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'skills' => $skills, 'availability' => $availability]);
            $message_sent = true;
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!-- Hero -->
<div class="position-relative py-5" style="background-image: url('/assets/img/cause-education.png'); background-size: cover; background-position: center;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(15, 23, 42, 0.8);"></div>
    <div class="container position-relative z-1 py-5 text-center text-white">
        <h1 class="display-3 fw-bold mb-3 text-white">Join Our Mission</h1>
        <p class="lead mb-4 text-white">Become a volunteer and help us create lasting impact in the communities we serve.</p>
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Info -->
            <div class="col-lg-5">
                <h3 class="fw-bold text-navy mb-4">Why Volunteer?</h3>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white p-3 rounded-circle shadow-sm text-orange">
                            <i class="fas fa-hand-holding-heart fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold">Make a Real Impact</h5>
                        <p class="text-muted">Directly contribute to projects that change lives.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white p-3 rounded-circle shadow-sm text-orange">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold">Join a Community</h5>
                        <p class="text-muted">Connect with like-minded individuals passionate about change.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white p-3 rounded-circle shadow-sm text-orange">
                            <i class="fas fa-certificate fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold">Gain Experience</h5>
                        <p class="text-muted">Develop new skills and get a certificate of volunteering.</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-premium p-4 p-md-5 rounded-4">
                    <?php if ($message_sent): ?>
                        <div class="text-center py-5">
                            <div class="mb-4 text-success animate__animated animate__bounceIn">
                                <i class="fas fa-check-circle fa-5x"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Application Received!</h3>
                            <p class="text-muted mb-4">Thank you for your interest. Our coordination team will review your application and get back to you within 3-5 working days.</p>
                            <a href="index.php" class="btn btn-outline-primary rounded-pill px-4">Return Home</a>
                        </div>
                    <?php else: ?>
                        <h3 class="fw-bold mb-4 text-navy">Volunteer Application</h3>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control bg-light border-0" id="name" name="name" placeholder="Name" required>
                                        <label for="name">Full Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control bg-light border-0" id="email" name="email" placeholder="Email" required>
                                        <label for="email">Email Address</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control bg-light border-0" id="phone" name="phone" placeholder="Phone">
                                <label for="phone">Phone Number</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-navy small text-uppercase">Availability</label>
                                <select class="form-select bg-light border-0 py-3" name="availability">
                                    <option value="Weekends">Weekends Only</option>
                                    <option value="Weekdays">Weekdays Only</option>
                                    <option value="Flexible">Flexible / Any Time</option>
                                </select>
                            </div>

                            <div class="form-floating mb-4">
                                <textarea class="form-control bg-light border-0" placeholder="Skills" id="skills" name="skills" style="height: 120px"></textarea>
                                <label for="skills">Skills & Interests (Teaching, Medical, etc.)</label>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold shadow-lg">
                                Submit Application <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
