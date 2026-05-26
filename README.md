# Expense Splitter System

A modern Splitwise-inspired Expense Splitter web application built using Laravel and Tailwind CSS.
This project helps users manage shared expenses, groups, balances, and settlements in a clean and responsive interface.

---

## ✨ Features

* User Authentication
* Create & Manage Groups
* Add Shared Expenses
* Expense Categories
* Shared Balance Tracking
* Dashboard Analytics
* Monthly Expense Charts
* Category Doughnut Charts
* Receipt Upload & Preview
* Responsive Mobile-Friendly UI
* Group Snapshots
* “Who Owes Whom” Settlements
* Indian Rupee (₹) Currency Support
* Modern Sidebar Navigation
* Clean Dashboard Design
* Hover Effects & Microinteractions

---

## 🛠️ Tech Stack

### Frontend

* Blade Templates
* Tailwind CSS
* JavaScript
* Chart.js

### Backend

* Laravel 12
* PHP
* SQLite / MySQL

---


### Dashboard

* KPI cards
* Expense analytics
* Spending trends
* Category breakdown charts

### Groups

* Group cards
* Member statistics
* Shared expense management

### Expenses

* Expense history
* Receipt previews
* Category badges
* Responsive tables

---

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/vivekraj3456/Spilitwise-system.git
cd Spilitwise-system
```

---

### 2. Install Dependencies

```bash
composer install
npm install
```

---

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

---

### 4. Configure Database

Update `.env` file:

```env
DB_CONNECTION=sqlite
```

OR configure MySQL credentials.

---

### 5. Run Migrations

```bash
php artisan migrate
```

---

### 6. Storage Link

```bash
php artisan storage:link
```

---

### 7. Run Development Server

```bash
php artisan serve
npm run dev
```

---

## 📊 Project Modules

| Module          | Description                 |
| --------------- | --------------------------- |
| Dashboard       | Analytics, balances, charts |
| Groups          | Manage shared groups        |
| Expenses        | Track expenses and receipts |
| Authentication  | Login & registration system |
| Shared Balances | Settlement calculations     |

---

## 🎨 UI/UX Highlights

* Clean modern layout
* Splitwise-inspired design
* Soft shadows & rounded cards
* Responsive dashboard
* Modern charts & analytics
* Mobile-friendly navigation
* Minimal and polished interface

---

## 📁 Folder Structure

```bash
app/
resources/views/
routes/
database/
public/
storage/
```

---

## 🔮 Future Improvements

* Dark Mode
* Settle Up Feature
* CSV/PDF Export
* Activity Feed
* Guest Member Support
* Advanced Analytics

---

## 👨‍💻 Author

**Vivek Raj**

GitHub:
https://github.com/vivekraj3456

---

## 📌 Project Purpose

This project was developed as a college-level full-stack web application project to demonstrate:

* Laravel backend development
* Database management
* Responsive UI/UX design
* Dashboard analytics
* Real-world expense management workflows
