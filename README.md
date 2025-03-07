# RestoMate

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a>
</p>

## About Restaurant Order Management System

The Restaurant Order Management System is a modern, robust application built with Laravel for managing restaurant orders, products, customers, and companies. The application follows SOLID principles and modular architecture to ensure scalability and maintainability.

## My Development Discipline & Process Management

- **Find Requirements**: First, I decided which packages I should use in the project. I used Spatie for role and permission management, and Sanctum for API Authorization.
- **DB Design**: Once I had decided on the packages, I designed the database schema based on the project's requirements. I defined the tables, their relationships, and used migrations to implement them.
-**Task Breakdown**: After I designed the database, I broke it into smaller tasks that I can work on to finish the project. And I created a TODO list in NotionAI to track progress and priorities.
-**Branching** For each task, I created a separate branch from the main branch, and named them after the task number. I did git commit after each task I finished and did the merge back to master.
-**Debugging**: As I completed tasks, I would run the project to ensure everything was working as it should. I created intermediate tasks every time I felt a need to squash a bug/make something better so that the project could flow efficiently.
-**Optimizing & Refactoring**: Followed up with an optimization of the code to refactor everything that was possible to make sure the code was clean and maintainable.

## Features

- **User Management**: Role-based access control system with permissions using Spatie's Laravel-Permission package
- **Restaurant Management**: Create and manage companies with associated addresses
- **Product Catalog**: Maintain a catalog of food products with pricing
- **Order Processing**: Create, track, and manage food orders
- **Order Status Tracking**: Monitor order fulfillment through status updates
- **Address Management**: Store and manage delivery addresses
- **Permission-Based Access Control**: Granular control over user permissions and roles with Spatie Laravel-Permission

## System Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js and NPM for frontend assets

## Installation

1. Clone the repository
   ```
   git clone https://github.com/BNohut/restaurant-order-project.git
   cd restaurant-order-project
   ```

2. Install PHP dependencies
   ```
   composer install
   ```

3. Install frontend dependencies(optional)
   ```
   npm install && npm run build
   ```

4. Configure environment
   ```
   cp .env.example .env
   php artisan key:generate
   ```

5. Set up your database in the `.env` file
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=restaurant_order
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations and seed the database
   ```
   php artisan migrate --seed
   ```

7. Start the development server
   ```
   php artisan serve
   ```

## Architecture

This project follows a modular architecture adhering to SOLID principles:

- **Models**: Represent database entities (User, Product, Order, OrderItem, Company, Address, Status)
- **Controllers**: Handle HTTP requests and responses
- **Traits**: Provide reusable functionality across models

## Packages & Technologies

The project leverages several powerful packages to enhance functionality:

- **[Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission)**: Advanced role and permission management with a powerful and flexible ACL system
- **[Laravel Sanctum](https://laravel.com/docs/12.x/sanctum)**: API token authentication
- **[Laravel Eloquent](https://laravel.com/docs/12.x/eloquent)**: ORM for elegant database interactions
- **[Laravel Validation](https://laravel.com/docs/12.x/validation)**: For robust data validation

## API Documentation

The system provides both web and API interfaces. API endpoints follow REST conventions and include:

- User authentication **READY!**
- Order creation **READY!**
- Product management (Soon!)
- Status updates (Soon!)
- Company and address management (Soon!)

For detailed API documentation, run the application and visit `/api/documentation`.

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

## License

The Restaurant Order Management System is open-sourced software licensed under the ME.
