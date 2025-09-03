Admin Dashboard - Quick Install
Prerequisites
PHP 8.2+

Composer

Node.js and npm

MySQL 5.7+ or equivalent database

Installation Steps
Clone the Repository

bash
git clone https://github.com/jpcunanan716/admindash.git
cd admindash
Install PHP Dependencies

bash
composer install
Install JavaScript Dependencies

bash
npm install
Environment Setup

bash
cp .env.example .env
Edit .env with your database credentials:

env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=admindash
DB_USERNAME=root
DB_PASSWORD=your_password
Generate Application Key

bash
php artisan key:generate
Database Setup

bash
php artisan migrate
Seed Default Data (creates admin user and sample products)

bash
php artisan db:seed
Build Frontend Assets

bash
npm run build
Start Development Server

bash
php artisan serve
Access Application
Visit http://localhost:8000 in your browser

Default Login Credentials
Username: admin or admin@example.com

Password: password

Getting Started
Log in with the default credentials

Access the dashboard from the sidebar

Manage products via the "Products" link

View videos via the "Videos" link
