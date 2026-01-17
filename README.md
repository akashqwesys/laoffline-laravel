## {{ Project Name }} - Laravel Project with Vue.js

This project is a Laravel application built with Laravel Mix and Vue.js.

### Requirements

  * PHP \>= 8.0.2
  * Node.js \>= 16
  * Composer

### Installation

1.  Clone the repository:

```sh
git clone https://your-repository-url.git
```

2.  Navigate to the project directory:

```sh
cd {{ Project Name }}
```

3.  Install dependencies:

```sh
composer install
```

4.  Install Node.js dependencies:

```sh
npm install
```

5.  Generate application key:

```sh
php artisan key:generate
```

6.  Configure database connection in `.env` file.

7.  Migrate and seed database (if applicable):

```sh
php artisan migrate
php artisan db:seed (optional)
```

8.  Run development server:

```sh
# phpbrew use php-8.1.31
php artisan serve

# nvm use 16
npx mix watch
```

## Import database

```sh
task import-db DB_FILE=20250329.pgdump
```

This will start the development server at http://localhost:8000.

### Laravel Mix and Vue.js

This project uses Laravel Mix for compiling frontend assets and Vue.js for building the user interface. Refer to the official documentation for further details:

  * Laravel Mix: [https://laravel.com/docs/9.x/mix](https://laravel.com/docs/9.x/mix)
  * Vue.js: [https://vuejs.org/](https://vuejs.org/)

**Note:**

  * Replace `{{ Project Name }}` with your actual project name.
  * You can adjust the instructions based on your specific project setup (e.g., if you have custom commands for seeding data).

This README.md file provides a basic overview of your Laravel project with the mentioned dependencies. You can further customize it to include additional information like project features, testing instructions, deployment steps, etc.
