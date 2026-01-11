<?php
// public/contact.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$message_sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = cleanInput($_POST['name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $subject = cleanInput($_POST['subject'] ?? '');
    $message = cleanInput($_POST['message'] ?? '');

    if($name && $email && $subject && $message) {
        $db = new Database();
        $conn = $db->getConnection();

        // Lazy Create Table
        $conn->exec("CREATE TABLE IF NOT EXISTS inquiries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            email VARCHAR(100),
            subject VARCHAR(200),
            message TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        try {
            $stmt = $conn->prepare("INSERT INTO inquiries (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
            $stmt->execute(['name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message]);
            $message_sent = true;
        } catch(PDOException $e) {
            $error = "Something went wrong.";
        }
    }
}
?>

<!-- Custom CSS for Button Animation -->
<style>
.btn-send-message {
    background: linear-gradient(45deg, #f97316, #ea580c);
    border: none;
    color: white;
    font-weight: bold;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(234, 88, 12, 0.4);
}
.btn-send-message:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(234, 88, 12, 0.6);
    background: linear-gradient(45deg, #ea580c, #c2410c);
}
.btn-send-message:active {
    transform: translateY(1px);
}
</style>

<!-- Page Header -->
<div class="bg-primary-dark text-white py-5">
    <div class="container py-4 text-center">
        <h1 class="display-4 fw-bold">Contact Us</h1>
        <p class="lead mb-0">We’d love to hear from you.</p>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Contact Info & Map -->
            <div class="col-lg-5">
                <div class="mb-5">
                    <h3 class="fw-bold mb-4">Get in Touch</h3>
                    <p class="text-muted mb-4">Have questions about our projects or want to volunteer? Reach out to us directly or fill carefully out the form.</p>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3 text-accent"><i class="fas fa-map-marker-alt fa-lg"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Visit Us</h6>
                            <p class="text-muted small">123 Charity Lane, Goodville, GV 54321</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3 text-accent"><i class="fas fa-envelope fa-lg"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Email Us</h6>
                            <p class="text-muted small">info@trustflow.org</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3 text-accent"><i class="fas fa-phone fa-lg"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Call Us</h6>
                            <p class="text-muted small">+1 (555) 123-4567</p>
                        </div>
                    </div>
                </div>
                
                <!-- Google Map Placeholder -->
                <div class="map-container rounded-3 overflow-hidden shadow-soft" style="height: 300px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3506.2233913121415!2d77.4051603706222!3d23.250576529060286!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x397c42e439266b61%3A0x521ddbbd5c9fa0b4!2sMadhya%20Pradesh%20Nagar%2C%20Bhopal%2C%20Madhya%20Pradesh!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-soft p-4 p-md-5">
                    
                    <?php if ($message_sent): ?>
                        <div class="alert alert-success shadow-soft" role="alert">
                            <h4 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Message Sent!</h4>
                            <p>Thank you for reaching out. We will get back to you shortly.</p>
                        </div>
                    <?php else: ?>
                    
                    <form action="" method="POST" class="contact-form needs-validation" novalidate>
                        <h3 class="fw-bold mb-4">Send a Message</h3>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                                    <label for="name">Your Name</label>
                                    <div class="invalid-feedback">Please provide your name.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                    <label for="email">Email Address</label>
                                    <div class="invalid-feedback">Please provide a valid email.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                            <label for="subject">Subject</label>
                            <div class="invalid-feedback">Please provide a subject.</div>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 150px" required></textarea>
                            <label for="message">Message</label>
                            <div class="invalid-feedback">Please write a message.</div>
                        </div>
                        
                        <button class="btn btn-gradient w-100 py-3 rounded-pill fw-bold shadow-soft hover-lift btn-send-message" type="submit">
                            Send Message
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
