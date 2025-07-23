<div align="center">
  <img src="logo/image.png" alt="Atom-hospital" width="250" height="250">
  <h1>Atom Hospital Website</h1>
</div>

## Overview
Atom Hospital is a web-based hospital management system designed to streamline patient management, staff coordination, and administrative tasks. The system provides interfaces for various roles including doctors, nurses, laboratory staff, receptionists, assistants, and administrators.

## Features
- **User Authentication**: Secure login system with role-based access control.
- **Admin Panel**: Manage users, add/search users, and manage patients.
- **Patient Management**: Register, search, update, and archive patient records.
- **Role-based Interfaces**: Custom dashboards for Admin, Doctor, Nurse, Laboratory, Receptionist, and Doctor Assistant.
- **Medical Records**: Upload and manage medical files and test results.
- **Charts & Analytics**: Visualize staff and patient statistics.

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript, jQuery
- **Backend**: PHP
- **Database**: SQLite
- **Libraries**: Chart.js, SweetAlert2, Font Awesome

## Directory Structure
- **admin/**: Admin dashboard, user management, and analytics
- **bridge/**: Backend PHP endpoints for all roles
- **database/**: SQLite database files and database setup script
- **js/**: JavaScript files for all user interfaces
- **logo/**: Branding assets
- **medical/**: Medical staff interface and file upload
- **style/**: CSS files for all interfaces
- **workspace/**: HTML files for each user role

## Setup Instructions
1. **Clone the repository**
2. **Install Requirements**
   - Ensure you have a web server with PHP and SQLite support (e.g., XAMPP, WAMP, or LAMP).
3. **Database Initialization**
   - The system will auto-create necessary tables on first run.
   - If you want to manually create an admin user, use the Admin Panel or insert directly into `users.db`.
4. **Set Default Admin User**
   - The system allows login with the following credentials by default:
     - **Username:** `admin`
     - **Password:** `admin`
   - This is hardcoded as a fallback in `bridge/login.php` and `bridge/cheker.php`.
   - **Change the admin password after first login for security!**
5. **Run the Application**
   - Place the project files in your web server's root directory.
   - Access the site via your browser (e.g., `http://localhost/Atom/index.html`).

## User Roles & Interfaces
- **Admin:** Full access to user and patient management, analytics, and archiving.
- **Doctor:** View, manage, and archive their patients; order medical tests.
- **Nurse:** View and manage all patients; order medical tests.
- **Laboratory:** View test orders, upload results, and mark tests as finished.
- **Receptionist:** Register new patients, update, search, and archive records.
- **Doctor Assistant:** View and manage patients assigned to their doctor.

## Authentication
- Token-based authentication. On login, a token is stored in `localStorage` and used for subsequent requests.
- Role-based redirection after login.

## Database Files
- `users.db`: User accounts and doctor-assistant relationships
- `clinic.db`: Patient records
- `laboratory.db`: Medical test records
- `archive.db`: Archived patient records

## Security Notes
- The default admin credentials are for initial setup only. Change them immediately after first login.
- All user passwords are stored in plaintext and hashed form for compatibility. Update to use only hashed passwords for production.

## Contributing
Contributions are welcome! Please submit a Pull Request.

