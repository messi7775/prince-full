# Prince Full

نظام إدارة ومبيعات بطاقات الإنترنت **Prince Full**، مبني باستخدام **PHP + MySQL + HTML5 + CSS3 + JavaScript**، ويعتمد على معمارية **MVC — Model View Controller**.

المشروع مصمم ليكون:

-  وسهل الصيانة.
- سريعًا وخفيفًا.
- مناسبًا للاستضافات المشتركة.
- متوافقًا مع infinityfree.com.
- عربيًا بالكامل ويدعم RTL.
- Mobile-First ومتجاوبًا مع الهاتف والكمبيوتر.
- بدون أطر عمل ثقيلة أو متطلبات Node.js.

---

# 1. التقنيات

## Backend

- PHP 8+
- MVC Architecture
- PDO
- MySQL / MariaDB
- PHP Sessions
- `password_hash()`
- `password_verify()`

## Frontend

- HTML5
- CSS3
- Vanilla JavaScript
- Arabic RTL
- Responsive Design
- Mobile-First

## Web Server

- Apache
- `.htaccess`

## Database

- MySQL
- MariaDB

## Version Control

- Git
- GitHub

---

# 2. المعمارية — MVC

المشروع يعتمد على فصل واضح بين:

```text
Model
View
Controller
```

### Model

مسؤول عن البيانات وقاعدة البيانات.

```text
Models/
├── Admin.php
├── Package.php
├── Inventory.php
├── Sale.php
├── Distributor.php
├── Payment.php
├── Expense.php
├── Line.php
├── CashMovement.php
└── AuditLog.php
```

### View

مسؤول عن واجهة المستخدم والعرض فقط.

```text
Views/
├── layouts/
├── auth/
├── dashboard/
├── packages/
├── inventory/
├── sales/
├── distributors/
├── payments/
├── expenses/
├── lines/
├── cash/
├── reports/
└── settings/
```

### Controller

مسؤول عن استقبال الطلبات وتنفيذ منطق التطبيق والتنسيق بين Model وView.

```text
Controllers/
├── AuthController.php
├── DashboardController.php
├── PackageController.php
├── InventoryController.php
├── SaleController.php
├── DistributorController.php
├── PaymentController.php
├── ExpenseController.php
├── LineController.php
├── CashController.php
├── ReportController.php
└── SettingsController.php
```

---

# 3. دورة تنفيذ الطلب

```text
                   المستخدم
                      │
                      ▼
                 ┌─────────┐
                 │ Browser │
                 └────┬────┘
                      │
                      │ HTTP Request
                      ▼
              ┌─────────────────┐
              │ public/index.php │
              └────────┬────────┘
                       │
                       ▼
                 ┌──────────┐
                 │  Router  │
                 └────┬─────┘
                      │
                      ▼
              ┌────────────────┐
              │   Controller   │
              └───────┬────────┘
                      │
                      ▼
              ┌────────────────┐
              │     Model      │
              └───────┬────────┘
                      │
                      ▼
                ┌───────────┐
                │   MySQL   │
                └─────┬─────┘
                      │
                      ▼
                    Model
                      │
                      ▼
                 Controller
                      │
                      ▼
                    View
                      │
                      ▼
                  Browser
```

مثال إضافة باقة:

```text
المستخدم
   ↓
Package View
   ↓
PackageController
   ↓
Package Model
   ↓
MySQL
   ↓
Package Model
   ↓
PackageController
   ↓
Package View
```

---

# 4. هيكل المشروع

الهيكل المستهدف الكامل:

```text
prince-full/
│
├── app/
│   │
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── PackageController.php
│   │   ├── InventoryController.php
│   │   ├── SaleController.php
│   │   ├── DistributorController.php
│   │   ├── PaymentController.php
│   │   ├── ExpenseController.php
│   │   ├── LineController.php
│   │   ├── CashController.php
│   │   ├── ReportController.php
│   │   └── SettingsController.php
│   │
│   ├── Models/
│   │   ├── Admin.php
│   │   ├── Package.php
│   │   ├── Inventory.php
│   │   ├── Sale.php
│   │   ├── Distributor.php
│   │   ├── Payment.php
│   │   ├── Expense.php
│   │   ├── Line.php
│   │   ├── CashMovement.php
│   │   └── AuditLog.php
│   │
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   ├── sidebar.php
│   │   │   ├── topbar.php
│   │   │   └── footer.php
│   │   │
│   │   ├── auth/
│   │   │   └── login.php
│   │   │
│   │   ├── dashboard/
│   │   │   └── index.php
│   │   │
│   │   ├── packages/
│   │   ├── inventory/
│   │   ├── sales/
│   │   ├── distributors/
│   │   ├── payments/
│   │   ├── expenses/
│   │   ├── lines/
│   │   ├── cash/
│   │   ├── reports/
│   │   └── settings/
│   │
│   ├── Core/
│   │   ├── App.php
│   │   ├── Controller.php
│   │   ├── Model.php
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── Session.php
│   │
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── SaleService.php
│   │   ├── PaymentService.php
│   │   ├── InventoryService.php
│   │   └── ReportService.php
│   │
│   └── Helpers/
│       ├── auth.php
│       ├── csrf.php
│       ├── validation.php
│       ├── format.php
│       └── redirect.php
│
├── config/
│   ├── app.php
│   └── database.php
│
├── database/
│   └── schema.sql
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   │
│   └── assets/
│       ├── css/
│       │   └── app.css
│       ├── js/
│       │   └── app.js
│       └── images/
│
├── storage/
│   ├── logs/
│   └── backups/
│
├── tools/
│   └── hash_password.php
│
├── .gitignore
└── README.md
```

---

# 5. وظيفة كل مجلد

## `app/`

يحتوي على منطق التطبيق الأساسي.

## `app/Controllers/`

يحتوي على Controllers.

الـ Controller يستقبل الطلب ويقرر ماذا يجب أن يحدث.

## `app/Models/`

يحتوي على Models والتعامل مع البيانات.

## `app/Views/`

يحتوي على صفحات الواجهة.

لا يتم وضع استعلامات SQL داخل الـ View.

## `app/Core/`

النواة الأساسية للنظام، مثل:

- Router.
- Database.
- Request.
- Response.
- Session.
- Base Controller.
- Base Model.

## `app/Services/`

يحتوي على منطق الأعمال الذي يحتاج إلى أكثر من Model أو أكثر من خطوة.

مثال:

```text
SaleService
```

يمكنه تنفيذ:

```text
إنشاء البيع
↓
خصم المخزون
↓
تسجيل الحركة المالية
↓
تسجيل الدين عند البيع الآجل
```

## `app/Helpers/`

دوال مساعدة مشتركة.

## `config/`

إعدادات التطبيق وقاعدة البيانات.

## `database/`

ملفات قاعدة البيانات وSchema.

## `public/`

هذا هو **المجلد العام Web Root**.

يجب أن تكون الملفات التي يمكن للمتصفح الوصول إليها موجودة هنا فقط عند استخدام الاستضافة التي تدعم ذلك.

## `storage/`

ملفات داخلية مثل:

```text
logs/
backups/
```

ويجب عدم السماح بالوصول المباشر إليها من المتصفح.

---

# 6. Front Controller

نقطة الدخول الرئيسية للنظام:

```text
public/index.php
```

بدل أن يكون لكل صفحة PHP ملف مستقل مثل:

```text
dashboard.php
sales.php
packages.php
```

يتم توجيه الطلبات من خلال Router:

```text
public/index.php
        ↓
      Router
        ↓
   Controller
        ↓
      Model
        ↓
      View
```

وهذا هو الأسلوب الأساسي في MVC.

---

# 7. نظام المصادقة

المشروع يستخدم حساب Admin واحد.

لا يوجد:

- تسجيل مستخدمين.
- Multiple Users.
- Roles.
- Permissions.
- OAuth.
- JWT.
- Password Reset.

كلمة المرور تحفظ كـ Hash:

```php
password_hash($password, PASSWORD_DEFAULT);
```

والتحقق:

```php
password_verify($password, $hash);
```

بعد تسجيل الدخول:

```php
session_regenerate_id(true);
```

ويتم تخزين حالة تسجيل الدخول داخل PHP Session.

كل Controller أو صفحة محمية يجب أن تتحقق من تسجيل الدخول.

---

# 8. قاعدة البيانات

قاعدة البيانات:

```text
MySQL / MariaDB
```

التعامل معها عبر:

```text
PDO
```

مع:

```text
Prepared Statements
```

ملف الإعداد:

```text
config/database.php
```

مثال:

```php
return [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'prince',
    'username' => 'root',
    'password' => '',
];
```

`3306` هو المنفذ الافتراضي لـ MySQL.

لا يجب رفع كلمة مرور قاعدة البيانات الحقيقية إلى مستودع GitHub عام.

---

# 9. الوحدات الرئيسية

## Dashboard

لوحة التحكم الرئيسية.

تعرض البيانات الحقيقية من قاعدة البيانات.

عند عدم وجود بيانات:

```text
0
```

ولا يتم استخدام بيانات وهمية.

---

## Packages

إدارة الباقات.

البيانات الأساسية:

- اسم الباقة.
- السعر.
- الحجم.
- الساعات.
- اللون.
- الحالة.

---

## Inventory

إدارة المخزون.

يتم تسجيل:

- الباقة.
- الكمية.
- السعر وقت إنشاء المخزون.
- التاريخ.
- الحالة.

السعر التاريخي مهم للحفاظ على دقة العمليات القديمة.

---

## Sales

إدارة عمليات البيع.

يدعم:

- البيع النقدي.
- البيع الآجل.
- أكثر من بطاقة في العملية.
- سعر الوحدة.
- إجمالي العملية.

---

## Distributors

إدارة الموزعين.

رصيد الموزع يحسب من الحركات:

```text
الرصيد = إجمالي المبيعات الآجلة - إجمالي المدفوعات
```

لا يتم الاعتماد على قيمة ثابتة قد تصبح غير متطابقة مع الحركات.

---

## Payments

إدارة التحصيل.

يدعم:

- الدفع الكامل.
- الدفع الجزئي.
- دفعات متعددة.

مثال:

```text
المبيعات = 10,000
المدفوع = 6,000
المتبقي = 4,000
```

---

## Lines

إدارة الخطوط.

يشمل:

- إضافة خط.
- بيانات الخط.
- المدفوعات.
- سجل المدفوعات.
- الرصيد.

---

## Expenses

إدارة المصروفات.

أمثلة:

```text
كهرباء
إنترنت
صيانة
نقل
مصروفات أخرى
```

---

## Owner Withdrawals

إدارة سحوبات المالك.

مثال:

```text
سحب المالك: 50,000 ريال
```

وتظهر العملية ضمن الحركة النقدية والتقارير.

---

## Cash

الحركة النقدية.

أمثلة:

```text
بيع نقدي
تحصيل دين
مصروف
سحب مالك
دفع خط
حركة نقدية أخرى
```

---

## Reports

التقارير:

- المبيعات.
- المبيعات حسب الباقة.
- المبيعات حسب الموزع.
- التحصيل.
- الديون.
- المصروفات.
- سحوبات المالك.
- الحركة النقدية.
- المخزون.
- التقرير اليومي.
- التقرير الشهري.

---

## Search

البحث في النظام حسب:

- الموزع.
- العملية.
- الباقة.
- التاريخ.
- الحركة.
- الخط.
- المدفوعات.

---

# 10. Mobile-First

التصميم يبدأ من الهاتف أولًا.

```text
Mobile
   ↓
Tablet
   ↓
Desktop
```

ويجب أن تكون جميع الصفحات قابلة للاستخدام على شاشة الهاتف دون الحاجة إلى التكبير.

يشمل ذلك:

- Dashboard.
- Forms.
- Tables.
- Navigation.
- Reports.
- Search.
- Modals.
- Buttons.

---

# 11. Arabic RTL

لغة النظام الأساسية:

```text
العربية
```

اتجاه الواجهة:

```html
<html lang="ar" dir="rtl">
```

ويجب أن يكون:

- النص عربيًا.
- المحاذاة صحيحة.
- القوائم RTL.
- الجداول مناسبة للعربية.
- النماذج مناسبة للـ RTL.
- التنقل في الهاتف مناسبًا للـ RTL.

---

# 12. التصميم

التصميم المطلوب مستوحى من واجهة نظام إدارة شبكة البرنس:

```text
Dark Sidebar
Topbar
KPI Cards
Alerts
Recent Operations
Inventory Status
Responsive Navigation
Mobile Bottom Navigation
```

ويجب أن تكون الواجهة:

- Compact.
- Modern.
- واضحة.
- غير ضخمة.
- مناسبة للاستخدام اليومي.

---

# 13. البيانات التجريبية

لا يتم وضع بيانات وهمية في النظام النهائي.

عند تثبيت المشروع لأول مرة:

```text
المبيعات = 0
المخزون = 0
المصروفات = 0
السحوبات = 0
الحركة النقدية = 0
الديون = 0
```

باستثناء بيانات Admin المطلوبة لتسجيل الدخول، إذا تم إنشاؤها ضمن Schema أو Setup.

---

# 14. قواعد البيانات المالية

البيانات المالية يجب أن تعتمد على الحركات الفعلية.

لا يفضل تخزين قيم محسوبة مثل:

```text
paid_amount
remaining_amount
balance
```

إذا كان من الممكن حسابها من السجلات المرتبطة.

مثال:

```text
remaining =
total_sales - SUM(payments)
```

هذا يقلل من احتمالية اختلاف الرصيد المخزن عن الرصيد الحقيقي.

---

# 15. الأسعار التاريخية

عند حدوث عملية بيع يجب حفظ:

```text
unit_price
```

داخل تفاصيل العملية.

حتى لو تغير سعر الباقة مستقبلًا، لا تتغير قيمة العملية القديمة.

مثال:

```text
سعر الباقة وقت البيع = 500
```

ثم تم تعديل سعر الباقة لاحقًا إلى:

```text
600
```

تبقى العملية القديمة:

```text
500
```

---

# 16. الأمان

## SQL Injection

استخدام:

```text
PDO Prepared Statements
```

ولا يتم تركيب SQL من مدخلات المستخدم مباشرة.

## XSS

يجب Escape للبيانات قبل عرضها:

```php
htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
```

## CSRF

كل العمليات التي تغير البيانات يجب أن تستخدم CSRF Token.

## Sessions

يجب:

```php
session_regenerate_id(true);
```

بعد تسجيل الدخول.

## Validation

يجب التحقق من البيانات في Backend حتى لو تم التحقق منها في JavaScript.

## Authorization

كل صفحة أو Controller محمي يجب أن يتأكد من جلسة Admin.

---

# 17. ملف `.htaccess`

يستخدم Apache لإعادة توجيه الطلبات إلى:

```text
public/index.php
```

ويهدف إلى تطبيق Front Controller.

كما يجب حماية الملفات الداخلية من الوصول المباشر عند الحاجة.

---

# 18. الاستضافة

المشروع مناسب للاستضافة التي تدعم:

```text
PHP 8+
MySQL / MariaDB
Apache
PDO MySQL
.htaccess
```

في الاستضافة الخارجية يتم ضبط:

```text
Host
Port
Database
Username
Password
```

داخل:

```text
config/database.php
```

مثال:

```php
return [
    'host' => 'sql.example.com',
    'port' => 3306,
    'database' => 'database_name',
    'username' => 'database_user',
    'password' => 'database_password',
];
```

---

# 19. XAMPP

المسار المحلي:

```text
C:\xampp\htdocs\prince-full
```

تشغيل:

```text
Apache
MySQL
```

ثم:

```text
http://localhost/prince-full/
```

إنشاء قاعدة البيانات من:

```text
http://localhost/phpmyadmin
```

ثم استيراد:

```text
database/schema.sql
```

---

# 20. النسخ الاحتياطي

يجب أن يدعم النظام مستقبلًا:

```text
Database Backup
```

وتخزين النسخ في:

```text
storage/backups/
```

ولا يجب جعل ملفات النسخ الاحتياطي قابلة للتحميل مباشرة من الإنترنت.

---

# 21. Audit Log

يجب تسجيل العمليات المهمة مستقبلًا، مثل:

```text
Login
Add Package
Update Package
Add Inventory
Sale
Payment
Expense
Owner Withdrawal
Cash Movement
Settings Change
Delete / Cancel
```

---

# 22. GitHub

المستودع:

```text
https://github.com/messi7775/prince-full
```

الفرع الرئيسي:

```text
main
```

رفع التعديلات:

```powershell
git add .
git commit -m "Update Prince Full"
git push
```

تحميل آخر نسخة:

```powershell
git pull
```

---

# 23. ملفات حساسة

لا يجب رفع:

```text
.env
```

أو:

```text
Database Passwords
```

أو:

```text
API Keys
```

أو:

```text
Private Credentials
```

إذا تم رفع كلمة مرور حقيقية إلى مستودع عام، يجب تغييرها فورًا.

---

# 24. مراحل التطوير

## المرحلة 1

```text
MVC Core
Router
Database
Request
Response
Session
```

## المرحلة 2

```text
Authentication
Admin
CSRF
Validation
Security
```

## المرحلة 3

```text
Dashboard
Packages
Inventory
```

## المرحلة 4

```text
Sales
Distributors
Payments
```

## المرحلة 5

```text
Lines
Line Payments
Expenses
Owner Withdrawals
Cash Movements
```

## المرحلة 6

```text
Reports
Search
Audit Log
Settings
```

## المرحلة 7

```text
Backup
Security Hardening
Mobile Optimization
Performance
```

---

# 25. مبدأ التطوير

Prince Full ليس مشروعًا مبنيًا على كثرة التقنيات.

المبدأ هو:

```text
PHP
+
MySQL
+
MVC
+
HTML
+
CSS
+
JavaScript
```

مع فصل واضح:

```text
Controller
    ↓
Model
    ↓
Database

Controller
    ↓
View
```

والهدف هو الحصول على نظام:

```text
بسيط
سريع
منظم
آمن
متجاوب
سهل الاستضافة
سهل الصيانة
```

---

# 26. الحالة المستهدفة

```text
Project
    Prince Full

Architecture
    MVC

Backend
    PHP 8+

Database
    MySQL / MariaDB

Frontend
    HTML5
    CSS3
    Vanilla JavaScript

UI
    Arabic
    RTL
    Mobile-First
    Responsive

Authentication
    Single Admin

Database Access
    PDO

Web Server
    Apache

Deployment
    XAMPP
    Shared Hosting : infinityfree.com or any hosting

Version Control
    Git
    GitHub
```

---

# 27. الخلاصة

**Prince Full** هو نظام إدارة شبكة ومبيعات بطاقات إنترنت مبني على PHP MVC، مع MySQL كقاعدة بيانات وواجهة عربية RTL تعمل بأسلوب Mobile-First.

المعمارية الأساسية:

```text
Browser
   ↓
public/index.php
   ↓
Router
   ↓
Controller
   ↓
Service
   ↓
Model
   ↓
MySQL
   ↓
Model
   ↓
Controller
   ↓
View
   ↓
Browser
```

ويجب الحفاظ على هذا الفصل طوال عملية تطوير المشروع.
