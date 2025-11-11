## Why This Dashboard?
**Laravel Dashboard** This project is developed using Laravel 12 with PHP 8.4, integrated with Inertia.js and Vue 3 to provide a modern and dynamic user experience. It uses Shadcn UI for a clean and responsive interface design.

The application is built on Laravel’s default starter kit and enhanced with Spatie Laravel Permission for advanced role and permission management. This setup ensures secure, flexible, and maintainable access control for different user types.

- **Code Quality Enforced**: Uses Rector to automatically refactor and maintain high-quality, modern PHP code that follows best practices.
- **Consistent Code Style**: Enforces PSR-12 coding standards across the entire project using **Laravel Pint**, ensuring clean and uniform formatting.
- **Strict Type Safety**: PHPStan is used for static type analysis, guaranteeing correct type usage throughout all classes, methods, and properties.
- **100% Unit Test Coverage**: Pest tests run with *--exactly=100 coverage* to ensure every line of code is tested, achieving full test coverage.
- **100% Type Coverage**: Enforced through *pest --type-coverage --min=100*, ensuring that every function and method includes proper type hints and return types.
- **Automated Quality Workflow**: The main **test** script executes a complete validation pipeline.
- **Zero Tolerance for Errors**: The build fails if any lint, type, or test validation fails — ensuring only clean, reliable code is merged.
- **Highly Maintainable & Scalable**: This strict setup ensures the project remains stable, easy to maintain, and scalable over time.

Overall, the project follows Laravel’s best practices, combining server-side power with a smooth Vue-based frontend to deliver an efficient and maintainable web application.

## Getting Started

> **Requires [PHP 8.4+](https://php.net/releases/)**.

```bash
git clone https://github.com/shahriarshaon1993/dashboard.git
```

### Initial Setup

Navigate to your project and complete the setup:

```bash
cd dashboard

# Setup project
composer setup

# Start the development server
composer dev
```

### Verify Installation

Run the test suite to ensure everything is configured correctly:

```bash
composer test
```

You should see 100% test coverage and all quality checks passing.

## Available Tooling

### Development
- `composer dev` - Starts Laravel server, queue worker, log monitoring, and Vite dev server concurrently

### Code Quality
- `composer lint` - Runs Rector (refactoring), Pint (PHP formatting), and Prettier (JS/TS formatting)
- `composer test:lint` - Dry-run mode for CI/CD pipelines

### Testing
- `composer test:type-coverage` - Ensures 100% type coverage with Pest
- `composer test:types` - Runs PHPStan at level 9 (maximum strictness)
- `composer test:unit` - Runs Pest tests with 100% code coverage requirement
- `composer test` - Runs the complete test suite (type coverage, unit tests, linting, static analysis)

### Maintenance
- `composer update:requirements` - Updates all PHP and NPM dependencies to latest versions
