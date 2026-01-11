/**
 * TrustFlow Main JavaScript
 * Handles Premium Navbar effects, Animations, and Validations.
 */

document.addEventListener('DOMContentLoaded', function () {

    /* -------------------------------------------------------------------------- */
    /*                         Premium Navbar Scroll Effect                       */
    /* -------------------------------------------------------------------------- */
    const navbar = document.querySelector('.navbar-custom');

    // Check if we are on a page with hero section
    const hasHeroSection = document.querySelector('.hero-section') !== null;

    if (hasHeroSection && navbar) {
        // Start transparent
        navbar.classList.add('navbar-transparent');

        window.addEventListener('scroll', function () {
            if (window.scrollY > 100) {
                navbar.classList.remove('navbar-transparent');
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.add('navbar-transparent');
                navbar.classList.remove('navbar-scrolled');
            }
        });
    } else if (navbar) {
        // For pages without hero, always show scrolled state
        navbar.classList.add('navbar-scrolled');
    }

    /* -------------------------------------------------------------------------- */
    /*                           Count Up Animation                               */
    /* -------------------------------------------------------------------------- */
    const counters = document.querySelectorAll('.counter');
    const speed = 200; // The lower the slower

    const animateCounters = () => {
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText.replace(/,/g, ''); // Remove commas for calc

                // Lower inc to slow and higher to speed
                const inc = target / speed;

                if (count < target) {
                    // Add inc to count and output in counter
                    counter.innerText = Math.ceil(count + inc).toLocaleString();
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            };
            updateCount();
        });
    };

    // Trigger animation when the section is in view using Intersection Observer
    const impactSection = document.querySelector('#impact-stats');
    if (impactSection) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                animateCounters();
                observer.disconnect(); // Run only once
            }
        }, { threshold: 0.5 }); // 50% visibility

        observer.observe(impactSection);
    }

    /* -------------------------------------------------------------------------- */
    /*                           Contact Form Validation                          */
    /* -------------------------------------------------------------------------- */
    const contactForm = document.querySelector('.contact-form');

    if (contactForm) {
        contactForm.addEventListener('submit', function (event) {
            if (!contactForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            contactForm.classList.add('was-validated');
        }, false);
    }

    /* -------------------------------------------------------------------------- */
    /*                         Smooth Scroll for Anchor Links                     */
    /* -------------------------------------------------------------------------- */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    /* -------------------------------------------------------------------------- */
    /*                         Dropdown Hover Effect (Desktop)                    */
    /* -------------------------------------------------------------------------- */
    if (window.innerWidth > 991) {
        const dropdowns = document.querySelectorAll('.navbar .dropdown');

        dropdowns.forEach(dropdown => {
            const dropdownToggle = dropdown.querySelector('.dropdown-toggle');

            dropdown.addEventListener('mouseenter', function () {
                const dropdownMenu = this.querySelector('.dropdown-menu');
                if (dropdownMenu && dropdownToggle) {
                    // Use Bootstrap's Dropdown instance
                    const bsDropdown = new bootstrap.Dropdown(dropdownToggle);
                    bsDropdown.show();
                }
            });

            dropdown.addEventListener('mouseleave', function () {
                const dropdownMenu = this.querySelector('.dropdown-menu');
                if (dropdownMenu && dropdownToggle) {
                    const bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                    if (bsDropdown) {
                        bsDropdown.hide();
                    }
                }
            });
        });
    }
});
