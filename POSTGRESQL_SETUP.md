# PostgreSQL Setup Guide for Automazad

## 1. تثبيت PostgreSQL

### Windows:
```bash
# تحميل وتثبيت PostgreSQL
# https://www.postgresql.org/download/windows/

# أو استخدام Chocolatey
choco install postgresql
```

### Ubuntu/Debian:
```bash
sudo apt update
sudo apt install postgresql postgresql-contrib
```

### macOS:
```bash
# استخدام Homebrew
brew install postgresql
brew services start postgresql
```

## 2. إعداد قاعدة البيانات

```bash
# الدخول إلى PostgreSQL
sudo -u postgres psql

# إنشاء قاعدة البيانات
CREATE DATABASE automazad_db;

# إنشاء مستخدم
CREATE USER automazad_user WITH PASSWORD 'your_secure_password';

# منح الصلاحيات
GRANT ALL PRIVILEGES ON DATABASE automazad_db TO automazad_user;

# الخروج
\q
```

## 3. إعداد Laravel

### تعديل ملف .env:
```env
# استبدال إعدادات SQLite بـ PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=automazad_db
DB_USERNAME=automazad_user
DB_PASSWORD=your_secure_password
```

## 4. تثبيت PostgreSQL PHP Extension

### Windows (XAMPP/WAMP):
- إضافة `extension=pgsql` في `php.ini`
- إعادة تشغيل Apache

### Linux:
```bash
sudo apt install php-pgsql
```

### macOS:
```bash
brew install php
```

## 5. تثبيت Laravel PostgreSQL Package
```bash
composer require pgsql
```

## 6. ترحيل البيانات

```bash
# حذف قاعدة البيانات القديمة وإعادة بنائها
php artisan migrate:fresh --seed

# أو إذا كنت تريد الاحتفاظ بالبيانات
php artisan migrate --seed
```

## 7. التحقق من الإعداد

```bash
# اختبار الاتصال بقاعدة البيانات
php artisan tinker
>>> DB::connection()->getPdo()
>>> exit
```

## 8. إعدادات الإنتاج (Production)

### إعدادات PostgreSQL الموصى بها:
```env
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=automazad_production
DB_USERNAME=automazad_prod_user
DB_PASSWORD=very_secure_password_here
DB_SSL_MODE=require
```

### تحسين الأداء:
```sql
-- في PostgreSQL
CREATE EXTENSION IF NOT EXISTS pg_trgm; -- للبحث السريع
CREATE EXTENSION IF NOT EXISTS unaccent; -- للبحث بدون تشكيل

-- إنشاء indexes للجداول الرئيسية
CREATE INDEX idx_cars_make_model ON cars(make, model);
CREATE INDEX idx_auctions_status ON auctions(status);
CREATE INDEX idx_bids_auction_amount ON bids(auction_id, amount DESC);
```

## 9. النسخ الاحتياطي

### أمر النسخ الاحتياطي:
```bash
pg_dump -h localhost -U automazad_user automazad_db > backup.sql
```

### استعادة النسخة الاحتياطية:
```bash
psql -h localhost -U automazad_user automazad_db < backup.sql
```

## 10. استكشاف الأخطاء

### مشاكل شائعة:

1. **Connection refused**:
   - تأكد من PostgreSQL شغال
   - تحقق من المنفذ 5432

2. **Authentication failed**:
   - تحقق من اسم المستخدم وكلمة المرور
   - تأكد من صلاحيات المستخدم

3. **Extension not found**:
   - تثبيت `php-pgsql`
   - إعادة تشغيل PHP/Apache

4. **Migration errors**:
   - تحقق من إعدادات PostgreSQL
   - تأكد من صلاحيات المستخدم

## 11. ملاحظات الأداء

### PostgreSQL vs SQLite:
- **PostgreSQL**: أفضل للإنتاج، يدعم التزامن
- **SQLite**: سريع للتطوير، محدود بالأداء

### تحسينات إضافية:
- استخدام Connection Pooling
- إعدادات PostgreSQL في `postgresql.conf`
- مراقبة الأداء مع pgAdmin
