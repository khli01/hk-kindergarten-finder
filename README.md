# HK Kindergarten Finder 香港幼稚園搜尋

A Laravel-based web application to help Hong Kong parents find and compare kindergartens for their children.

## Features

- **Multilingual Support**: Traditional Chinese (繁體中文), Simplified Chinese (简体中文), and English
- **Kindergarten Search**: Filter by district, class type (PN/K1-K3), ranking, and more
- **School Details**: View rankings, primary school success rates, fees, and features
- **Registration Deadlines**: Track application deadlines with automated web scraping
- **User Features**: 
  - Email registration with Mailgun verification
  - Save favorite schools
  - Submit private feedback for AI training
- **Admin Panel**: 
  - Manage kindergartens, deadlines, and users
  - Import/export data via CSV
  - Web scraper management
  - View and export parent suggestions

## Requirements

- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM (for frontend assets)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd hk-kindergarten-finder
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Configure environment variables**
   
   Edit `.env` and update:
   - Database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
   - Mailgun credentials (MAILGUN_DOMAIN, MAILGUN_SECRET)
   - App URL (APP_URL)

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database**
   ```bash
   php artisan db:seed
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## Default Admin Account

After seeding, you can log in with:
- Email: admin@hk-kindergarten.com
- Password: password

**Important**: Change this password immediately in production!

## Configuration

### Mailgun Setup

1. Sign up for a Mailgun account at https://www.mailgun.com
2. Add your domain and verify DNS records
3. Get your API key from the Mailgun dashboard
4. Update `.env`:
   ```
   MAIL_MAILER=mailgun
   MAILGUN_DOMAIN=your-domain.com
   MAILGUN_SECRET=your-api-key
   ```

### Scheduled Tasks

Add the following cron entry to run scheduled tasks (including the deadline scraper):

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker

For background job processing (emails, scraping):

```bash
php artisan queue:work
```

In production, use Supervisor to manage the queue worker.

## Import Data

### CSV Import Format

To import kindergartens via CSV, use this column format:

```
name_zh_tw, name_zh_cn, name_en, district_name_en, address_zh_tw, address_zh_cn, address_en, website_url, has_pn, has_k1, has_k2, has_k3, primary_success_rate, ranking_score, phone, email, school_type
```

Download a template from the admin panel at `/admin/import`.

## Directory Structure

```
app/
├── Console/Commands/      # Artisan commands (scraper)
├── Http/Controllers/      # Web controllers
│   ├── Admin/            # Admin panel controllers
│   └── Auth/             # Authentication controllers
├── Jobs/                  # Background jobs
├── Mail/                  # Email classes
├── Models/               # Eloquent models
└── Services/             # Business logic services

resources/
├── lang/                 # Language files (en, zh-TW, zh-CN)
└── views/                # Blade templates
    ├── admin/            # Admin panel views
    ├── auth/             # Authentication views
    ├── components/       # Reusable components
    └── layouts/          # Layout templates

database/
├── migrations/           # Database migrations
└── seeders/             # Database seeders
```

## API Endpoints

This application currently uses web routes only. A RESTful API can be added using Laravel Sanctum (already included).

## Security

- CSRF protection on all forms
- Email verification required for full access
- Rate limiting on login attempts
- Admin routes protected by middleware
- Passwords hashed with bcrypt
- Suggestions are private (not publicly visible)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open-source software licensed under the MIT license.

## Support

For questions or issues, please open a GitHub issue or contact info@hk-kindergarten.com.
