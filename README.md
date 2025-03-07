# RestoMate

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a>
</p>

## About Restaurant Order Management System

The Restaurant Order Management System is a modern, robust application built with Laravel for managing restaurant orders, products, customers, and companies. The application follows SOLID principles and modular architecture to ensure scalability and maintainability.

## Features

- **User Management**: Role-based access control system with permissions
- **Company Management**: Create and manage companies with associated addresses
- **Product Catalog**: Maintain a catalog of food products with pricing
- **Order Processing**: Create, track, and manage food orders
- **Order Status Tracking**: Monitor order fulfillment through status updates
- **Address Management**: Store and manage delivery addresses

## System Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js and NPM for frontend assets

## Installation

1. Clone the repository
   ```
   git clone https://your-repository-url.git
   cd restaurant-order-project
   ```

2. Install PHP dependencies
   ```
   composer install
   ```

3. Install frontend dependencies
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
- **Services**: Contain business logic
- **Repositories**: Handle data access logic
- **Traits**: Provide reusable functionality across models

## API Documentation

The system provides both web and API interfaces. API endpoints follow REST conventions and include:

- User authentication and management
- Product management
- Order creation and processing
- Status updates
- Company and address management

For detailed API documentation, run the application and visit `/api/documentation`.

## Testing

Run the automated tests with:

```
php artisan test
```

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

## License

The Restaurant Order Management System is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
