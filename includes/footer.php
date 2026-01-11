<!-- Premium Footer -->
    <footer class="bg-navy text-white">
        <div class="container py-5">
            <div class="row g-4 py-4">
                <!-- Column 1: Brand & Social -->
                <div class="col-lg-4 col-md-6">
                    <h3 class="fw-bold mb-3 text-white">
                        <i class="fas fa-hands-holding-heart me-2 text-orange"></i>TrustFlow
                    </h3>
                    <p class="text-gray-300 mb-4" style="color: var(--gray-300);">
                        Empowering communities through transparent giving. Every donation creates a ripple of positive change.
                    </p>
                    <div class="social-icons d-flex gap-3">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Explore -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3 text-white">Explore</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="/public/about.php">About Us</a></li>
                        <li><a href="/public/about.php#team">Our Team</a></li>
                        <li><a href="/public/impact.php">Impact</a></li>
                        <li><a href="/public/blog.php">Blog</a></li>
                        <li><a href="/public/contact.php">Contact</a></li>
                    </ul>
                </div>

                <!-- Column 3: Quick Action -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3 text-white">Action</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="/public/projects.php">Our Projects</a></li>
                        <li><a href="/public/donate.php">Donate Now</a></li>
                        <li><a href="/public/volunteer.php">Volunteer</a></li>
                        <li><a href="/public/testimonials.php">Stories</a></li>
                    </ul>
                </div>

                <!-- Column 4: Newsletter -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-3 text-white">Stay Connected</h5>
                    <p class="text-gray-300 mb-3" style="color: var(--gray-300); font-size: 0.95rem;">
                        Subscribe to receive updates on our impact and upcoming projects.
                    </p>
                    <form id="newsletterForm" class="newsletter-form">
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control newsletter-input" placeholder="Your email address" required>
                            <button class="btn btn-newsletter" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <div id="newsletterFeedback" class="small mt-2" style="display: none;"></div>
                    </form>
                    <script>
                        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            const form = this;
                            const feedback = document.getElementById('newsletterFeedback');
                            const btn = form.querySelector('button');
                            const originalContent = btn.innerHTML;
                            
                            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                            btn.disabled = true;

                            fetch('/api/newsletter/subscribe.php', {
                                method: 'POST',
                                body: new FormData(form)
                            })
                            .then(response => response.json())
                            .then(data => {
                                feedback.style.display = 'block';
                                feedback.className = data.success ? 'small mt-2 text-success fw-bold' : 'small mt-2 text-danger fw-bold';
                                feedback.textContent = data.message;
                                if(data.success) form.reset();
                            })
                            .catch(err => {
                                feedback.style.display = 'block';
                                feedback.className = 'small mt-2 text-danger fw-bold';
                                feedback.textContent = 'Connection error. Please try again.';
                            })
                            .finally(() => {
                                btn.innerHTML = originalContent;
                                btn.disabled = false;
                                setTimeout(() => { feedback.style.display = 'none'; }, 5000);
                            });
                        });
                    </script>
                    <div class="mt-4">
                        <p class="text-gray-300 small mb-2" style="color: var(--gray-300);">
                            <i class="fas fa-phone me-2 text-orange"></i>+1 (555) 123-4567
                        </p>
                        <p class="text-gray-300 small mb-0" style="color: var(--gray-300);">
                            <i class="fas fa-envelope me-2 text-orange"></i>info@trustflow.org
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom py-4" style="background-color: rgba(0, 0, 0, 0.2); border-top: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p class="mb-0 text-gray-300" style="color: var(--gray-300); font-size: 0.9rem;">
                            &copy; <?php echo date('Y'); ?> TrustFlow. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <a href="/public/privacy.php" class="footer-link me-3">Privacy Policy</a>
                        <a href="/public/terms.php" class="footer-link me-3">Terms of Service</a>
                        <a href="/public/refund.php" class="footer-link me-3">Refund Policy</a>
                        <a href="/public/financials.php" class="footer-link">Financial Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Custom Styles for Footer -->
    <style>
        .bg-navy {
            background-color: var(--navy-dark);
        }
        
        .text-orange {
            color: var(--orange-vibrant);
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--white-pure);
            transition: var(--transition-base);
            font-size: 1.1rem;
        }
        
        .social-icon:hover {
            background-color: var(--orange-vibrant);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(249, 115, 22, 0.4);
            color: var(--white-pure);
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: var(--gray-300);
            text-decoration: none;
            transition: var(--transition-fast);
            font-size: 0.95rem;
        }
        
        .footer-links a:hover {
            color: var(--orange-vibrant);
            padding-left: 5px;
        }
        
        .newsletter-input {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white-pure);
            padding: 0.75rem 1rem;
            border-radius: 50px 0 0 50px;
        }
        
        .newsletter-input::placeholder {
            color: var(--gray-400);
        }
        
        .newsletter-input:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: var(--orange-vibrant);
            color: var(--white-pure);
            box-shadow: none;
        }
        
        .btn-newsletter {
            background-color: var(--orange-vibrant);
            border: none;
            color: var(--white-pure);
            padding: 0.75rem 1.5rem;
            border-radius: 0 50px 50px 0;
            transition: var(--transition-base);
        }
        
        .btn-newsletter:hover {
            background-color: var(--orange-hover);
            transform: translateX(3px);
        }
        
        .footer-link {
            color: var(--gray-300);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition-fast);
        }
        
        .footer-link:hover {
            color: var(--orange-vibrant);
        }
    </style>

    <!-- Custom JS -->
    <script src="/assets/js/main.js"></script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
