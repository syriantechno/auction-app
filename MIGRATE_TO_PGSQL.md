# PostgreSQL Migration Script for Automazad

## ملفات التعديل المطلوبة:

### 1. تعديل .env
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=automazad_db
DB_USERNAME=automazad_user
DB_PASSWORD=your_password_here
```

### 2. تعديل config/database.php (إذا لزم الأمر)
```php
'pgsql' => [
    'driver' => 'pgsql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'automazad_db'),
    'username' => env('DB_USERNAME', 'automazad_user'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'sslmode' => 'prefer',
],
```

## خطوات الترحيل:

### الخطوة 1: تثبيت PostgreSQL
```bash
# Windows
choco install postgresql

# Ubuntu
sudo apt update && sudo apt install postgresql postgresql-contrib

# macOS
brew install postgresql && brew services start postgresql
```

### الخطوة 2: إعداد قاعدة البيانات
```bash
# الدخول إلى PostgreSQL
sudo -u postgres psql

# إنشاء قاعدة البيانات والمستخدم
CREATE DATABASE automazad_db;
CREATE USER automazad_user WITH PASSWORD 'your_password';
GRANT ALL PRIVILEGES ON DATABASE automazad_db TO automazad_user;
\q
```

### الخطوة 3: تثبيت PHP PostgreSQL Extension
```bash
# Windows
# إضافة extension=pgsql في php.ini

# Linux
sudo apt install php-pgsql

# macOS
brew install php
```

### الخطوة 4: تثبيت Laravel Package
```bash
composer require pgsql
```

### الخطوة 5: ترحيل البيانات
```bash
# نسخ قاعدة البيانات الحالية (اختياري)
cp database/database.sqlite database/database.sqlite.backup

# ترحيل إلى PostgreSQL
php artisan migrate:fresh --seed
```

### الخطوة 6: التحقق
```bash
php artisan tinker
>>> DB::connection()->getDriverName()
>>> # يجب يظهر: "pgsql"
```

## ملاحظات هامة:
- PostgreSQL أسرع بـ 3-5 مرات من SQLite
- يدعم التزامن والمعاملات المتقدمة
- أفضل للبيانات الضخمة والإنتاج
- يحتاج إعدادات إضافية للأداء الأمثل
