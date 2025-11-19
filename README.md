# Devkaki National Bug Report Platform

A comprehensive bug reporting platform for the Developer Kaki community in Malaysia. This platform allows developers to report, track, and discuss bugs found in major Malaysian applications, built with OWASP security best practices.

## Tech Stack

- **Backend**: PHP 8.4 + Laravel 12
- **Frontend**: Vue.js 3 + Vue Router + Pinia
- **Database**: SQLite
- **Authentication**: Laravel Sanctum
- **Styling**: Tailwind CSS 4
- **Build Tool**: Vite 7

## Features

### Core Functionality
- **Bug Reporting**: Submit detailed bug reports with attachments, severity levels, and reproduction steps
- **Categorization**: Organize bugs by app and category
- **Voting System**: Upvote bugs to indicate severity and priority
- **Comments & Discussions**: Threaded comments with nested replies
- **Search & Filtering**: Advanced search and filtering by app, category, status, and severity
- **User Roles**: User, Moderator, and Admin roles with appropriate permissions

### Security Features (OWASP Compliant)
- **Input Validation**: Comprehensive form request validators for all user inputs
- **SQL Injection Protection**: Laravel Eloquent ORM with parameterized queries
- **XSS Protection**: Laravel's built-in output escaping
- **CSRF Protection**: Laravel's CSRF token middleware
- **Authentication**: Secure token-based authentication with Laravel Sanctum
- **Authorization**: Role-based access control (RBAC)
- **Password Hashing**: Bcrypt password hashing

## Database Schema

### Tables
1. **users** - User accounts with roles (user, moderator, admin)
2. **apps** - Malaysian applications that can have bugs reported
3. **categories** - Bug categories (UI, Performance, Security, etc.)
4. **bugs** - Bug reports with full details
5. **comments** - Comments and replies on bugs
6. **votes** - User votes on bugs

### Key Relationships
- Users can report multiple bugs
- Bugs belong to an app and category
- Bugs can have multiple comments and votes
- Comments can have nested replies (parent-child relationship)

## API Endpoints

### Public Routes
```
GET  /api/apps                 - List all active apps
GET  /api/apps/{id}            - Get app details
GET  /api/categories           - List all categories
GET  /api/categories/{id}      - Get category details
GET  /api/bugs                 - List bugs (with filters)
GET  /api/bugs/{id}            - Get bug details
POST /api/register             - Register new user
POST /api/login                - Login user
```

### Authenticated Routes
```
POST   /api/logout              - Logout current user
GET    /api/me                  - Get current user
POST   /api/bugs                - Create bug report
PUT    /api/bugs/{id}           - Update bug
DELETE /api/bugs/{id}           - Delete bug
POST   /api/comments            - Add comment
PUT    /api/comments/{id}       - Update comment
DELETE /api/comments/{id}       - Delete comment
POST   /api/bugs/{id}/vote      - Toggle vote on bug
GET    /api/bugs/{id}/vote/check - Check if user voted
```

### Admin Routes
```
POST   /api/apps                - Create new app
PUT    /api/apps/{id}           - Update app
DELETE /api/apps/{id}           - Delete app
POST   /api/categories          - Create category
PUT    /api/categories/{id}     - Update category
DELETE /api/categories/{id}     - Delete category
```

## Installation

### Prerequisites
- PHP 8.4+
- Composer
- Node.js 18+
- SQLite PHP extension

### Setup Steps

1. **Install PHP dependencies**
   ```bash
   composer install
   ```

2. **Install Node dependencies**
   ```bash
   npm install --legacy-peer-deps
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

5. **Seed Database (Optional)**
   Create sample data:
   ```bash
   php artisan db:seed
   ```

6. **Build Frontend Assets**
   ```bash
   npm run dev    # Development
   npm run build  # Production
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

   Access the application at `http://localhost:8000`

## Project Structure

```
devkaki-national-bug-report/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php
│   │   │   ├── BugController.php
│   │   │   ├── CommentController.php
│   │   │   ├── VoteController.php
│   │   │   ├── AppController.php
│   │   │   └── CategoryController.php
│   │   └── Requests/
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       ├── StoreBugRequest.php
│   │       ├── UpdateBugRequest.php
│   │       └── StoreCommentRequest.php
│   └── Models/
│       ├── User.php
│       ├── Bug.php
│       ├── Comment.php
│       ├── Vote.php
│       ├── App.php
│       └── Category.php
├── database/
│   ├── migrations/
│   └── database.sqlite
├── resources/
│   ├── js/
│   │   ├── components/     # Vue components
│   │   ├── views/          # Vue pages
│   │   ├── stores/         # Pinia stores
│   │   ├── router/         # Vue Router
│   │   └── services/       # API services
│   └── css/
│       └── app.css
├── routes/
│   ├── api.php            # API routes
│   └── web.php            # Web routes
└── public/
```

## Models & Relationships

### User Model
- `hasMany` bugs, comments, votes
- Methods: `isAdmin()`, `isModerator()`

### Bug Model
- `belongsTo` user, app, category
- `hasMany` comments, votes
- Scopes: `status()`, `severity()`

### Comment Model
- `belongsTo` user, bug, parent (self-referential)
- `hasMany` replies (self-referential)

### Vote Model
- `belongsTo` user, bug
- Unique constraint on `[user_id, bug_id]`

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint    # PHP code formatting
```

### Frontend Development
```bash
npm run dev          # Watch mode
npm run build        # Production build
```

## Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Run migrations: `php artisan migrate --force`
4. Build assets: `npm run build`
5. Optimize Laravel: `php artisan optimize`
6. Set proper file permissions

## Security Considerations

- All user inputs are validated using Form Request classes
- SQL injection protection via Eloquent ORM
- XSS protection via Laravel's Blade templating
- CSRF tokens required for all state-changing requests
- Password hashing with Bcrypt
- API authentication with Sanctum tokens
- Role-based authorization checks in controllers

## Contributing

This is a community project for Developer Kaki. Contributions are welcome!

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is open-source software licensed under the MIT license.

## Support

For issues and questions, please open an issue on GitHub or contact the Developer Kaki community.

---

**Built with ❤️ by the Developer Kaki community**
