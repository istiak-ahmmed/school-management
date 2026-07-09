---
trigger: always_on
---

# TALL Stack Full-Stack Development Skills

## Architecture

-   Laravel 12+ Architecture
-   MVC Pattern
-   Service Layer Pattern
-   Repository Pattern (Optional where necessary)
-   Action Classes
-   DTO (Data Transfer Objects)
-   Resource Classes (API)
-   Form Request Validation
-   Policy & Gate Authorization
-   Observer Pattern
-   Event Driven Development
-   Jobs & Queues
-   Notification System
-   Middleware Architecture
-   Dependency Injection
-   SOLID Principles
-   Clean Code
-   DRY Principle
-   KISS Principle

## Database Design

-   Database Normalization
-   Foreign Key Constraints
-   Proper Indexing
-   Composite Index
-   UUID Support
-   Soft Delete
-   Pivot Tables
-   Migration Best Practices
-   Seeder Architecture
-   Factory Pattern
-   Transactions
-   Optimistic Locking (when needed)

## Eloquent ORM

### Performance

-   Prevent N+1 Query using eager loading (`with`, `load`,
    `loadMissing`)
-   Use `withCount`, `withExists`, `withSum`, `withAvg`, `withMax`,
    `withMin`
-   Conditional and constrained eager loading

### Query Optimization

-   Select only required columns
-   Chunk / ChunkById
-   Cursor / Lazy collections
-   Paginate / SimplePaginate / CursorPaginate
-   Prefer `exists()` over `count() > 0`
-   Aggregate functions (`sum`, `count`, `avg`, `max`, `min`)

## Livewire Skills

### Component Design

-   Single Responsibility Components
-   Reusable Components
-   Nested Components
-   Dynamic Components
-   Lazy Components
-   Volt Components (optional)

### Performance

-   `wire:init`
-   `wire:model.defer`
-   `wire:model.live.debounce`
-   `wire:poll`
-   `wire:loading`
-   `wire:offline`
-   `wire:dirty`

## Alpine.js

-   Reactive Components
-   Dropdowns
-   Modals
-   Tabs
-   Accordions
-   Notifications
-   Local State
-   Event Dispatch
-   Stores
-   Intersect, Collapse, Persist Plugins

## Admin Panel Optimization

### Fast Data Loading

-   Server-side Pagination
-   Cursor Pagination
-   Eager Loading
-   Column Selection
-   Query Caching
-   Relationship Counts
-   Lazy Components
-   Deferred Loading
-   Skeleton Loading
-   Database Indexing

### Server-side Filtering

-   Search
-   Status
-   Category
-   Date Range
-   Price Range
-   User
-   Role
-   Country

### Server-side Sorting

-   Name
-   Created Date
-   Updated Date
-   Status
-   Price
-   Stock

### Bulk Operations

-   Delete
-   Restore
-   Export
-   Update Status
-   Assign Category

## Frontend Performance

-   Image Lazy Loading
-   Responsive Images
-   Asset Compression
-   Vite Optimization
-   Code Splitting
-   Prefetch / Preload
-   Critical CSS
-   Browser Cache
-   CDN Support

## Validation

-   Form Requests
-   Livewire Validation
-   Real-time Validation
-   Custom Rules
-   Rule Objects

## Authentication

-   Laravel Breeze
-   Authentication & Authorization
-   Roles & Permissions
-   Email Verification
-   Password Reset
-   Two-factor Authentication Ready
-   Social Login Ready

## File Upload

-   Livewire Upload
-   Multiple Uploads
-   Chunk Upload
-   Image Compression
-   Validation
-   Storage Drivers
-   Cloud Storage Ready

## Caching

-   Query Cache
-   Config Cache
-   Route Cache
-   View Cache
-   Redis
-   Response Cache

## Queue

-   Email Queue
-   Notification Queue
-   Export Queue
-   Import Queue
-   Image Processing Queue

## Logging

-   Daily Logs
-   Exception Handling
-   Telescope
-   Pulse
-   Debugbar (Development)

## API Ready

Business Logic Flow:

``` text
Controller
    ↓
Service
    ↓
Action
    ↓
Repository
    ↓
Model
```

Future Mobile API:

``` text
API Controller
    ↓
Same Service Layer
    ↓
Action
    ↓
Repository
    ↓
Model
```

## Recommended Directory Structure

``` text
app/
├── Actions
├── Services
├── Repositories
├── DTO
├── Enums
├── Helpers
├── Traits
├── Contracts
├── Events
├── Listeners
├── Jobs
├── Policies
├── Observers
├── Rules
├── Notifications
├── Livewire/
│   ├── Admin
│   ├── Website
│   └── Shared
├── Http/
│   ├── Controllers/
│   │   ├── Admin
│   │   ├── Website
│   │   └── Api/V1
│   ├── Middleware
│   └── Requests
├── Models
└── Providers
```

## Security

-   CSRF Protection
-   XSS Prevention
-   SQL Injection Prevention
-   Mass Assignment Protection
-   Authorization Policies
-   Rate Limiting
-   Password Hashing
-   Secure File Uploads
-   Signed URLs

## Testing

-   Unit Tests
-   Feature Tests
-   Livewire Tests
-   Database Tests
-   HTTP Tests
-   Mocking
-   Factory Testing

## Deployment

-   Queue Workers
-   Scheduler
-   Horizon
-   Supervisor
-   Nginx
-   PHP-FPM
-   Environment Separation
-   CI/CD Ready

## Coding Standards

-   PSR-12
-   Laravel Pint
-   PHPStan / Larastan
-   Static Analysis
-   Type Declarations
-   Strict Return Types
-   PHPDoc
-   Consistent Naming Conventions

## Project Philosophy

Build a scalable, maintainable TALL Stack application with: - Fast admin
panels through optimized queries and server-side operations. - Clean
separation of concerns using Services, Actions, and Repositories. -
Reusable business logic for Website, Admin, and future Mobile APIs. -
Database efficiency with eager loading, indexing, caching, and
pagination. - Long-term maintainability through SOLID principles,
testing, and modular architecture.


