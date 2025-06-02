# USPEnrol

## Project Overview

USPEnrol is a web application designed to streamline enrollment services for students and administrators at the University of the South Pacific (USP). It provides functionalities for students to manage their course enrollments and submit various applications (e.g., grade recheck), while enabling administrators to manage student data, courses, and process applications.

## Features

**For Students:**
* **Secure Registration:** Students can register for an account, with their email automatically generated based on their student ID, and their password set to their student ID for initial login.
* **Personalized Dashboard:** Access to relevant information and services after logging in.
* **Course Enrollment:** Ability to view available courses and potentially enroll (if implemented).
* **Online Application Forms:** Submit various applications, such as the "Application for Reconsideration of Course Grade", with pre-filled personal details.
* **Email Notifications:** Receive email confirmations and updates (e.g., upon registration, application status).

**For Administrators:**
* **User Management:** (Assumed) Ability to manage student and administrator accounts.
* **Application Review:** (Assumed) Process and manage student applications.
* **Grade Management:** (Assumed) Functionality to manage student grades.

## Technologies Used

* **Framework:** Laravel 12
* **PHP Version:** 8.4.1
* **Database:** MySQL
* **Frontend:** Blade Templates with Livewire (for dynamic components)
* **Styling/UI:** Flux components (implies Tailwind CSS)
* **Email Services:** Gmail SMTP
* **PDF Generation:** Integration for generating PDF documents (e.g., application forms, receipts).
* **Roles & Permissions:** Spatie Laravel Permission Package (implied by roles mention).

## Prerequisites

Before running this project, ensure you have the following installed on your system:

* **PHP:** Version 8.4.1 or higher
* **Composer:** Latest version
* **Node.js & npm (or Yarn):** Latest LTS version
* **MySQL:** Database server
* **Web Server:** Nginx or Apache (or use Laravel's built-in `composer run dev`)

## Installation Guide

Follow these steps to get the USPEnrol project up and running on your local machine:

1.  **Clone the Repository:**
    ```bash
    git clone <repo-url>
    cd USPEnrol
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

3.  **Create and Configure Environment File:**
    ```bash
    cp .env.example .env
    ```
    Open the `.env` file and configure your database connection and mail settings:
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=uspenrol_db  # Choose your database name
    DB_USERNAME=root         # Your MySQL username
    DB_PASSWORD=             # Your MySQL password

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=your_gmail_username@gmail.com # Your Gmail address
    MAIL_PASSWORD=your_gmail_app_password      # Your Gmail App Password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="<span class="math-inline">\{MAIL\_USERNAME\}"
MAIL\_FROM\_NAME\="</span>{APP_NAME}"
    ```
    * **Note on Gmail App Password:** If you're using Gmail SMTP, you'll likely need to generate an "App password" from your Google Account security settings, as regular passwords often don't work for SMTP authentication anymore.

4.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

5.  **Run Database Migrations and Seeders:**
    This will create the necessary tables and populate them with initial data, including the admin user and student data.
    ```bash
    php artisan migrate --seed
    ```
    * **Important:** This command assumes your `DatabaseSeeder.php` calls the necessary seeders for `roles`, `admin user`, and `student data`.

6.  **Install Node.js Dependencies and Compile Assets:**
    ```bash
    npm install
    npm run dev # For development (or npm run build for production)
    ```

## How to Run the Application

Once all installation steps are complete, you can run the application:

1.  **Start the Laravel Development Server:**
    ```bash
    composer run dev
    ```
    This will typically start the server at `http://127.0.0.1:8000`.

2.  **Access the Application:**
    Open your web browser and navigate to the URL provided by `composer run dev` (e.g., `http://localhost:8000`).

## User Roles and Credentials

* **Administrator:**
    * **Email:** `admin@admin.com`
    * **Password:** `admin`
    * **Purpose:** Manages the system, users, courses, and applications.

* **Student:**
    * **Registration:** New student accounts are created via the registration page. The email is automatically `[student_id]@student.usp.ac.fj`, and the password is the `student_id`.
    * **Credentials:** Check your `StudentSeeder` file for details on any pre-seeded student accounts or generate new ones through the registration form.
    * **Purpose:** Accesses enrollment services and various application forms.

## Contribution

(Optional)
If you wish to contribute to this project, please follow standard Gitflow practices:
1.  Fork the repository.
2.  Create a new branch for your feature or bug fix.
3.  Commit your changes.
4.  Push to your fork and submit a pull request.

---

Feel free to customize any section further based on your specific implementation details!