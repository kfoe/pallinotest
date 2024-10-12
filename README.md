# Project Setup and Instructions

## Prerequisites

Before starting, ensure the following ports are available on your machine:

- **MySQL**: Port `8889`
- **Nginx**: Port `8081`
- **Xdebug**: Port `9003`

Make sure no other services are using these ports.

---

## Getting Started

Follow these steps to set up the environment:

1. **Build Docker containers**:
   ```bash
   docker-compose build --no-cache
   ```
   
2. **Start the Docker containers as deamon**:
   ```bash
   docker-compose up -d
   ```

3. **Access the PHP container**:
   ```bash
   docker exec -it php_pallinotest /bin/bash
   ```

4. **Install Composer dependencies**:
   ```bash
   composer install --prefer-dist
   ```

5. **Generate a new application key**:
   ```bash
   php artisan key:generate --ansi
   ```

6. **Run the database migrations**:
   ```bash
   php artisan migrate:fresh
   ```

7. **Import shop data**:
   ```bash
   php artisan import:shops
   ```

8. **Import offer data**:
   ```bash
   php artisan import:offers
   ```

---

## Testing and Quality Assurance

### Running Tests

To run the test suite, use:

```bash
composer test
```

### Code Coverage

To generate the code coverage report, run:

```bash
composer coverage
```

### Mutation Testing (Infection)

To run mutation testing with Infection:

```bash
composer infection
```

---

## End-to-End (E2E) Testing

You can test the APIs using `curl` with the following commands:

- **Get offers ordered by price (ascending) for a specific shop**:
   ```bash
   curl --location 'http://localhost:8081/api/v1/offers/10'
   ```

- **Get offers filtered by `countryCode` with related shops**:
   ```bash
   curl --location 'http://localhost:8081/api/v1/offers/BR'
   ```

---

## API Documentation

- The Swagger API documentation is available in the `openapi` folder.

---

## Postman Collection

- The Postman collection is located in the `postman` folder.
