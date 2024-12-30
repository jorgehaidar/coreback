# Developer README for [Your Project Generator]

## Overview 🎯🚀✨

[Your Project Generator] is a robust, scalable, and highly customizable tool designed to streamline and accelerate the development of web applications. Built on the Laravel framework, it includes:

- A generic **Model**, **Controller**, and **Service Layer** for rapid and error-free programming.
- A dynamic **security system** with role-based access control (RBAC), route management, and middleware for advanced protection.
- An error-handling system that logs errors in the database, assigns error IDs for debugging, and sends notifications to developers.
- Integrated JWT-based authentication with advanced security features such as account lockouts, email verification, and password recovery.

## Key Features 💡📋🔑

### 1. **Generic Modules**
- **Models**: Preconfigured to handle common database interactions.
- **Controllers**: Designed for CRUD operations with minimal customization.
- **Service Layer**: Encapsulates business logic, reducing duplication and improving maintainability.

### 2. **Dynamic Security System** 🔒🛡️
- Role-based permissions with dynamic role creation.
- Route-level access control stored in the database.
- Middleware for incremental account lockouts (e.g., 1hr, 2hrs, 12hrs, etc.) based on failed login attempts.

### 3. **Error Handling** ⚙️❌📝
- Configurable error handling for HTTP statuses (e.g., 500, 403).
- Errors are stored in the database with:
    - Description and stack trace.
    - Request payload.
    - User and IP information.
- Unique error IDs sent to the frontend for streamlined debugging.
- Optional email notifications for developers.

### 4. **Authentication System** 🔑📧🛠️
- JWT-based authentication with:
    - Email verification.
    - Password recovery.
    - User lockouts based on configurable thresholds.

### 5. **Error Rate Limiting** ⏳📉🔗
- Configurable progressive rate limiting for APIs (e.g., 60 requests per minute with increasing penalties for abuse).
- Fallback to standard rate limiting if the progressive system is disabled.

### 6. **Environmental Flexibility** 🌍🔧🗂️
- Centralized configuration through `.env` files.
- Toggleable features like error logging, email notifications, and rate limiting.

## Installation 🛠️📦✅

1. Clone the repository:
   ```bash
   git clone [repository_url]
   cd [project_directory]
   ```

2. Install dependencies and configure the environment:
   ```bash
   composer install
   cp .env.example .env
   ```

3. After running `composer install`, the following commands are automatically executed:
    - Migrate the database: `php artisan migrate`
    - Generate the application key: `php artisan key:generate`
    - Generate the JWT secret: `php artisan jwt:secret`
    - Clear the cache: `php artisan cache:clear`
    - Create symbolic links for storage: `php artisan storage:unlink && php artisan storage:link`
    - Seed the database: `php artisan db:seed`

4. Start the application:
   ```bash
   php artisan serve
   ```

## Configuration via Environment Variables 🌐⚙️📝

The system provides extensive configuration options through environment variables in the `.env` file:

### 1. Query Logging 🖋️📊🗂️
- `LOG_QUERY=true`: Enables query logging to `storage/logs/query.log`.
- Logs all executed queries, including parameters and execution time, to aid debugging and optimization.

### 2. Error Logging and Notifications ❌📬📨
- `LOG_ERROR=true`: Activates error logging.
- `NO_LOG_ERROR=500,403`: Specifies which HTTP error codes to exclude from logging.
- `EMAIL_DEV=jorge@codestic.net`: Email address where error details are sent. Includes the request payload, user info, and IP.

### 3. API Key Authentication 🔑🛡️📜
- `ENABLE_API_KEY_AUTH=true`: Activates API key-based authentication for systems that require it.

### 4. Progressive Rate Limiter 🕒📈🚦
- `ENABLE_PROGRESSIVE_RATE_LIMITER=true`: Implements progressive blocking for excessive requests, with increasing lockout durations.

## Usage 🖱️📖🚀

### Generic CRUD Operations
- Extend the provided `CoreModel`, `CoreController`, or `CoreService` to implement your custom logic.
- **CoreModel**: An extension of Laravel's base model, inheriting all default functionality. For further reference, consult the Laravel documentation.
- **CoreController**: Similar to Laravel's controller but preconfigured for CRUD operations. Refer to Laravel's controller documentation for additional details.
- **CoreService**: Abstracts business logic, housing operations to interact with the database or other services, leaving the controller responsible for processing input and deciding on the response.

### Security and Authentication 🔒👤📧
- Define roles and permissions in the database.
- Use the provided middleware for route protection.

### Password Management Endpoints 🔑📤📧
#### 1. **Change Password**
- **Route**: `POST /change-password`
- **Description**: Allows authenticated users to update their current password.
- **Parameters**:
    - `current_password` (string, required): The user's current password.
    - `new_password` (string, required): The new password meeting the strong password requirements.

#### 2. **Recover Password**
- **Request Recovery Email**:
    - **Route**: `POST /restore-password`
    - **Description**: Sends a recovery email with a unique code to reset the password.
    - **Parameters**:
        - `email` (string, required): The registered email of the user.

- **Validate Recovery Code**:
    - **Route**: `POST /validate-code`
    - **Description**: Verifies the recovery code sent to the user's email.
    - **Parameters**:
        - `email` (string, required): The registered email of the user.
        - `code` (string, required): The recovery code sent via email.

- **Reset Password**:
    - **Route**: `POST /restore-password/{hash}`
    - **Description**: Resets the user's password using the validated recovery hash.
    - **Parameters**:
        - `new_password` (string, required): The new password meeting the strong password requirements.

### Email Verification Endpoints 📧✅🔗
#### 1. **Send Verification Email**
- **Route**: `POST /email/send-verification`
- **Description**: Sends a verification email to the user's registered email address.
- **Parameters**: None.

#### 2. **Verify Email**
- **Route**: `POST /email/verify/{id}/{hash}`
- **Description**: Confirms the user's email verification using a unique hash.
- **Parameters**: None.

### Error Handling ❌📋💡
- Errors are logged in the `error_logs` table with a unique ID for each incident.
- Review logs via the database or receive email notifications if configured.

### Rate Limiting 📉⏳📋
- Customize rate-limiting behavior in the `AppServiceProvider` or through environment variables as described above.

### Commands 🛠️⚙️🖥️

- `php artisan make:service {module}/{model}`
    - **Options**:
        - `-a | --all`: Create all files (model, controller, migration, service).
        - `-m | --model`: Create only the model.
        - `-c | --controller`: Create only the controller.
        - `-s | --service`: Create only the service.
        - `-i | --migration`: Create only the migration.
        - `-p | --api`: Create only the API update.
        - `-e | --seeder`: Create only the seeder file.

- `php artisan route:sync`: Update the routes in the database for permission and role management.

### Symlink 🔗📁🔗
- `php artisan storage:link`: This command creates two symlinks:
    - Links the storage's public folder to the public directory.
    - Creates another symlink linking the web folder to the backend's public folder, facilitating easier deployment.

### Generic Export 🖨️📤📋
The frontend sends a JSON payload to the backend to export model records in the following format:
```json
{
  "{module}\{model}": {
    "columns": {
      "column1": "pretty column 1",
      "column2": "pretty column 2",
      "relation.column1": "pretty column 3"
    },
    "ids": [1, 2, 3]
  }
}
```

## Best Practices 🏆📚✨

- Use the provided base components to maintain consistency across your application.
- Regularly review logged errors and address recurring issues.
- Keep `.env` files secure and avoid committing them to version control.

## Contact 📧📱🌍

For questions or feedback, please contact [Your Name/Team] at [your

