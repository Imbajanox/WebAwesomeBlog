# WebAwesome Blog - Architecture Overview

## 📐 System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        FRONTEND                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  index.php   │  │   post.php   │  │  script.js   │      │
│  │  (Homepage)  │  │ (Post View)  │  │ (Dark Mode)  │      │
│  └──────┬───────┘  └──────┬───────┘  └──────────────┘      │
│         │                  │                                 │
└─────────┼──────────────────┼─────────────────────────────────┘
          │                  │
          ▼                  ▼
┌─────────────────────────────────────────────────────────────┐
│                    BACKEND (PHP)                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              includes/posts.php                       │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │  │
│  │  │ getAllPosts │  │ getPostById │  │ createPost  │  │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              includes/auth.php                        │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │  │
│  │  │   login()   │  │  logout()   │  │ checkAuth() │  │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │           includes/database.php                       │  │
│  │              ┌─────────────┐                          │  │
│  │              │ Database    │                          │  │
│  │              │ Connection  │                          │  │
│  │              └─────────────┘                          │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────┬───────────────────────────────────────┘
                      │ PDO
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                    DATABASE (MySQL)                          │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐              │
│  │   users   │  │categories │  │   posts   │              │
│  ├───────────┤  ├───────────┤  ├───────────┤              │
│  │ id        │  │ id        │  │ id        │              │
│  │ username  │  │ name      │  │ title     │              │
│  │ password  │  │ slug      │  │ slug      │              │
│  │ email     │  │ color     │  │ content   │              │
│  └───────────┘  └───────────┘  │ author_id │──┐           │
│                                 │ category  │──┼──┐        │
│                                 └───────────┘  │  │        │
│                                      │         │  │        │
│                                      └─────────┘  │        │
│                                           └───────┘        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                     ADMIN PANEL                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  login.php   │  │  index.php   │  │post-edit.php │      │
│  │  (Login)     │  │ (Dashboard)  │  │ (CRUD)       │      │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘      │
│         │                  │                  │              │
│         └──────────────────┼──────────────────┘              │
│                            │                                 │
│                            ▼                                 │
│                   ┌─────────────────┐                        │
│                   │  Session Auth   │                        │
│                   │   checkAuth()   │                        │
│                   └─────────────────┘                        │
└─────────────────────────────────────────────────────────────┘
```

## 🔄 Request Flow

### Frontend Post Display
```
User visits index.php
    ↓
index.php requires includes/posts.php
    ↓
getAllPosts($db, true) called
    ↓
Database query executed (published posts only)
    ↓
Posts rendered in HTML with WebAwesome components
    ↓
User sees blog posts
```

### Admin Login Flow
```
User visits admin/login.php
    ↓
User submits credentials
    ↓
login() function in auth.php called
    ↓
Password verified with password_verify()
    ↓
Session created with user_id
    ↓
Redirect to admin/index.php (dashboard)
```

### Admin Post Creation Flow
```
User clicks "New Post" in dashboard
    ↓
Form displayed (admin/post-edit.php)
    ↓
User fills form and submits
    ↓
Data validated in PHP
    ↓
createPost() function called
    ↓
Slug auto-generated if empty
    ↓
Post inserted into database
    ↓
Redirect to dashboard with success message
```

## 🔐 Security Layers

```
┌─────────────────────────────────────────┐
│  Layer 1: Input Validation              │
│  - Required field checks                │
│  - Data type validation                 │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│  Layer 2: SQL Injection Prevention      │
│  - PDO Prepared Statements              │
│  - Parameter binding                    │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│  Layer 3: XSS Prevention                │
│  - htmlspecialchars() on output         │
│  - Content sanitization                 │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│  Layer 4: Authentication                │
│  - Password hashing (bcrypt)            │
│  - Session management                   │
│  - HTTP-only cookies                    │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│  Layer 5: Access Control                │
│  - checkAuth() on admin pages           │
│  - Session timeout                      │
│  - .htaccess restrictions               │
└─────────────────────────────────────────┘
```

## 🗂️ Data Flow

### Creating a Post

```
Admin Interface                Backend                    Database
─────────────                ──────────                  ────────
     │                           │                          │
     │ Submit Form               │                          │
     ├──────────────────────────►│                          │
     │                           │ Validate Input           │
     │                           │                          │
     │                           │ Generate Slug            │
     │                           │                          │
     │                           │ createPost()             │
     │                           ├─────────────────────────►│
     │                           │                          │ INSERT
     │                           │                          │
     │                           │◄─────────────────────────┤
     │                           │ Success/Fail             │
     │◄──────────────────────────┤                          │
     │ Redirect/Error            │                          │
     │                           │                          │
```

### Displaying Posts

```
User Browser                 Frontend                   Database
────────────                ────────                   ────────
     │                          │                          │
     │ GET /index.php           │                          │
     ├─────────────────────────►│                          │
     │                          │ getAllPosts()            │
     │                          ├─────────────────────────►│
     │                          │                          │ SELECT
     │                          │                          │
     │                          │◄─────────────────────────┤
     │                          │ Post Array               │
     │                          │                          │
     │                          │ Render HTML              │
     │◄─────────────────────────┤                          │
     │ HTML Response            │                          │
     │                          │                          │
```

## 🎨 UI Component Structure

```
WebAwesome Components Used:
├── wa-button
│   ├── variant: primary, secondary, ghost, danger
│   └── size: small, medium, large
├── wa-badge
│   └── variant: info, warning, success, primary, light
├── wa-input
│   ├── type: text, email, url, password
│   └── validation: required, pattern
├── wa-textarea
│   └── rows: configurable
└── wa-card
    └── padding: configurable

Font Awesome Icons:
├── Navigation: fa-code, fa-house, fa-arrow-left
├── Actions: fa-pen, fa-trash, fa-plus, fa-save
├── Status: fa-check-circle, fa-triangle-exclamation
├── UI: fa-moon, fa-sun, fa-user, fa-gauge
└── Content: fa-book-open-reader, fa-share-nodes
```

## 📊 Database Relationships

```
users (1) ────────┐
                  │
                  │ author_id
                  ▼
              posts (N)
                  ▲
                  │ category_id
                  │
categories (1) ────┘

Cascade Rules:
- Delete user → Delete all their posts
- Delete category → Set posts.category_id to NULL
```

## 🚀 Deployment Checklist

```
1. Server Setup
   ├── Install PHP 7.4+
   ├── Install MySQL/MariaDB
   ├── Configure Apache/Nginx
   └── Enable required PHP extensions (PDO, pdo_mysql)

2. Application Setup
   ├── Upload files to server
   ├── Create database
   ├── Import schema.sql
   ├── Copy config.example.php to config.php
   ├── Configure database credentials
   └── Set proper file permissions

3. Security Configuration
   ├── Change default admin password
   ├── Enable HTTPS
   ├── Configure .htaccess
   ├── Delete test-db.php
   └── Verify config.php is protected

4. Testing
   ├── Test database connection
   ├── Test admin login
   ├── Create test post
   ├── Verify frontend display
   └── Test all CRUD operations

5. Production
   ├── Disable error display
   ├── Enable error logging
   ├── Configure backups
   ├── Monitor logs
   └── Regular security updates
```

---

**Architecture Designed**: October 2025  
**Stack**: PHP + MySQL + WebAwesome + Font Awesome
