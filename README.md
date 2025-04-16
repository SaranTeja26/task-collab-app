# 🧩 Task Collaboration App

A **lightweight and efficient Task Collaboration App** built using **HTML, CSS (TailwindCSS), JavaScript, PHP, and MySQL**. Designed for both **individual users** to manage their tasks and **admins** to oversee all tasks system-wide.

> 🎯 Submitted for the Web Developer Internship Assignment  
> 📅 Due Date: April 16, 2025  
> 💻 Stack Used: HTML, CSS, JavaScript, PHP, MySQL  

---

## ✅ Core Features Implemented

### 👤 User Authentication
- Secure login using **email & password**
- **Password hashing** with PHP's `password_hash()`
- **Session management** for persistent login
- Admins have **elevated access** to all tasks and users

### 📝 Task Management

#### 🛡️ Admin Role
- View **all users** and their tasks
- **Delete or edit any task**

#### 🙋 User Role
- Create new tasks: `Title`, `Deadline`, `Priority (High/Medium/Low)`
- Edit or delete **own tasks only**
- View all personal tasks in a clean list

### 🧑‍💻 Frontend
- Built using **TailwindCSS** for a clean, modern UI
- **Responsive design** for both desktop and mobile
- AJAX-based task actions (delete/edit) — **no full-page reloads**

---

## 🧠 Database Schema

Two core tables used:

### `users`  
| Field    | Type             |
|----------|------------------|
| id       | INT (Primary Key)|
| name     | VARCHAR          |
| email    | VARCHAR (Unique) |
| password | VARCHAR (Hashed) |
| role     | ENUM('user','admin') |

### `tasks`  
| Field     | Type                        |
|-----------|-----------------------------|
| id        | INT (Primary Key)           |
| user_id   | INT (Foreign Key)           |
| title     | VARCHAR                     |
| deadline  | DATE                        |
| priority  | ENUM('High','Medium','Low') |
| status    | ENUM('Pending','Completed') |

> 📎 SQL dump with **sample users and tasks** included at: `db/task_collab.sql`

---

## 🛠️ Setup Instructions

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/SaranTeja26/task-collab-app.git
cd task-collab-app
```
2️⃣ Set Up the Database

Open phpMyAdmin

Create a new database:

CREATE DATABASE task_collab;

3️⃣ Configure the Database Connection

Edit config/db.php and update your local credentials:
```
$host = 'localhost';
$db   = 'task_collab';
$user = 'root';    // default for XAMPP
$pass = '';        // leave blank for XAMPP
```


🔐 Sample Login Credentials
```
Role	Email	Password
Admin	admin@demo.com	admin123
User	user@test.com	user123
```