# TrustFlow - NGO Management System

TrustFlow is a comprehensive web-based platform designed to streamline operations for Non-Governmental Organizations (NGOs). It facilitates donation tracking, volunteer management, project handling, and impactful storytelling through blogs and testimonials.

## 🚀 Features

*   **Donation Management:** Secure donation processing, history tracking, and downloadable PDF receipts with PAN support.
*   **Project Showcase:** Dynamic project listings with progress bars and status updates to keep donors informed.
*   **Volunteer System:** dedicated portal for volunteers to join and manage their engagement.
*   **Content Management:** Built-in blog and media gallery to share success stories and updates.
*   **Admin Dashboard:** Centralized control panel for managing users, financials, inquiries, and settings.
*   **Security:** Robust authentication, CSRF protection, secure file uploads, and session hardening.
*   **Responsive Design:** Modern, mobile-friendly UI built with Bootstrap 5.

## 🛠️ Tech Stack

*   **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5, FontAwesome
*   **Backend:** PHP (Vanilla)
*   **Database:** MySQL / MariaDB
*   **Security:** PDO (Prepared Statements), CSRF Tokens, Input Sanitization, Bcrypt

## ⚙️ Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone [https://github.com/](https://github.com/Shreyansh1729/trust_flow-software.git
    cd trustflow
    ```

2.  **Configure Environment**
    *   Rename the example environment file (if available) or create a new `.env` file in the root directory.
    *   Add your database credentials:
    ```ini
    DB_HOST=127.0.0.1
    DB_NAME=trust_flow_db
    DB_USER=root
    DB_PASS=your_password
    ```

3.  **Database Setup**
    *   Create a blank database named `trust_flow_db` in your MySQL server.
    *   Run the setup script to generate tables and seed initial data:
    ```bash
    php scripts/setup_db.php
    ```

4.  **Run the Application**
    *   Start a local PHP server:
    ```bash
    php -S localhost:8000
    ```
    *   Open `http://localhost:8000` in your browser.

## 📂 Project Structure

*   `public/` - Public-facing pages (Home, About, Donate, etc.)
*   `admin/` - Administrative control panel
*   `api/` - Backend logic for form handling and AJAX requests
*   `config/` - Database and environment configuration
*   `includes/` - Reusable PHP components (Header, Footer, Functions)
*   `access/uploads/` - Storage for user-uploaded images and documents

## 🛡️ Security Note

This project follows security best practices, including:
*   **Upload Safety:** Strict validation of MIME types and extensions.
*   **Database:** Helper functions use PDO to prevent SQL injection.
*   **Auth:** Passwords are hashed using standard algorithms.
*   **CSRF:** Forms are protected against Cross-Site Request Forgery.

## 📄 License

This project is open-source and available for educational and non-commercial use.
