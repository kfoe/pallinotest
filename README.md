# Project Setup and Instructions

## Prerequisites

Before starting, ensure that the following ports are available on your machine:

- **MySQL**: Port `8889`
- **Nginx**: Port `8081`
- **Xdebug**: Port `9003`

Make sure no other services are using these ports to avoid conflicts.

---

## Getting Started

Follow these steps to set up the environment:

### 1. **Clone the Repository**
   Clone the project repository to your local machine:
   ```bash
   git clone https://github.com/kfoe/pallinotest.git
   cd pallinotest
   ```

### 2. **Build Docker containers**
   Build the Docker images with the `--no-cache` option to ensure no cached layers are used:
   ```bash
   docker-compose build --no-cache
   ```

### 3. **Start Docker containers in the background (detached mode)**
   Start the Docker containers as a daemon:
   ```bash
   docker-compose up -d
   ```

### 4. **Access the PHP container**
   Get inside the PHP container to execute further commands:
   ```bash
   docker exec -it php_pallinotest /bin/bash
   ```

### 5. **Install Composer dependencies**
   Inside the PHP container, install all the project dependencies using Composer:
   ```bash
   composer install --prefer-dist
   ```

### 6. **Generate a new application key**
   Create a new application key for your Laravel project:
   ```bash
   php artisan key:generate --ansi
   ```

### 7. **Run database migrations**
   Apply database migrations to create the necessary tables:
   ```bash
   php artisan migrate:fresh
   ```

### 8. **Import shop data**
   Import data related to shops:
   ```bash
   php artisan import:shops
   ```

### 9. **Import offer data**
   Import data related to offers:
   ```bash
   php artisan import:offers
   ```

---

## Testing and Quality Assurance

### Running Tests
Run the test suite to ensure the application is working as expected:
```bash
composer test
```

### Code Coverage
Generate a code coverage report to assess the quality of your tests:
```bash
composer coverage
```

### Mutation Testing (Infection)
Run mutation testing to measure the effectiveness of your test suite:
```bash
composer infection
```

---

## API Documentation

- The Swagger API documentation is located in the `openapi` folder.

---

## Postman Collection

- The Postman collection for API testing is available in the `postman` folder.