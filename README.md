# Prince Full

نظام إدارة متكامل للكروت والباقات والمبيعات والموزعين والحسابات، مبني باستخدام PHP MVC ومصمم بشكل Mobile-First ليعمل بشكل ممتاز على الهواتف والأجهزة اللوحية والكمبيوتر.

## Stack

- PHP 8+
- MySQL 8+ / MariaDB
- HTML5
- CSS3
- Vanilla JavaScript
- PHP MVC Architecture
- PDO
- Sessions
- `password_hash()` / `password_verify()`
- Responsive Mobile-First UI
- Arabic RTL
- No framework
- No Node.js
- No React
- No TypeScript
- No Laravel
- No PostgreSQL

## Architecture

المشروع يستخدم بنية MVC واضحة:

```text
prince-full/
│
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   ├── Core/
│   ├── Services/
│   └── Helpers/
│
├── config/
│   ├── database.php
│   └── app.php
│
├── public/
│   ├── index.php
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── .htaccess
│
├── database/
│   └── schema.sql
│
├── storage/
│   ├── logs/
│   └── backups/
│
└── README.md