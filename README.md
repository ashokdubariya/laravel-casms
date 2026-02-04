# Client Approval & Sign-Off Management System

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-12.49.0-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple)
![Tailwind](https://img.shields.io/badge/Tailwind-4.1-38bdf8)
![Alpine](https://img.shields.io/badge/Alpine.js-3.15-8bc0d0)
![License](https://img.shields.io/badge/license-Proprietary-green)

A professional, self-hosted Laravel application for managing client approvals and sign-offs with version history, secure token-based access, and comprehensive audit trails.

---

## 📸 Application Screenshots

<table>
<tr>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/01-dashboard.png" width="450" /></td>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/02-client-management.png" width="450" /></td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/03-view-client.png" width="450" /></td>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/04-approval-requests.png" width="450" /></td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/05-view-approval-requests.png" width="450" /></td>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/06-activity-history.png" width="450" /></td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/07-email-templates.png" width="450" /></td>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/08-user-management.png" width="450" /></td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/09-user-profile-management.png" width="450" /></td>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/10-role-management.png" width="450" /></td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/11-role-details.png" width="450" /></td>
<td><img src="https://raw.githubusercontent.com/ashokdubariya/laravel-casms/main/screenshots/12-edit-role.png" width="450" /></td>
</tr>
</table>

---

## Table of Contents

- [What This Is](#what-this-is)
- [What This Is Not](#what-this-is-not)
- [Key Features](#key-features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [Testing](#testing)
- [Security](#security)
- [Support](#support)
- [License](#license)

---

## What This Is

A **focused approval tracking system** designed for:
- Agencies managing client approvals
- Freelancers tracking project sign-offs
- Consultants requiring verifiable acceptance records
- Service providers needing audit trails

This system centralizes approval workflows, eliminates email-based approvals, and provides legally useful proof of client decisions.

---

## What This Is Not

This is **NOT**:
- A project management system
- A CRM platform
- An invoicing or payment solution
- A time tracking tool
- A messaging or collaboration suite
- A task management system

If you need those features, this product is not for you. This application is **deliberately focused** on approval workflows only.

---

## Key Features

### Core Approval Management
- Create approval requests with title, description, and version
- Attach files, images, and URLs
- Add internal team notes (never shown to clients)
- Track approval status: Pending, Approved, Rejected

### Secure Client Access
- Token-based approval links (no client login required)
- Time-limited expiry (default: 7 days)
- Single-use tokens prevent reuse
- IP address and user agent tracking

### Approval Workflow
- Clients approve with one click
- Clients reject with mandatory feedback
- Email notifications to team and clients
- Reminder emails for pending approvals

### Audit & History
- Immutable activity timeline
- Complete approval lifecycle tracking
- PDF export of approval proof
- Search and filter approvals

### Team Management
- Admin and Team Member roles
- Active/inactive user management
- Permission-based access control

---

## Requirements

### Server Requirements
- **PHP**: 8.2 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Web Server**: Apache or Nginx
- **Composer**: 2.x
- **Node.js**: 18.x or higher (for asset compilation, optional)

### PHP Extensions
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD or Imagick (for PDF generation)

---

## Installation

### Step 1: Upload Files
Extract the ZIP file to your web server directory:
```bash
/var/www/html/your-domain/
```

### Step 2: Install Dependencies
```bash
cd /var/www/html/your-domain
composer install --no-dev --optimize-autoloader
```

### Step 3: Environment Configuration
Copy the example environment file:
```bash
cp .env.example .env
```

Edit `.env` and configure:
```env
APP_NAME="Client Approval System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Run Migrations
```bash
php artisan migrate --force
```

### Step 6: Create Storage Symlink
```bash
php artisan storage:link
```

### Step 7: Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 8: Create Admin User
```bash
php artisan tinker
```

Then run:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('secure-password'),
    'role' => 'admin',
    'is_active' => true,
]);
```

### Step 9: Configure Web Server

**For Apache (.htaccess included)**:
Ensure `mod_rewrite` is enabled.

**For Nginx**:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/your-domain/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Step 10: Access the Application
Navigate to: `https://your-domain.com`

Login with the admin credentials created in Step 8.

---

## Configuration

### Email Configuration
Configure email in `.env`:

**For SMTP**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

**For Mailgun, Postmark, SES**: See [Laravel Mail Documentation](https://laravel.com/docs/11.x/mail)

### Approval Settings
Edit `config/approval.php`:

```php
'token' => [
    'expiry_days' => 7, // Token expiry in days
],

'attachments' => [
    'max_count' => 10, // Maximum attachments per approval
    'max_file_size' => 20480, // Max file size in KB (20MB)
],
```

Or use `.env`:
```env
APPROVAL_TOKEN_EXPIRY_DAYS=7
APPROVAL_MAX_ATTACHMENTS=10
APPROVAL_MAX_FILE_SIZE=20480
```

### PDF Configuration
PDF generation uses DomPDF. Customize in `config/approval.php`:

```php
'pdf' => [
    'orientation' => 'portrait',
    'paper_size' => 'a4',
    'company_name' => env('APP_NAME', 'Client Approval System'),
],
```

---

## Usage Guide

### Creating an Approval Request

1. **Navigate to Dashboard**: Click "New Approval Request"
2. **Fill in Details**:
   - **Title**: Brief description (required)
   - **Description**: Detailed context (optional)
   - **Version**: e.g., v1, v2.1 (optional)
   - **Client Name**: Full name (required)
   - **Client Email**: Valid email (required)
   - **Internal Notes**: Team-only notes (optional)
3. **Add Attachments**: Upload images, documents, or add URLs
4. **Submit**: System generates secure approval link

### Sending Approval to Client

After creating an approval:
1. Copy the **secure approval link**
2. Email it to the client (or use built-in reminder feature)
3. Link expires in 7 days by default
4. Client can approve or reject (once only)

### Client Approval Process

Clients receive a link and can:
1. **View** approval details and attachments
2. **Approve** with one click
3. **Reject** with mandatory feedback
4. No login or account required
5. Secure, single-use token

### Managing Approvals

- **Filter**: By status, client email, or date range
- **Edit**: Pending approvals only (completed are immutable)
- **Send Reminder**: Nudge clients for pending approvals
- **Regenerate Link**: Create new token if needed
- **View History**: See complete audit trail
- **Download PDF**: Export approval proof

### Admin Features

Admins can:
- Manage all users (create, deactivate)
- Delete any approval request
- Access all approvals (not just their own)

---

## Testing

Run the complete test suite:

```bash
php artisan test
```

### Test Coverage

- **Feature Tests**: Full workflow testing
- **Unit Tests**: Model logic and helpers
- **Validation Tests**: Input sanitization
- **Security Tests**: Token security, authorization, SQL injection prevention

---

## Security

### Token Security
- Cryptographically secure random generation (64 characters)
- SHA-256 hashing with entropy
- Single-use enforcement
- Time-based expiry
- IP and user agent tracking

### Authorization
- Laravel Policy-based authorization
- Role-based access control (Admin, Team Member)
- Active user enforcement
- Owner-based approval access

### Data Protection
- Immutable audit history
- Internal notes isolation
- SQL injection prevention
- XSS protection via Blade templating
- CSRF token validation

### Best Practices
- HTTPS enforcement recommended
- Strong password requirements
- Session security
- Input validation and sanitization

---

## Support

### Common Issues

**Issue**: Emails not sending
- **Solution**: Check `.env` mail configuration
- Verify SMTP credentials
- Check firewall/port blocking

**Issue**: File upload fails
- **Solution**: Check `upload_max_filesize` in `php.ini`
- Verify storage permissions: `chmod 775 storage`

**Issue**: Token link shows "expired"
- **Solution**: Token expired (default 7 days)
- Regenerate new link from approval details page

**Issue**: 500 error after installation
- **Solution**: Check storage permissions
- Run `php artisan config:cache`
- Check error logs: `storage/logs/laravel.log`

---

## Version History

### v1.0.0 (January 2026)
- Initial release
- Core approval workflow
- Token-based client access
- PDF export
- Audit history
- Email notifications
- Comprehensive test coverage

---

## Credits

Built with:
- [Laravel](https://laravel.com/) - PHP Framework
- [Tailwind CSS](https://tailwindcss.com/) - Styling
- [Alpine.js](https://alpinejs.dev/) - Interactivity
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) - PDF Generation

---

**Thank you for choosing Client Approval & Sign-Off Management System!**