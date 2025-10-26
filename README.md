# WebAwesome Blog - Mini CMS

A lightweight blog CMS built with PHP, MySQL, and WebAwesome components.

## Features

- 📝 **Blog Post Management**: Create, edit, and delete blog posts
- 🔐 **Admin Panel**: Secure admin control panel with authentication
- 🎨 **WebAwesome Components**: Modern UI using WebAwesome component library
- 📱 **Responsive Design**: Works on desktop, tablet, and mobile devices
- 🌙 **Dark Mode**: Toggle between light and dark themes
- 📂 **Categories**: Organize posts with categories
- 🔍 **SEO Friendly**: Clean URLs with slugs

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB 10.2+)
- Apache/Nginx web server
- PDO PHP Extension

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Imbajanox/WebAwesomeBlog.git
cd WebAwesomeBlog
```

### 2. Database Setup

Create a MySQL database and import the schema:

```bash
mysql -u your_username -p
```

```sql
CREATE DATABASE webawesome_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webawesome_blog;
SOURCE schema.sql;
```

Or import via phpMyAdmin or your preferred MySQL client.

### 3. Configure Database Connection

Copy the example configuration file:

```bash
cp config.example.php config.php
```

Edit `config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'webawesome_blog');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 4. Set Up Web Server

#### Apache

Make sure `.htaccess` is enabled and `mod_rewrite` is active. The document root should point to the project directory.

#### Nginx

Add a server block configuration:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/WebAwesomeBlog;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 5. Access the Application

- **Frontend**: `http://your-domain.com/index.php`
- **Admin Panel**: `http://your-domain.com/admin/login.php`

## Default Admin Credentials

**⚠️ IMPORTANT: Change these credentials immediately after first login!**

- **Username**: `admin`
- **Password**: `admin123`

To change the password, you can update it directly in the database using PHP's password_hash():

```php
<?php
echo password_hash('your_new_password', PASSWORD_DEFAULT);
?>
```

Then update the password field in the `users` table.

## Usage

### Admin Panel

1. Navigate to `/admin/login.php`
2. Log in with your credentials
3. From the dashboard you can:
   - Create new blog posts
   - Edit existing posts
   - Delete posts
   - Toggle post publication status
   - Organize posts by categories

### Creating a Blog Post

1. Click "Neuer Beitrag" (New Post) in the admin dashboard
2. Fill in the form:
   - **Title**: Post title (required)
   - **URL Slug**: Auto-generated from title, or enter custom slug
   - **Category**: Select a category
   - **Excerpt**: Short description shown in post listings
   - **Content**: Main post content (supports HTML)
   - **Image URL**: Optional header image
   - **Published**: Check to publish immediately, uncheck to save as draft
3. Click "Erstellen" (Create) to save

### Managing Categories

Categories are pre-populated in the database. To add more categories, insert them directly into the `categories` table:

```sql
INSERT INTO categories (name, slug, color) VALUES 
('Your Category', 'your-category', 'primary');
```

Available colors: `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`

## Project Structure

```
WebAwesomeBlog/
├── admin/                  # Admin panel
│   ├── index.php          # Dashboard
│   ├── login.php          # Login page
│   ├── logout.php         # Logout handler
│   └── post-edit.php      # Post editor
├── includes/              # PHP includes
│   ├── auth.php          # Authentication functions
│   ├── database.php      # Database connection
│   └── posts.php         # Post management functions
├── index.php             # Homepage
├── post.php              # Individual post view
├── script.js             # Frontend JavaScript
├── style.css             # Styles
├── schema.sql            # Database schema
├── config.example.php    # Example configuration
└── config.php            # Configuration (ignored by git)
```

## Security Notes

1. **Change Default Password**: Immediately change the default admin password
2. **Database Credentials**: Never commit `config.php` to version control
3. **HTTPS**: Use HTTPS in production
4. **Session Security**: Sessions are configured with httponly cookies
5. **SQL Injection**: All database queries use prepared statements
6. **XSS Protection**: Output is escaped with `htmlspecialchars()`

## Customization

### Styling

Edit `style.css` to customize the appearance. The theme uses CSS variables for easy customization:

```css
:root {
  --bg: #f5f6f8;
  --card: #ffffff;
  --primary: #0b78ff;
  --accent: #2dd4bf;
  --text: #111827;
}
```

### WebAwesome Components

This project uses [WebAwesome](https://webawesome.com/) components. Refer to their [documentation](https://webawesome.com/docs/) for available components and customization options.

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Components**: WebAwesome 3.0
- **Icons**: Font Awesome 6.5

## License

This project is open source and available for personal and commercial use.

## Support

For issues, questions, or contributions, please open an issue on GitHub.

## Credits

Created by Imbajanox
