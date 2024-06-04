## setup
composer update
npm i
npm run watch
cp .env.example .env
php artisan key:generate
php artisan config:cache

## start
php artisan serve

## file structure
### routes
.
├── api -----------------------------------------routes location for api.
├── web -----------------------------------------routes location for web.
│   └── common.php ------------------------------common routes.
└── auth.php ------------------------------------auth routes.
## Controllers
.
├── Web -----------------------------------------controllers location for web.
├── Api -----------------------------------------controllers location for api.
└── Auth ----------------------------------------controllers location for auth.