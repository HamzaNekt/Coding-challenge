# 🛍️ Product Management System - Laravel API

## 📋 Project Description

This project is a product management system developed with Laravel, offering a robust REST API for creating, viewing, and managing products and categories. The system uses a modular architecture with the Repository pattern and Services, ensuring clear separation of responsibilities and optimal maintainability.

**Key Features:**
- ✅ Product creation and listing
- ✅ Category system with hierarchical support
- ✅ Web interface/CLI commands for product management
- ✅ Image upload 
- ✅ Filtering and sorting
- ✅ Complete unit and functional tests

## 🚀 Technologies Used

### Backend
- **Laravel 8.x** - Modern PHP framework
- **PHP 7.4** - Server language
- **MySQL 8.0** - Relational database
- **PHPUnit** - Unit and functional testing

### Frontend
- **Vue.js 2.6** - Progressive JavaScript framework
- **Bootstrap 4.6** - Responsive CSS framework
- **Laravel Mix** - Build tool and asset compilation
- **Axios** - HTTP client for API requests

### DevOps & Tools
- **Docker** - Containerization and deployment
- **Docker Compose** - Multi-container orchestration
- **Composer** - PHP dependency manager
- **NPM** - JavaScript dependency manager



## 📦 Installation and Setup

### Prerequisites
- Docker and Docker Compose
- Git

### 1. Clone the Project
```bash
git clone <repository-url>
cd coding-challenge
```

### 2. Docker Launch (Recommended Method)
```bash
# Build and launch containers
docker-compose up -d

# Application will be accessible at http://localhost:8000


The Docker container automatically handles:
- ✅ Composer dependencies installation
- ✅ Environment configuration (.env)
- ✅ Application key generation
- ✅ Database migrations execution
- ✅ Database seeding
- ✅ NPM dependencies installation
- ✅ Frontend assets compilation

### 3. Manual Installation (Alternative)
```bash
# Install PHP dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=products_db
DB_USERNAME=root
DB_PASSWORD=secret

# Run migrations and seeders
php artisan migrate --seed

# Install NPM dependencies and compile
npm install
npm run dev

# Start development server
php artisan serve
```

## 🎯 Implemented Features

### 1. 🌐 Web Interface

#### Product Viewing
- **URL**: `http://localhost:8000/products`
- **Features**:
  - Complete product listing
  - Associated categories display
  - Responsive interface with Bootstrap

#### Product Creation
- **URL**: `http://localhost:8000/products/create`
- **Features**:
  - Complete creation form
  - Client-side and server-side validation
  - Image upload
  - Multiple category selection


### 2. 🖥️ Command Line Interface (CLI)

#### Product Creation
```bash
# Create a product with all information
php artisan product:create \
  --name="MacBook Pro M3" \
  --description="Professional laptop computer" \
  --price=2499.99 \
  --image="macbook-pro.jpg" \
  --categories="1,2"
```

#### Product Viewing
```bash
# List all products
php artisan product:create --list

# Filter by category
php artisan product:create --list --category-filter=1

# Sort by price
php artisan product:create --list --sort-price=desc

# Combine filters and sorting
php artisan product:create --list --category-filter=1 --sort-price=asc
```

**Example output:**
```
+----+------------------+----------------------------------------+------------+------------------+----------+
| ID | Name             | Description                            | Price      | Categories       | Created  |
+----+------------------+----------------------------------------+------------+------------------+----------+
| 1  | iPhone 15 Pro    | Latest iPhone with Pro features       | 1 199,99 Dh| Electronics      | 11/07/25 |
| 2  | MacBook Pro M3   | Professional laptop computer          | 2 499,99 Dh| Electronics      | 11/07/25 |
+----+------------------+----------------------------------------+------------+------------------+----------+
```

### 4. 🧪 Testing and Quality

#### Running Tests
```bash
# All tests
php artisan test

# Specific tests
php artisan test --filter=ProductCreationTest

# Tests with coverage
php artisan test --coverage
```

#### Available Test Types
- **Unit Tests**: Models, Repositories, Services
- **Functional Tests**: API, Complete workflows
- **Integration Tests**: Database, Relations

### 5. 📊 Data Management

#### Available Seeders
```bash
# Complete test data
php artisan db:seed

# Specific seeder
php artisan db:seed --class=ProductSeeder
php artisan db:seed --class=CategorySeeder
```

#### Database Structure
```sql
-- Products table
products: id, name, description, price, image, created_at, updated_at

-- Categories table
categories: id, name, parent_id, created_at, updated_at

-- Pivot table (many-to-many)
product_categories: product_id, category_id
```

## 🔧 Useful Commands

### Development
```bash
# Restart containers
docker-compose down && docker-compose up -d

# Access application container
docker-compose exec app bash

# View logs
docker-compose logs -f app

# Rebuild assets
npm run dev
# or for production
npm run prod
```

### Database
```bash
# Reset database
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_new_table

# Create new seeder
php artisan make:seeder NewTableSeeder
```

### Cache and Optimization
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Optimize for production
php artisan optimize
```
