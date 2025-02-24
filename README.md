# Contacts List Web Application

## Overview

This is a simple Contact List web application built using Laravel (backend) and Vue.js (frontend) with MySQL as the database. The application allows users to perform CRUD operations on contacts, including name (firstname, lastname), address, and telephone numbers. It features dynamic interactions like live search and modals for adding, editing, and deleting contacts.

## Features

- **REST API** for managing contacts
- **Vue.js Frontend** with TailwindCSS for styling
- **Live Search** for quick contact lookup
- **Modals** for seamless add/edit/delete interactions
- **Service & Repository Pattern** for a clean architecture
- **Strict TDD Approach** with unit and feature tests
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

1. **Provision Server:** Create an AWS server using Laravel Forge.
2. **Clone Repository:** Deploy code to Forge via GitHub.
3. **Configure Environment:** Update `.env` variables.
4. **Run Migrations:**
   ```sh
   php artisan migrate --seed
   ```
5. **Set Up Queue & Scheduler:**
   ```sh
   php artisan queue:work
   ```
6. **Restart Services:** Restart Nginx & PHP-FPM.

### Frontend (Vue.js) Deployment on S3 & CloudFront

1. Build the Vue.js project:
   ```sh
   npm run build
   ```
2. Upload the `/dist` folder to an S3 bucket.
3. Configure CloudFront for CDN distribution.
4. Update environment variables to point to the S3 URL.

---

## Version Control & Contribution

- Follow the Git flow branching model.
- Commit messages should follow conventional commit standards.
- Open a pull request for feature additions or bug fixes.

---

## License

This project is licensed under the MIT License.

## Author

[Oluwatobi Mayowa Ogunyemi](https://github.com/MayowaOgunyemi)

---

### Additional Notes

- Ensure `.env` is correctly set up before running the project.
- Modify `config/database.php` if needed for different database setups.
