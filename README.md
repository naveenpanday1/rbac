# 🔗 URL Shortener Application (Laravel)

A role-based URL Shortener web application built with **Laravel**, implementing  
**RBAC (Role-Based Access Control)** with **SuperAdmin, Admin, and Member** roles.

---

## 🚀 Features

### 🔐 Authentication
- User Login & Registration
- Secure password hashing
- Profile management

### 👥 Role-Based Access Control (RBAC)

| Role | Permissions |
|------|------------|
| **SuperAdmin** | Manage users & roles |
| **Admin** | Create & manage short URLs (company-wise) |
| **Member** | Create & manage own short URLs |

> ❗ SuperAdmin **cannot create short URLs** (business rule + tests verified)

---

### 🔗 URL Shortening
- Generate short URLs
- Redirect short → original URL
- Public URL resolution
- CSV export (Admin)

---

### 📊 Dashboard
- Role-based dashboards
- URL filtering
- Clean UI using Tailwind CSS

---

## 🛠️ Tech Stack

- **Framework:** Laravel
- **Frontend:** Blade + Tailwind CSS
- **Database:** MySQL
- **Authentication:** Laravel Auth
- **Testing:** PHPUnit
- **Version Control:** Git

---

## ⚙️ Installation

```bash
git clone https://github.com/naveenpanday1/rbac.git
cd rbac
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev
npm run build
php artisan migrate:fresh --seed
php artisan serve

### SuperAdmin
Email: superadmin@demo.com  
Password: demo123
