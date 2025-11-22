# YoPrint - CSV Upload and Management System

A Laravel-based CSV file upload and management system with background processing, admin dashboard, and role-based access control.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Usage](#usage)
- [System Architecture](#system-architecture)
- [Admin Features](#admin-features)
- [Screenshots](#screenshots)
- [Troubleshooting](#troubleshooting)
- [Commands Reference](#commands-reference)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Overview

YoPrint is a comprehensive CSV file management system built with Laravel 11 that allows users to upload CSV files for background processing. The system features role-based access control, with separate interfaces for regular users and administrators.

### Key Highlights

- **Background Processing**: CSV files are processed asynchronously using Laravel's queue system
- **UPSERT Logic**: Intelligent data insertion/update based on unique keys
- **UTF-8 Cleaning**: Automatic removal of non-UTF-8 characters from CSV data
- **Idempotent Uploads**: Same file can be uploaded multiple times without creating duplicates
- **Admin Dashboard**: Comprehensive monitoring and management interface
- **Role-Based Access**: Separate permissions for users and administrators

## ✨ Features

### User Features

- ✅ CSV file upload with validation (max 10MB)
- ✅ Real-time upload status tracking (Pending, Processing, Completed, Failed)
- ✅ File management dashboard with filtering options
- ✅ Download processed files
- ✅ Delete uploaded files
- ✅ Profile management (update name, email, password)

### Admin Features

- 👨‍💼 Processing dashboard with statistics
- 📊 Total uploads, pending, processing, completed, failed counts
- 🔄 Reprocess failed uploads
- ✅ Mark uploads as completed manually
- 📁 View detailed processing information
- 🗃️ Browse all CSV data records
- 🔍 Search functionality for CSV data
- 🗑️ Delete uploads and data

### Technical Features

- 🔐 Authentication system with login/register
- 🎨 Modern UI with Hyper Bootstrap template
- 🌓 Gradient header design with YoPrint branding
- 📱 Responsive design
- 🔄 Background job processing with retry mechanism
- 📈 Processing statistics and error tracking
- 🛡️ Middleware-based authorization
- 🔧 UTF-8 character cleaning
- 💾 UPSERT database operations

## 🛠️ Tech Stack

- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Database**: MySQL 8.0+
- **Frontend**: Bootstrap 5 (Hyper Template)
- **Queue Driver**: Database
- **Assets**: Vite
- **Icons**: Material Design Icons, Unicons

## 📦 Installation

### Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (for asset compilation)
- Git

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/yocsv.git
cd yocsv
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit your `.env` file with your database credentials:

```env
APP_NAME=YoPrint
APP_ENV=local
APP_KEY=base64:your-generated-key-here
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yocsv
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

QUEUE_CONNECTION=database
```

### Step 5: Create Database

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE yocsv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

This will create the following tables:
- `users` - User accounts with admin flag
- `csv_uploads` - CSV file tracking and processing status
- `csv_data` - Parsed CSV data records
- `jobs` - Queue jobs table
- `failed_jobs` - Failed job tracking
- `password_reset_tokens` - Password reset tokens
- `sessions` - User sessions

### Step 7: Create Storage Link

```bash
php artisan storage:link
```

### Step 8: Compile Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### Step 9: Create Admin User

Create your first admin user by running:

```bash
php artisan tinker
```

Then in the tinker console:

```php
$user = new App\Models\User();
$user->name = 'Admin User';
$user->email = 'admin@yoprint.com';
$user->password = bcrypt('password');
$user->is_admin = true;
$user->save();
exit
```

### Step 10: Start Queue Worker

**⚠️ IMPORTANT**: In a separate terminal, start the queue worker:

```bash
php artisan queue:work --tries=3 --timeout=300
```

> **Keep this running to process CSV files in the background**

### Step 11: Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## ⚙️ Configuration

### Queue Configuration

The application uses database-based queues. Configuration is in `config/queue.php`:

```php
'default' => env('QUEUE_CONNECTION', 'database'),
```

### File Upload Limits

Upload limits are configured in the validation rules (`app/Http/Controllers/CsvUploadController.php`):

```php
'csv_file' => 'required|file|mimetypes:text/plain,text/csv,application/csv,text/comma-separated-values,application/vnd.ms-excel|max:10240',
```

Maximum file size: **10MB** (10240 KB)

### Supported MIME Types

- `text/plain`
- `text/csv`
- `application/csv`
- `text/comma-separated-values`
- `application/vnd.ms-excel`

## 🗄️ Database Setup

### Database Schema

#### Users Table
```sql
- id (bigint, primary key)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- is_admin (boolean, default: false)
- remember_token (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### CSV Uploads Table
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users.id)
- file_name (string)
- status (enum: pending, processing, completed, failed)
- total_rows (integer, nullable)
- inserted_rows (integer, default: 0)
- updated_rows (integer, default: 0)
- error_rows (integer, default: 0)
- error_messages (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### CSV Data Table
```sql
- id (bigint, primary key)
- unique_key (string, unique, indexed)
- product_title (string, nullable)
- product_description (string, nullable)
- style (string, nullable)
- sanmar_mainframe_color (string, nullable)
- size (string, nullable)
- color_name (string, nullable)
- piece_price (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Migration Files

1. `2014_10_12_000000_create_users_table.php`
2. `2014_10_12_100000_create_password_reset_tokens_table.php`
3. `2019_08_19_000000_create_failed_jobs_table.php`
4. `2019_12_14_000001_create_personal_access_tokens_table.php`
5. `2024_01_01_000000_create_sessions_table.php`
6. `2024_01_01_000000_create_jobs_table.php`
7. `2025_11_21_132143_create_csv_uploads_table.php`
8. `2025_11_21_174907_create_csv_data_table.php`
9. `2025_11_21_175043_add_processing_fields_to_csv_uploads_table.php`
10. `2025_11_21_195240_add_is_admin_to_users_table.php`

## 📖 Usage

### For Regular Users

1. **Register/Login**
   - Navigate to `/register` to create a new account
   - Or login at `/login` if you already have an account

2. **Upload CSV File**
   - After login, you'll be redirected to `/csv`
   - Click "Choose file" and select your CSV file
   - Click "Upload File" button
   - File will be queued for background processing

3. **Monitor Upload Status**
   - View all your uploads on the dashboard
   - Filter by status: All, Pending, Processing, Completed, Failed
   - See processing statistics (total rows, inserted, updated, errors)

4. **Download/Delete Files**
   - Click download icon to download the original file
   - Click delete icon to remove the upload

5. **Profile Management**
   - Navigate to `/profile`
   - Update your name and email
   - Change your password
   - Delete your account (if needed)

### For Administrators

1. **Access Admin Dashboard**
   - Login with an admin account
   - Navigate to `/admin/csv`

2. **View Processing Dashboard**
   - See statistics for all uploads across all users
   - Monitor total uploads, pending, processing, completed, failed counts
   - View total CSV data records imported

3. **Manage Uploads**
   - View all user uploads
   - See detailed processing information
   - Reprocess failed uploads
   - Mark uploads as completed manually
   - Delete problematic uploads

4. **Browse CSV Data**
   - Navigate to `/admin/csv-data`
   - Search through all imported CSV records
   - View data in paginated table format

## 🏗️ System Architecture

### File Upload Flow

```
User uploads CSV file
       ↓
File saved to storage/app/public/csv_uploads/
       ↓
CsvUpload record created (status: pending)
       ↓
ProcessCsvFile job dispatched to queue
       ↓
Queue worker picks up job
       ↓
CsvParserService processes file
       ↓
Status updated: processing
       ↓
Parse CSV rows → Clean UTF-8 → UPSERT to database
       ↓
Status updated: completed/failed
       ↓
Statistics saved (total_rows, inserted_rows, updated_rows, error_rows)
```

### UPSERT Logic

The system uses intelligent UPSERT (Update or Insert) logic based on the `UNIQUE_KEY` column:

```php
// Check if record exists
$existing = CsvData::where('unique_key', $rowData['unique_key'])->first();

if ($existing) {
    // Update existing record
    $existing->update($rowData);
    $stats['updated']++;
} else {
    // Insert new record
    CsvData::create($rowData);
    $stats['inserted']++;
}
```

This ensures that:
- Re-uploading the same file updates existing records instead of creating duplicates
- The system is idempotent - multiple uploads of the same data produce the same result

### UTF-8 Character Cleaning

All CSV data is cleaned to remove non-UTF-8 characters before insertion:

```php
private function cleanUtf8(string $string): string
{
    // Convert encoding to UTF-8
    $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');

    // Remove control characters except newlines and tabs
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $string);

    return $string;
}
```

### Background Job Processing

Jobs are configured with:
- **Queue**: default
- **Timeout**: 300 seconds (5 minutes)
- **Tries**: 3 attempts
- **Retry After**: 310 seconds

```php
public $timeout = 300;
public $tries = 3;
```

## 🔐 Authentication & Authorization

### Middleware

The application uses two main middleware groups:

1. **auth**: Requires user to be logged in
2. **admin**: Requires user to be logged in AND have `is_admin = true`

### Admin Middleware

Located at `app/Http/Middleware/IsAdmin.php`:

```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Unauthorized action.');
    }

    return $next($request);
}
```

### Protected Routes

```php
// User routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/csv', [CsvUploadController::class, 'index'])->name('csv.index');
    Route::post('/csv', [CsvUploadController::class, 'store'])->name('csv.store');
    Route::delete('/csv/{csvUpload}', [CsvUploadController::class, 'destroy'])->name('csv.destroy');
});

// Admin routes (requires auth + admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/csv', [CsvAdminController::class, 'index'])->name('csv.index');
    Route::get('/csv/{csvUpload}', [CsvAdminController::class, 'show'])->name('csv.show');
    Route::post('/csv/{csvUpload}/reprocess', [CsvAdminController::class, 'reprocess'])->name('csv.reprocess');
    Route::post('/csv/{csvUpload}/mark-completed', [CsvAdminController::class, 'markCompleted'])->name('csv.markCompleted');
    Route::delete('/csv/{csvUpload}', [CsvAdminController::class, 'destroy'])->name('csv.destroy');
    Route::get('/csv-data', [CsvAdminController::class, 'data'])->name('csv.data');
});
```

## 🎨 Admin Features

### Processing Dashboard

Access: `/admin/csv`

**Statistics Cards:**
- Total Uploads
- Pending
- Processing
- Completed
- Failed
- Total CSV Records

**Processing Queue Table:**
- User name
- File name
- Status badge
- Upload date
- Statistics (Total/Inserted/Updated/Errors)
- Actions (View Details, Mark Completed, Reprocess, Delete)

### CSV Data Records

Access: `/admin/csv-data`

**Features:**
- Search functionality
- Paginated results
- Display all imported CSV data
- Column headers: Unique Key, Product Title, Description, Style, Color, Size, Price

### Admin Actions

#### Reprocess Upload
```php
POST /admin/csv/{csvUpload}/reprocess
```
Dispatches a new background job to reprocess the CSV file.

#### Mark as Completed
```php
POST /admin/csv/{csvUpload}/mark-completed
```
Manually marks an upload as completed (useful for stuck jobs).

#### Delete Upload
```php
DELETE /admin/csv/{csvUpload}
```
Deletes the upload record and associated file.

## 📸 Screenshots

### Landing Page
The landing page features a hero section with YoPrint branding and call-to-action buttons.

### Login Page
Clean, centered card design with gradient header displaying the YoPrint logo.

### User Dashboard
File manager interface with upload form, status filters, and file listing table.

### Admin Dashboard
Comprehensive statistics cards and processing queue management.

### Profile Settings
Modern profile management with sections for personal info, password update, and account deletion.

## 🐛 Troubleshooting

### Common Issues

**Issue: Queue jobs not processing**
- **Solution**: Make sure queue worker is running: `php artisan queue:work`
- Check queue connection in `.env`: `QUEUE_CONNECTION=database`
- Verify jobs table exists: `php artisan migrate`

**Issue: File upload fails**
- **Solution**: Check file size (max 10MB)
- Verify MIME type is supported
- Check storage permissions: `chmod -R 775 storage`

**Issue: Admin routes return 403**
- **Solution**: Verify user has `is_admin = true` in database
- Check middleware is applied to routes

**Issue: UTF-8 characters not displaying correctly**
- **Solution**: Verify database charset is `utf8mb4`
- Check CSV file encoding before upload

**Issue: Duplicate entries after re-upload**
- **Solution**: Ensure CSV has `UNIQUE_KEY` column
- Verify UPSERT logic is working in `CsvParserService`

**Issue: Route cache issues**
- **Solution**: Clear route cache: `php artisan route:clear && php artisan config:clear`

### Log Files

Check application logs:
```bash
tail -f storage/logs/laravel.log
```

View failed jobs:
```bash
php artisan queue:failed
```

Retry failed job:
```bash
php artisan queue:retry {job_id}
```

Retry all failed jobs:
```bash
php artisan queue:retry all
```

## 📝 Commands Reference

### Artisan Commands

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (drop all tables and re-migrate)
php artisan migrate:fresh

# Create storage link
php artisan storage:link

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Queue management
php artisan queue:work              # Start queue worker
php artisan queue:work --tries=3    # With retry attempts
php artisan queue:listen            # Listen mode (auto-reload)
php artisan queue:failed            # List failed jobs
php artisan queue:retry all         # Retry all failed jobs
php artisan queue:flush             # Delete all failed jobs

# Development server
php artisan serve                   # Start at localhost:8000
php artisan serve --port=8080       # Custom port

# Tinker (Laravel REPL)
php artisan tinker
```

### Create Admin User in Tinker

```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = bcrypt('password');
$user->is_admin = true;
$user->save();
```

### Composer Commands

```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Install without dev dependencies (production)
composer install --no-dev --optimize-autoloader
```

### NPM Commands

```bash
# Install dependencies
npm install

# Development build
npm run dev

# Production build
npm run build

# Watch mode (auto-rebuild on changes)
npm run watch
```

## 🧪 Testing

### Testing CSV Upload

Create a test CSV file (`test.csv`):

```csv
UNIQUE_KEY,PRODUCT_TITLE,PRODUCT_DESCRIPTION,STYLE,SANMAR_MAINFRAME_COLOR,SIZE,COLOR_NAME,PIECE_PRICE
TEST001,Product 1,Description 1,Style A,Red,M,Crimson,19.99
TEST002,Product 2,Description 2,Style B,Blue,L,Navy,24.99
TEST003,Product 3,Description 3,Style C,Green,S,Forest,15.99
```

Upload this file and verify:
1. File is accepted
2. Job is queued
3. Processing starts
4. 3 rows are inserted
5. Re-uploading the same file updates existing rows (0 new inserts, 3 updates)

## 🚀 Deployment

### Production Checklist

1. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   ```

2. **Optimize Application**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

3. **Set Permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

4. **Configure Queue Worker as Service**

   Create `/etc/systemd/system/yocsv-worker.service`:

   ```ini
   [Unit]
   Description=YoPrint Queue Worker
   After=network.target

   [Service]
   Type=simple
   User=www-data
   WorkingDirectory=/path/to/yocsv
   ExecStart=/usr/bin/php /path/to/yocsv/artisan queue:work --tries=3 --timeout=300
   Restart=always

   [Install]
   WantedBy=multi-user.target
   ```

   Enable and start the service:
   ```bash
   sudo systemctl enable yocsv-worker
   sudo systemctl start yocsv-worker
   sudo systemctl status yocsv-worker
   ```

5. **Configure Supervisor (Alternative)**

   Create `/etc/supervisor/conf.d/yocsv-worker.conf`:

   ```ini
   [program:yocsv-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/yocsv/artisan queue:work --tries=3 --timeout=300
   autostart=true
   autorestart=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/path/to/yocsv/storage/logs/worker.log
   ```

   Reload supervisor:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start yocsv-worker:*
   ```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature-name`
3. Commit your changes: `git commit -am 'Add new feature'`
4. Push to the branch: `git push origin feature/your-feature-name`
5. Submit a pull request

### Coding Standards

- Follow PSR-12 coding standard
- Use meaningful variable and function names
- Add comments for complex logic
- Write descriptive commit messages

## 📄 License

This project is licensed under the MIT License.

## 👥 Authors

- Initial development and implementation

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP framework
- [Hyper Template](https://coderthemes.com/hyper/) - Bootstrap admin template
- [Bootstrap](https://getbootstrap.com/) - CSS framework
- [Material Design Icons](https://materialdesignicons.com/) - Icon library

## 📞 Support

For support, open an issue in the GitHub repository.

---

**Made with ❤️ using Laravel**
