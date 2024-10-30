// index.php
<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Redirect to profile page if authenticated
header('Location: /profile.php');
exit();
?>

// .env
DB_HOST=localhost
DB_NAME=nbi30_marketplace
DB_USER=root
DB_PASS=
APP_ENV=development
FACEBOOK_APP_ID=your_facebook_app_id
FACEBOOK_APP_SECRET=your_facebook_app_secret
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret

// composer.json
{
    "name": "nbi30/marketplace",
    "description": "NBI30 Marketplace Platform",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "vlucas/phpdotenv": "^5.5",
        "facebook/graph-sdk": "^5.7",
        "google/apiclient": "^2.12"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "authors": [
        {
            "name": "Your Name",
            "email": "your.email@example.com"
        }
    ],
    "minimum-stability": "stable"
}

// .gitignore
/vendor/
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
.DS_Store
*.log
*.cache
/node_modules/
/.idea/
/.vscode/

// README.md
# NBI30 Marketplace

## Setup Instructions

1. Clone the repository
2. Copy `.env.example` to `.env` and update the configuration
3. Install dependencies:
   ```bash
   composer install
   ```
4. Set up the database:
   - Create a new MySQL database
   - Import the schema from `database/schema.sql`
   - Update database credentials in `.env`

5. Configure OAuth:
   - Set up projects in Facebook and Google Developer Consoles
   - Update OAuth credentials in `.env`

6. Start the development server:
   ```bash
   php -S localhost:8000 -t public
   ```

## Features
- User authentication (email/password)
- Social login (Facebook, Google)
- User profiles
- Product marketplace

## Directory Structure
```
project_root/
├── assets/         # Static assets (CSS, JS, images)
├── auth/           # Authentication handlers
├── includes/       # PHP includes and configuration
├── public/         # Public-facing files
└── vendor/         # Composer dependencies
```

## Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx)

## Contributing
Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

## License
This project is licensed under the MIT License - see the LICENSE.md file for details.
