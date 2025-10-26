# Quick Start Guide

Get your WebAwesome Blog up and running in 5 minutes!

## 📦 What You Need

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Apache or Nginx web server
- A terminal/command prompt

## ⚡ Quick Setup

### 1. Database Setup (2 minutes)

```bash
# Login to MySQL
mysql -u root -p

# Create database and import schema
CREATE DATABASE webawesome_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webawesome_blog;
SOURCE schema.sql;
exit
```

### 2. Configuration (1 minute)

```bash
# Copy configuration template
cp config.example.php config.php

# Edit config.php with your database credentials
nano config.php  # or use your preferred editor
```

Update these values in `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'webawesome_blog');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. Test Connection (30 seconds)

Visit in your browser:
```
http://localhost/test-db.php
```

You should see all green checkmarks! ✅

**Important:** Delete `test-db.php` after successful test.

### 4. Access Your Blog (30 seconds)

**Frontend:**
```
http://localhost/index.php
```

**Admin Panel:**
```
http://localhost/admin/login.php
```

**Login Credentials:**
- Username: `admin`
- Password: `admin123`

**⚠️ IMPORTANT:** Change this password immediately!

### 5. Change Admin Password (1 minute)

Create a new password hash:
```bash
php -r "echo password_hash('YourNewPassword123', PASSWORD_DEFAULT);"
```

Update the database:
```sql
mysql -u root -p webawesome_blog
UPDATE users SET password = 'YOUR_HASH_HERE' WHERE username = 'admin';
exit
```

## 🎯 Your First Blog Post

1. Go to `http://localhost/admin/login.php`
2. Login with your credentials
3. Click "Neuer Beitrag" (New Post)
4. Fill in:
   - **Title**: Your post title
   - **Category**: Select one
   - **Excerpt**: Short description
   - **Content**: Your post content (supports HTML)
   - Check "Sofort veröffentlichen" to publish
5. Click "Erstellen" (Create)
6. Visit your homepage to see it live!

## 🎨 Customization Tips

### Change Colors
Edit `style.css`:
```css
:root {
  --primary: #0b78ff;  /* Your primary color */
  --accent: #2dd4bf;   /* Your accent color */
}
```

### Add a Category
```sql
INSERT INTO categories (name, slug, color) 
VALUES ('Tutorial', 'tutorial', 'info');
```

Available colors: `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`

## 🐛 Troubleshooting

### Can't connect to database?
- Check if MySQL is running: `systemctl status mysql`
- Verify credentials in `config.php`
- Ensure database exists: `SHOW DATABASES;`

### Admin login not working?
- Check password is correct
- Verify session is enabled: `php -i | grep session`
- Clear browser cookies

### Posts not showing?
- Check posts are published in admin panel
- Verify database has posts: `SELECT * FROM posts;`

### 404 errors?
- Enable mod_rewrite in Apache
- Check `.htaccess` is loaded
- Verify file permissions

## 📚 Next Steps

- Read [README.md](README.md) for detailed documentation
- Check [IMPLEMENTATION.md](IMPLEMENTATION.md) for features
- Review [ARCHITECTURE.md](ARCHITECTURE.md) for system design
- See [FILES_CREATED.md](FILES_CREATED.md) for file details

## 🔐 Security Checklist

Before going live:
- [ ] Change admin password
- [ ] Delete `test-db.php`
- [ ] Enable HTTPS
- [ ] Update `config.php` with production values
- [ ] Set proper file permissions
- [ ] Configure error logging
- [ ] Backup database regularly

## 🚀 Production Deployment

For production use:
1. Use a strong admin password
2. Enable HTTPS with SSL certificate
3. Configure proper error logging
4. Set up regular database backups
5. Keep PHP and MySQL updated
6. Monitor server logs

## 💡 Tips

- Use the dark mode toggle in the header
- WebAwesome components are customizable - check their docs
- HTML is allowed in post content for formatting
- Slugs are auto-generated from titles
- Categories can be color-coded

## 🎉 You're Ready!

Your blog is now running! Start creating amazing content!

Need help? Check the documentation or create an issue on GitHub.

---

**Setup Time**: ~5 minutes  
**Difficulty**: Easy  
**Fun Factor**: 💯
