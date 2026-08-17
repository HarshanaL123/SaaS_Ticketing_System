# Enterprise SaaS Ticketing System

A high-performance, strictly typed Customer Support Ticketing System built to enterprise standards. This project serves as a showcase of modern **Domain-Driven Design (DDD)**, **Event Sourcing Light**, and **Single Page Application (SPA)** architecture using Laravel 13+ and Vue 3.

##  Enterprise Architecture Showcase

This codebase intentionally avoids junior developer anti-patterns (such as "Fat Controllers" and raw database manipulation). It is engineered using strict software design patterns to guarantee scalability, testability, and data integrity.

### 1. Domain-Driven Design (The Action Pattern)
Business logic is strictly isolated from the HTTP layer. Operations such as creating a ticket or updating a status are encapsulated in dedicated, testable `Action` classes (e.g., `CreateTicketAction`, `UpdateTicketStatusAction`). Controllers are kept incredibly "skinny," acting merely as HTTP traffic cops that delegate pre-validated requests to these Actions.

### 2. Event Sourcing Light (Audit Trail)
Data mutations are never destructive. Instead of simply overwriting a `status` column, the system utilizes a strict `ticket_activities` audit trail. Every state change, system log, or user comment is recorded immutably, ensuring absolute accountability. Multi-table inserts are strictly wrapped in `DB::transaction()` to prevent data corruption.

### 3. Strict Typing & Backend-Driven UI (PHP 8.3 Backed Enums)
Fragile string literals have been replaced with strict PHP 8.3 Backed Enums (`TicketStatus`, `TicketPriority`). These Enums act as the single source of truth, dictating not only database states but also UI representation by returning specific Tailwind CSS strings (`badgeColor()`). This allows the Vue frontend to act as a "dumb" mirror of the backend state.

### 4. Polymorphism & The Strategy Pattern
The background notification system is built using the Strategy Pattern and strict Interfaces. By injecting a `NotificationInterface` into asynchronous Queue Jobs, the core logic is entirely decoupled from the delivery mechanism. Switching from Email notifications to Slack notifications requires zero rewrites to the core domain.

### 5. API Translation Contracts
Raw Eloquent models are never leaked to the frontend. Strict `JsonResource` API classes enforce a predictable, secure JSON contract for the Vue SPA, formatting dates (`diffForHumans()`) and mapping Enum states intelligently before transmission.

### 6. Seamless SPA & AJAX Filtering
The frontend is built with Vue 3 (Composition API) and Inertia.js. It features a GitHub-style Activity Timeline that renders Event Sourced data asynchronously via `useForm()`. The data table utilizes `router.get()` to send query parameters back to the Laravel controller, allowing the backend to dynamically apply Eloquent `where()` clauses while seamlessly updating the DOM without a page refresh.

---

##  Tech Stack

**Backend**
* Laravel 13+ (Latest Stable)
* PHP 8.3+
* MySQL (Strict Relational Integrity & Cascade Rules)
* Pest PHP (Automated TDD)

**Frontend**
* Vue 3 (Composition API)
* Inertia.js (SPA Routing)
* Tailwind CSS
* Laravel Breeze

---

##  Local Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd SaaS_Ticketing_System
   ```

2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure the Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Ensure your `.env` is configured for a MySQL database connection, and set `QUEUE_CONNECTION=database`.*

4. **Run Migrations & Build Frontend:**
   ```bash
   php artisan migrate
   npm run build
   ```

5. **Start the Development Servers:**
   ```bash
   php artisan serve
   php artisan queue:listen
   ```

---

##  Testing

This project adheres to strict Test-Driven Development (TDD) principles. To execute the automated Pest PHP test suite (verifying Authorization Policies and Action classes):

```bash
php artisan test
```

