# Laravel Project

## Overview

This project is a Laravel-based application that includes user authentication, profile management, and interaction features such as posts, comments, and likes. The application is designed to handle user registration, login, logout, and allows users to create, update, delete, and interact with posts and comments.

## Features

- User Registration and Login
- User Profile Management (including profile image upload)
- CRUD Operations for Posts
- CRUD Operations for Comments on Posts
- Like/Unlike Posts
- Middleware for Authentication (Sanctum)

## Installation

### Prerequisites

- PHP >= 7.3
- Composer
- MySQL or any other database

### Steps

1. **Clone the repository:**

    ```bash
    git clone <https://github.com/sobhysayed/blog-api.git>
    ```

2. **Navigate into the directory:**

    ```bash
    cd <blog>
    ```

3. **Install dependencies:**

    ```bash
    composer install
    ```

4. **Copy `.env.example` to `.env` and configure your environment variables:**

    ```bash
    cp .env.example .env
    ```

5. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

6. **Set up the database and run migrations:**

   Configure your database settings in the `.env` file and then run:

    ```bash
    php artisan migrate
    ```

7. **Run the seeders to populate the database with test data:**

    ```bash
    php artisan db:seed
    ```

8. **Create a symbolic link from `public/storage` to `storage/app/public` to serve uploaded files:**

    ```bash
    php artisan storage:link
    ```

9. **Start the development server:**

    ```bash
    php artisan serve
    ```

## API Endpoints

### Auth

- **Register:**
    - `POST /register`
    - Body: `{ "name": "string", "email": "string", "password": "string", "password_confirmation": "string" }`

- **Login:**
    - `POST /login`
    - Body: `{ "email": "string", "password": "string" }`

- **Logout:**
    - `POST /logout`
    - Header: `Authorization: Bearer <token>`

### Profile

- **Get Profile:**
    - `GET /profile`
    - Header: `Authorization: Bearer <token>`

- **Update Profile:**
    - `PUT /profile`
    - Header: `Authorization: Bearer <token>`
    - Body: `{ "name": "string", "email": "string", "password": "string", "password_confirmation": "string", "image": "file" }`

### Posts

- **Get All Posts:**
    - `GET /posts`

- **Get Specific Post:**
    - `GET /posts/{id}`

- **Create Post:**
    - `POST /posts`
    - Header: `Authorization: Bearer <token>`
    - Body: `{ "title": "string", "body": "string" }`

- **Update Post:**
    - `PUT /posts/{id}`
    - Header: `Authorization: Bearer <token>`
    - Body: `{ "title": "string", "body": "string" }`

- **Delete Post:**
    - `DELETE /posts/{id}`
    - Header: `Authorization: Bearer <token>`

### Likes

- **Like a Post:**
    - `POST /posts/{id}/like`
    - Header: `Authorization: Bearer <token>`

- **Unlike a Post:**
    - `DELETE /posts/{id}/like`
    - Header: `Authorization: Bearer <token>`

- **Get Likes on a Post:**
    - `GET /posts/{postId}/likes`

### Comments

- **Get Comments on a Post:**
    - `GET /posts/{postId}/comments`

- **Create a Comment:**
    - `POST /posts/{postId}/comments`
    - Header: `Authorization: Bearer <token>`
    - Body: `{ "body": "string" }`

- **Update a Comment:**
    - `PUT /posts/{postId}/comments/{commentId}`
    - Header: `Authorization: Bearer <token>`
    - Body: `{ "body": "string" }`

- **Delete a Comment:**
    - `DELETE /posts/{postId}/comments/{commentId}`
    - Header: `Authorization: Bearer <token>`

## Testing

You can use Laravel's built-in testing capabilities to test your application. Create test cases and run tests using:

```bash
php artisan test
