# Motor Bazar — How It Works
> سجل شامل لكيفية عمل كل خدمة في المنصة. يُحدَّث عند إضافة أي ميزة جديدة.

---

## 🏗️ Stack التقني

| الطبقة | التقنية |
|--------|---------|
| Backend | Laravel 11 (PHP) |
| Frontend | Blade + Alpine.js + Vanilla JS |
| CSS | Tailwind CSS (CDN) |
| Real-time | Laravel Reverb (WebSocket on port 8080) |
| Broadcast | Pusher-JS client → Reverb server |
| Database | MySQL / MariaDB |
| Queue | Sync (no queue worker — notifications fire immediately) |
| Scheduler | `php artisan schedule:work` — runs every minute |
| Icons | Lucide Icons |
| Toasts | Toastify.js |

---

## 1. 🔔 نظام التنبيهات (Notification Center)

### كيف يعمل؟

1. **عند وصول Lead جديد** من نموذج البيع في الصفحة الرئيسية:
   - `HomeController@storeSellLead` ينشئ الـ Lead في DB
   - يجلب كل المستخدمين بـ `role = admin` أو email الأدمن
   - يُطلق `NewLeadReceived::class` على كل أدمن
   - التنبيه يُخزَّن تلقائياً في جدول `notifications`

2. **عند وجود مزايدة جديدة**:
   - `AuctionController@placeBid` بعد حفظ الـ Bid
   - يُطلق `NewBidPlaced::class` على كل أدمن

3. **في الواجهة (Admin Header)**:
   - زر 🔔 Bell في أعلى اليمين من الـ header
   - Badge برتقالي يظهر عدد التنبيهات غير المقروءة
   - عند الضغط → يفتح Panel يجلب التنبيهات من API
   - كل 15 ثانية يتحقق تلقائياً من تنبيهات جديدة
   - عند وصول تنبيه جديد → Toast Popup + صوت ping صغير
   - عند النقر على تنبيه → يُعلَّم مقروءاً وينتقل للصفحة المرتبطة

### الملفات:
```
app/Notifications/NewLeadReceived.php
app/Notifications/NewBidPlaced.php
app/Http/Controllers/Admin/NotificationController.php
resources/views/admin/layout.blade.php  (Bell UI + JS)
routes/web.php  (admin/notifications/*)
```

### API Endpoints:
```
GET  /admin/notifications          → قائمة التنبيهات (آخر 30)
GET  /admin/notifications/count   → عدد غير المقروءة فقط
POST /admin/notifications/read-all → تعليم الكل مقروء
POST /admin/notifications/{id}/read → تعليم واحد مقروء
```

---

## 2. ⚡ Real-time WebSocket (Laravel Reverb)

### كيف يعمل؟

1. **السيرفر**: Reverb يشتغل على `127.0.0.1:8080`
   - يُشغَّل بـ: `php artisan reverb:start`

2. **الـ Client**: Pusher-JS يتصل بـ Reverb
   - الإعدادات في `.env`: `REVERB_APP_KEY`, `REVERB_HOST`, `REVERB_PORT`

3. **عند المزايدة**:
   - `BidPlaced` event → يُرسَل لـ channel `auction.{id}`
   - `AuctionUpdated` event → يُحدِّث السعر والوقت في كل المتصلين
   - صفحة المزاد `show.blade.php` تستمع للحدثين وتحدّث UI بدون refresh

4. **عند إغلاق مزاد منتهي**:
   - `CloseExpiredAuctions` command يجري كل دقيقة
   - يُغلق المزادات المنتهية ويُطلق `AuctionUpdated`
   - كل من على صفحة المزاد يرى الحالة تتغير لـ "Closed" فوراً

### الملفات:
```
app/Events/BidPlaced.php
app/Events/AuctionUpdated.php
app/Console/Commands/CloseExpiredAuctions.php
routes/console.php  (scheduler registration)
resources/views/auctions/show.blade.php  (JS listener)
```

### تشغيل الخدمات:
```bash
php artisan reverb:start          # WebSocket server
php artisan schedule:work         # Scheduler (auto-close auctions)
php artisan serve                 # Laravel HTTP server
npm run dev                       # Vite / CSS watcher
```

---

## 3. 🔨 نظام المزادات (Auction System)

### دورة حياة المزاد:

```
coming_soon → active → closed
```

| الحالة | الشكل في الواجهة |
|--------|-----------------|
| `coming_soon` | Badge برتقالي متحرك "Coming Soon" |
| `active` | Badge أخضر متحرك "Live Now" + نقطة تومض |
| `closed` | Badge أحمر "Finished" |
| `finished` | نفس closed — مكتمل |

### منطق المزايدة (placeBid):

1. **التحقق**: المزاد active؟ المبلغ ≥ الحد الأدنى؟
2. **Anti-Sniping**: إذا تبقى ≤ X ثانية → أضف Y ثانية (من Global Settings)
3. **حفظ الـ Bid** في جدول `bids`
4. **تحديث `current_price`** في جدول `auctions`
5. **Broadcast** `BidPlaced` + `AuctionUpdated` عبر Reverb
6. **إشعار الأدمن** بـ `NewBidPlaced` notification

### الملفات:
```
app/Http/Controllers/AuctionController.php
app/Models/Auction.php
app/Models/Bid.php
resources/views/auctions/show.blade.php
resources/views/auctions/index.blade.php
```

---

## 4. ⚙️ إعدادات المزادات العامة (Global Auction Settings)

### مكان الإعدادات:
```
/admin/settings/auctions
```

### ما يمكن التحكم به:

| الإعداد | المفتاح في DB | الافتراضي |
|---------|--------------|-----------|
| تفعيل Anti-Sniping | `anti_snipe_enabled` | `1` (مفعّل) |
| عتبة التفعيل (ثانية) | `time_extension_threshold` | `30` |
| مدة الإضافة (ثانية) | `time_extension_seconds` | `20` |
| الزيادة الدنيا للمزايدة | `default_bid_increment` | `500` |
| مبلغ التأمين الافتراضي | `default_deposit` | `1000` |
| إغلاق تلقائي للمزادات | `auction_auto_close` | `1` |
| إخفاء Bid Feed عن الديلرز | `global_bid_feed_admin_only` | `1` |

### كيف تُقرأ الإعدادات:
```php
\App\Models\SystemSetting::get('anti_snipe_enabled', '1')
```

### الملفات:
```
app/Http/Controllers/Admin/SettingsController.php
app/Models/SystemSetting.php
resources/views/admin/settings/auctions.blade.php
```

---

## 5. 🏠 الصفحة الرئيسية — قسم المزادات

### كيف يعمل؟

- `HomeController@index` يجلب المزادات بحالة `active` أو `coming_soon`
- محفوظة في Cache لمدة 5 دقائق (يُمسح بـ `php artisan cache:clear`)
- تُعرض بـ cards في قسم "Active Auctions"
- كل card يحتوي: صورة + اسم السيارة + السعر الحالي + countdown timer + عدد المزايدات

### ملاحظة:
> بعد تغيير حالة مزاد يجب مسح الـ Cache:
> ```bash
> php artisan cache:clear
> ```

### الملفات:
```
app/Http/Controllers/HomeController.php
resources/views/home.blade.php  (قسم #live-auctions)
```

---

## 6. 🛡️ Global Bid Feed — صلاحيات العرض

### كيف يعمل؟

- قسم "Global Bid Feed" في صفحة المزاد يعرض أسماء المزايدين ومبالغهم
- **للأدمن فقط** بشكل افتراضي
- يُتحكم به من: `Settings → Auction Settings → Hide Global Bid Feed from Dealers`
- إذا كان `global_bid_feed_admin_only = 1` → يظهر للأدمن فقط مع شارة "Admin View"
- إذا كان `0` → يظهر للجميع

### الملفات:
```
resources/views/auctions/show.blade.php  (السطر ~203)
```

---

## 7. 📋 نظام إدارة الطلبات (Leads CRM)

### كيف يصل الطلب؟

1. العميل يملأ نموذج "Sell Your Car" في الصفحة الرئيسية (3 خطوات)
2. `POST /sell-car` → `HomeController@storeSellLead`
3. يُنشئ `Lead` في DB مع كل بيانات السيارة والعميل
4. يُرسل `NewLeadReceived` notification لكل الأدمنية

### حالات الطلب:
```
new → in_review → inspection_scheduled → approved / rejected
```

### الملفات:
```
app/Models/Lead.php
app/Http/Controllers/Admin/LeadController.php  (أو ما يقابله)
resources/views/admin/leads/
```

---

## 8. 🔐 صلاحيات المستخدمين

### الأدوار المتاحة (عمود `role` في جدول `users`):

| الدور | الصلاحيات |
|------|-----------|
| `admin` | كل شيء |
| `dealer` | عرض وبيع السيارات، المشاركة في المزادات |
| (فارغ/null) | مستخدم عادي |

### التحقق في الكود:
```php
// Controller
auth()->user()->role === 'admin'

// Blade
@if(auth()->user()?->role === 'admin')
    ...
@endif

// Model method
$user->isAdmin()  // يتحقق بالبريد الإلكتروني
```

---

## 9. 🗃️ قاعدة البيانات — الجداول الرئيسية

| الجدول | الغرض |
|--------|-------|
| `users` | المستخدمين (admin, dealer, عادي) |
| `auctions` | المزادات مع السعر والحالة والوقت |
| `bids` | كل المزايدات مرتبطة بمزاد ومستخدم |
| `cars` | السيارات المرتبطة بالمزادات |
| `leads` | طلبات البيع من العملاء |
| `notifications` | تنبيهات النظام (Laravel built-in) |
| `system_settings` | إعدادات النظام بنظام key-value |
| `inspections` | تقارير الفحص التقني للسيارات |

---

## 🔄 الخدمات التي يجب إبقاؤها شغّالة

```bash
# Terminal 1 — HTTP Server
php artisan serve

# Terminal 2 — CSS / JS (Development)
npm run dev

# Terminal 3 — WebSocket Server (Real-time)
php artisan reverb:start

# Terminal 4 — Scheduler (Auto-close auctions)
php artisan schedule:work
```

> في الإنتاج: استخدم **Supervisor** لإدارة Reverb و schedule:work تلقائياً.

---

## 📝 سجل التحديثات

| التاريخ | الميزة المضافة |
|--------|--------------|
| 2026-04-04 | نظام التنبيهات الكامل (Bell + Toast + Sound + DB) + **إصلاح bug**: الأدمن لم يُعرَّف بـ `role=admin` |
| 2026-04-04 | إشعار `NewLeadReceived` و `NewBidPlaced` — يُحفظ في `notifications` table عند كل حدث |
| 2026-04-04 | إيميل تأكيد للعميل عند تسجيل Lead — قالب HTML احترافي + إعداد عبر Admin Panel |
| 2026-04-04 | WhatsApp Service — يدعم Twilio / Meta Cloud API / Custom HTTP |
| 2026-04-04 | صفحة إعدادات Communication `/admin/settings/communication` — SMTP + WhatsApp API + قوالب الرسائل |
| 2026-04-04 | رابط "Email & WhatsApp" في sidebar الأدمن |
| 2026-04-04 | Global Bid Feed مخفي عن الديلرز |
| 2026-04-04 | قسم المزادات في الصفحة الرئيسية |
| 2026-04-04 | صفحة إعدادات المزادات العامة `/admin/settings/auctions` |
| 2026-04-04 | Anti-Sniping بإعدادات عالمية قابلة للتحكم |
| 2026-04-04 | إغلاق تلقائي للمزادات عبر Scheduler + Reverb broadcast |
