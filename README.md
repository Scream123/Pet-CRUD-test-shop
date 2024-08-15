# CRUD Application

A brief description of the project and its purpose.

## Installation

Instructions for setting up and configuring the project.

1. Clone the repository:
    ```bash
    git clone https://github.com/your-repository.git
    ```

2. Navigate to the project directory:
    ```bash
    cd project-name
    ```

3. Install dependencies:
    ```bash
    composer install
    npm install
    ```

4. Create an `.env` file from the example:
    ```bash
    cp .env.example .env
    ```

5. Generate the application key:
    ```bash
    php artisan key:generate
    ```

6. Run database migrations:
    ```bash
    php artisan migrate
    ```

7. Start the server:
    ```bash
    php artisan serve
    ```

## Docker Setup

If you are using Docker to manage the environment, follow these steps:

1. Build and start the containers:
    ```bash
    docker-compose up -d
    ```

2. If you need to rebuild the Docker images:
    ```bash
    docker-compose build
    ```

3. Run database migrations within the Docker container:
    ```bash
    docker-compose exec app php artisan migrate
    ```

4. Access the application via `http://localhost:8876`.

## Usage

Instructions on how to use the project, including command examples and features.

## Configuration

### Database

Make sure to set up your database configurations in the `.env` file:

- `DB_CONNECTION`: mysql
- `DB_HOST`: your database host (default is `127.0.0.1` or `db` if using Docker)
- `DB_PORT`: the port for the database (default is `3306`)
- `DB_DATABASE`: the name of your database
- `DB_USERNAME`: your database username
- `DB_PASSWORD`: your database password

## Testing

How to run tests and check the project.

```bash
php artisan test
