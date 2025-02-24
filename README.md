# Contacts List Web Application

## Overview

This is a simple Contact List web application built using Laravel (backend) and Vue.js (frontend) with MySQL as the database. The application allows users to perform CRUD operations on contacts, including name (firstname, lastname), address, and telephone numbers. It features dynamic interactions like live search and modals for adding, editing, and deleting contacts.

## Features

- **REST API** for managing contacts
- **Vue.js Frontend** with TailwindCSS for styling
- **Live Search** for quick contact lookup
- **Modals** for seamless add/edit/delete interactions
- **Service & Repository Pattern** for a clean architecture
- **Strict Adherence to TDD Approach** with unit and feature tests
- **Deployment via Laravel Forge (AWS) & Vue.js on S3/CloudFront**

---

## Installation & Setup

### Prerequisites

Ensure you have the following installed:

- PHP 8+
- Composer
- Node.js & npm
- MySQL
- Laravel 11+
- Git

### Backend (Laravel) Setup

1. Clone the repository and navigate to the backend directory:
   ```sh
   git clone https://github.com/MayowaOgunyemi/dft-contacts.git 
   cd dft-contacts-BE
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Copy the environment file and configure your database:
   ```sh
   cp .env.example .env
   ```
4. Generate the application key:
   ```sh
   php artisan key:generate
   ```
5. Run migrations and seeders:
   ```sh
   php artisan migrate --seed
   ```
6. Start the Laravel development server:
   ```sh
   php artisan serve
   ```

### Frontend (Vue.js) Setup

1. Navigate to the frontend directory:
   ```sh
   cd dft-contacts-FE
   ```
2. Install dependencies:
   ```sh
   npm install
   ```
3. Start the frontend development server:
   ```sh
   npm run dev
   ```

---

## Architecture & Design Patterns

### Spatie Data Package

- Handles DTOs, validation, and resource transformations.
- Ensures a clean data structure and consistent API responses.

### Dependency Injection

- Implemented via `AppServiceProvider`.
- Interfaces are injected into the service and the controller.

### Service & Repository Pattern

- **Service Layer:** Handles business logic (Service Interface & Concrete Implementation).
- **Repository Layer:** Handles data access logic (Repository Interface & Concrete Implementation).

### Testing (TDD Approach)

- **Unit & Feature Tests:**
  - Mocks service and repository interfaces.
  - Tests run independently of the database.
  - Covers both **positive & negative** test cases.
- Run tests using:
  ```sh
  php artisan test
  ```
**Controller Layer Unit Test**
| Method | Endpoint             | Positive Test Cases ✅                 | Positive Test Cases ✅ |
| ------ | -------------------- | -------------------------------------- | ----------------------------- |
| GET    | `/api/contacts`      | ✅ Retrieve all contacts (paginated)   | ❌ Handle empty contact list |
| GET    | `/api/contacts/{id}` | ✅ Retrieve an existing contact by ID  | ❌ Retrieve a non-existent contact by ID. |
| POST   | `/api/contacts`      | ✅ Create a new contact successfully   | ❌ Create with missing fields. |
| PUT    | `/api/contacts/{id}` | ✅ Update an existing contact successfully | ❌ Update a non-existent contact. |
| DELETE | `/api/contacts/{id}` | ✅ Delete an existing contact successfully | ❌ Delete a non-existent contact. |
| GET    | `/api/contacts/search?q={term}` | ✅ Search contacts with a matching term (paginated). | ❌ Search with an empty or non-matching term. |

**Service Layer Unit Test**
| Method             | Positive Test Cases ✅                   | Positive Test Cases ✅ |
| -------------------- | -------------------------------------- | ----------------------------- |
| `getAllContacts()`    | ✅ Returns a paginated list of all contacts   | ❌ Returns an empty paginator when no contacts exist. |
| `createContact(ContactData $contactData)` | ✅ Successfully creates a new contact using ContactData  | ❌ Fails when required fields in ContactData are missing. |
| `updateContact(int $id, ContactData $contactData)`      | ✅ Successfully updates an existing contact using ContactData   | ❌ Fails when contact ID does not exist. |
| `updateContact(int $id, ContactData $contactData)`      |  | ❌ Fails when invalid data is provided in ContactData. |
| `deleteContact(int $id)` | ✅ Successfully deletes an existing contact | ❌ Fails when contact ID does not exist. |
| `findContactById(int $id)` | ✅ Returns ContactData for a valid contact ID | ❌ Returns null when contact ID does not exist. |
| `searchContacts(string $term)` | ✅ Returns a paginated list of contacts matching the search term | ❌ Returns an empty paginator when no contacts match the search term. |

---

## API Documentation

### Endpoints

| Method | Endpoint             | Description            |
| ------ | -------------------- | ---------------------- |
| GET    | `/api/contacts`      | Fetch all contacts     |
| GET    | `/api/contacts/{id}` | Get a specific contact |
| POST   | `/api/contacts`      | Create a new contact   |
| PUT    | `/api/contacts/{id}` | Update a contact       |
| DELETE | `/api/contacts/{id}` | Delete a contact       |
| GET    | `/api/contacts/search?q={term}` | Search contacts with a matching term (paginated) |

---

## Deployment Guide

### Backend (Laravel) Deployment with Laravel Forge

1. **Provision Server:**
   * Create an AWS EC2 instance (choose an appropriate instance type based on your expected traffic).
   * Use Laravel Forge to provision and configure the EC2 instance (select your preferred PHP version and web server).
2. **Clone Repository:**
   * Connect your GitHub (or other version control) repository to Laravel Forge.
   * Deploy your Laravel API code to the EC2 instance using Forge's deployment features.
3. **Configure Environment:**
   * Update the .env file on the server with your production environment variables (database credentials, API keys, etc.).
   * Ensure APP_ENV=production and APP_DEBUG=false.
4. **Run Migrations and seed data (if needed):**
   ```sh
   php artisan migrate --force
   php artisan db:seed --force # If you need to seed data
   ```
   * The --force flag is essential in production to bypass confirmation prompts.
5. **Set Up Queue & Scheduler:**
   ```sh
   php artisan queue:work --daemon # If using queues
   php artisan schedule:run # If using the scheduler, configure a cron job
   ```
   * Configure a Supervisor process for queue:work to ensure it restarts if it crashes.
   * Configure a cron job on the server to run php artisan schedule:run every minute.
6. **Configure Web Server:**
   * Ensure your web server (Nginx or Apache) is configured to point to the public directory of your Laravel application.
   * Set up SSL certificates (if using HTTPS).
7. **Restart Services:**
   * Restart Nginx and PHP-FPM to apply configuration changes:
   ```sh
   sudo systemctl restart nginx
   sudo systemctl restart php<version>-fpm # Replace <version> with your PHP version
   ```
8. **Configure CORS:**
   * Ensure that CORS is configured correctly in your Laravel API to allow requests from your Vue.js frontend's CloudFront domain.
   
### Frontend (Vue.js) Deployment on S3 & CloudFront

1. **Build the Vue.js project**:
   ```sh
   npm run build
   ```
2. **Create S3 Bucket:**
   * Create an Amazon S3 bucket to store your Vue.js static files.
   * Configure the bucket for static website hosting (optional, but recommended).
3. **Upload the `/dist` folder to an S3 bucket:**
   * Upload the contents of the /dist folder to your S3 bucket.
   * Ensure that the bucket permissions are set to allow public read access (if needed).
4. **Configure CloudFront for CDN distribution:**
   * Create a CloudFront distribution that points to your S3 bucket as the origin.
   * Configure CloudFront settings (e.g., caching, SSL certificates).
   * Set the root object to index.html
5. **Update environment variables to point to the S3 URL:**
   * Update the vueJs environment variable to point to the Laravel API's production URL (e.g., using CloudFront or your API's domain).
6. **Invalidate CloudFront Cache:**
   * After uploading new files to S3, invalidate the CloudFront cache to ensure that users see the latest version of your application.

---

## Author

[Oluwatobi Mayowa Ogunyemi](https://github.com/MayowaOgunyemi)

---

### Additional Notes

- Ensure `.env` is correctly set up before running the project.
- Modify `config/database.php` if needed for different database setups.
