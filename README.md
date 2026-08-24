# R-DEIP — Rwanda Digital Estate & Inheritance Platform

## Phase 1: Foundation & Authentication

R-DEIP is a professional digital platform designed for secure estate and inheritance management in Rwanda. Phase 1 establishes the core foundation including authentication, authorization, user management, and audit logging.

---

## Installation (XAMPP)

### Prerequisites

- XAMPP with Apache and MySQL 8+
- A web browser

### Step-by-Step Setup

1. **Start XAMPP Services**
   - Start Apache from the XAMPP Control Panel
   - Start MySQL from the XAMPP Control Panel

2. **Copy the Project**
   - Copy the entire `rdeip` folder to `C:\xampp\htdocs\rdeip` (Windows)
   - Or `/opt/lampp/htdocs/rdeip` (Linux)

3. **Create the Database**
   - Open phpMyAdmin at `http://localhost/phpmyadmin`
   - Click "New" to create a new database
   - Name it `rdeip`
   - Set collation to `utf8mb4_unicode_ci`
   - Click "Create"

4. **Import the SQL File**
   - Select the `rdeip` database
   - Click the "Import" tab
   - Browse to `database/rdeip.sql`
   - Click "Go" to import
   - This creates all tables and inserts demo data

5. **Configure Environment**
   - Open `rdeip/.env`
   - Verify the database settings match your XAMPP configuration:
     ```
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_NAME=rdeip
     DB_USER=root
     DB_PASS=
     ```
   - The default XAMPP MySQL has no password, so `DB_PASS=` is correct

6. **Set Up Apache**
   - Ensure Apache's `mod_rewrite` is enabled (it is by default in XAMPP)
   - The `.htaccess` files handle URL rewriting automatically

7. **Access the Application**
   - Open your browser
   - Navigate to `http://localhost/rdeip`
   - The homepage should load

---

## Demo Accounts

All demo accounts use the password: **Password@123**

| Role | Email | Description |
|------|-------|-------------|
| Super Administrator | superadmin@rdeip.gov.rw | Full system access |
| Administrator | admin@rdeip.gov.rw | User management, audit logs |
| Government Officer | officer@rdeip.gov.rw | Limited dashboard access |
| Citizen | citizen@rdeip.gov.rw | Profile access only |

---

## Project Structure

```
rdeip/
│
├── app/
│   ├── controllers/     # Auth, Dashboard, User, Profile, Audit, Home
│   ├── core/           # Database, Router, Auth, CSRF, Validator, etc.
│   ├── helpers/        # Global helper functions
│   ├── middleware/      # Auth, Guest, RBAC middleware
│   └── views/          # All PHP templates
│       ├── layouts/       # app.php (dashboard), auth.php
│       ├── auth/          # login, register, forgot/reset password
│       ├── dashboard/     # Role-specific dashboards
│       ├── users/         # User management CRUD
│       ├── audit/         # Audit log viewer
│       ├── profile/       # User profile
│       ├── home/          # Public pages
│       ├── errors/        # 403, 404, 500 error pages
│       └── partials/      # Shared components
│
├── config/
│   └── config.php      # Application configuration
│
├── database/
│   ├── rdeip.sql       # Full schema + seed data
│   └── seeders/        # Database seeders
│
├── public/
│   ├── index.php       # Application entry point
│   └── assets/
│       ├── css/app.css    # Complete stylesheet
│       ├── js/app.js      # Vanilla JavaScript
│       └── images/        # Placeholders & future assets
│
├── routes/
│   └── web.php          # All route definitions
│
├── lang/                # Translations (en, rw, fr)
├── storage/             # Logs, cache, backups
├── .env                 # Environment configuration
├── .env.example         # Environment template
├── .htaccess            # Root URL rewriting & protection
└── README.md            # This file
```

---

## Phase 1 Features

### Authentication
- Login with email and password
- User registration with validation
- Password reset (forgot password flow)
- Password visibility toggle
- Password strength validation
- Remember-me functionality
- Session timeout (1 hour inactivity)
- Login attempt lockout (5 attempts, 15-minute lockout)

### Authorization (RBAC)
- 4 roles: Super Admin, Administrator, Government Officer, Citizen
- 21 permissions across 7 modules
- Many-to-many user-role and role-permission relationships
- Server-side permission checking on every protected route
- Role-based dashboard views
- Middleware-based route protection

### User Management
- View, create, edit, suspend, and activate users
- Role assignment
- Search and filter by status
- Pagination (15 per page)

### Security
- PDO prepared statements (SQL injection protection)
- CSRF token verification on all POST requests
- HTML output escaping (XSS protection)
- Secure session configuration
- Account lockout after failed attempts
- Audit logging for all administrative actions
- Protected directories via .htaccess
- No sensitive data exposure in production mode

### Audit Trail
- Comprehensive logging of all administrative actions
- Filterable by module, action, and date range
- Paginated log viewing

### UI/UX
- Professional Rwandan government-inspired design
- Responsive layout (320px to 1440px+)
- Mobile navigation
- Role-based dashboards with real database data
- Flash messages and toast notifications
- Empty state handling (no fake data)
- Image fallback system
- Accessible (semantic HTML, focus states, ARIA)

### Multi-Language Foundation
- English (default), Kinyarwanda, French translation files
- Translation helper function `__()`

---

## Future Phases

| Phase | Module | Status |
|-------|--------|--------|
| 1 | Foundation & Authentication | **Complete** |
| 2 | Citizen / Person Management | Planned |
| 3 | Death Registration | Planned |
| 4 | Estate Management | Planned |
| 5 | Family & Beneficiaries | Planned |
| 6 | Digital Will | Planned |
| 7 | Court & Legal Workflow | Planned |
| 8 | Government Administration | Planned |
| 9 | AI, Fraud Detection & Analytics | Planned |

---

## Technology Stack

- **Backend:** PHP 8+ (Object-Oriented, MVC)
- **Database:** MySQL 8+ (PDO)
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Server:** Apache (XAMPP)
- **No frameworks required** — clean, dependency-free code

---

## Security Notes

- All passwords are hashed with bcrypt (cost 12)
- CSRF protection is enforced on all non-GET requests
- SQL injection is prevented via PDO prepared statements
- XSS is prevented via output escaping with `e()`
- Session cookies are set with HttpOnly and SameSite=Lax
- Production mode hides all PHP errors from users
- Sensitive directories (config, database, storage, app) are protected by .htaccess

---

## License

Proprietary — Government of Rwanda
