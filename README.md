# Admin Dashboard

A Laravel-based admin dashboard with Vue.js frontend components for product management and authentication.

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL database

## Installation

1. Clone the repository:
```bash
git clone https://github.com/jpcunanan716/admindash.git
cd admindash
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Configure database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=admindash
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Generate application key:
```bash
php artisan key:generate
```

7. Run migrations and seeders:
```bash
php artisan migrate --seed
```

8. Create storage link:
```bash
php artisan storage:link
```

9. Build assets:
```bash
npm run build
```

## Running the Application

Start the development server:
```bash
php artisan serve
```

For development with asset watching:
```bash
npm run dev
```

Access the application at: `http://127.0.0.1:8000`

## Default Login

- Username: `admin` or `admin@example.com`
- Password: `password`

## Features

- **Authentication**: Laravel-based login with username/email and remember me
- **Product Management**: Full CRUD operations with Vue.js components
- **Dashboard**: AdminLTE-styled interface with sidebar navigation
- **Search & Filter**: Product listing with search and category filtering
- **File Upload**: Multiple image upload for products
- **Video Player**: Vue component for video playback (bonus feature)

## Key Routes

- `/login` - Authentication
- `/dashboard` - Admin dashboard (Admin role only)
- `/home` - User dashboard (User role)
- `/products` - Product listing and management (Admin role only)
- `/videos` - Video player page

## Development

Run tests:
```bash
php artisan test
```

Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```
