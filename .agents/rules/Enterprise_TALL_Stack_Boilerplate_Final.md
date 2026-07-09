# Enterprise TALL Stack Boilerplate (Final)

## Philosophy

-   Keep Laravel conventions.
-   Thin Controllers.
-   Business logic in Services.
-   Database access in Repositories.
-   Livewire for UI.
-   No Filament.
-   No unnecessary DTOs, Enums, or Repository Interfaces.
-   Use `tinyInteger` for statuses.
-   Free/open-source tooling only.

## Stack

-   Laravel 12/13
-   Livewire 3
-   Alpine.js
-   Tailwind CSS 4
-   Vite
-   MySQL
-   Laravel Breeze
-   Laravel Sanctum
-   Spatie Laravel Permission
-   Pest

## Architecture

``` text
Route
  ↓
Controller
  ↓
Form Request
  ↓
Service
  ↓
Repository
  ↓
Model
  ↓
Database

API → Resource → JSON
Web → Blade / Livewire
```

## Folder Structure

``` text
app
├── Helpers
├── Integrations
│   ├── Firebase
│   ├── Sms
│   ├── Payment
│   └── Storage
├── Http
│   ├── Controllers
│   │   ├── Admin
│   │   ├── Student
│   │   ├── Website
│   │   └── Api
│   ├── Middleware
│   ├── Requests
│   └── Resources
├── Livewire
│   ├── Admin
│   │   ├── Dashboard
│   │   ├── Academic
│   │   ├── Finance
│   │   ├── Inventory
│   │   ├── CRM
│   │   ├── Reports
│   │   └── Settings
│   ├── Student
│   ├── Website
│   └── Shared
├── Models
├── Notifications
├── Policies
├── Repositories
├── Rules
├── Services
├── Traits
└── ViewModels
```

## Resources

``` text
resources
├── views
│   ├── website
│   ├── student
│   ├── admin
│   ├── layouts
│   │   ├── website.blade.php
│   │   ├── student.blade.php
│   │   └── admin.blade.php
│   └── components
├── css
├── js
└── lang
```

## Routes

``` text
routes
├── website.php
├── student.php
├── admin.php
├── api.php
├── auth.php
├── channels.php
└── console.php
```

## Dashboards

### Website

Public pages.

### Student Dashboard

Dedicated UI and layout.

Modules: - Profile - Courses - Attendance - Results - Payments -
Notices - Notifications

### Management Dashboard

Single dashboard shared by: - Super Admin - Admin - Branch Manager -
Teacher - Accountant - HR - Admission Officer - Library Staff

Menus are controlled by Spatie permissions. roles will be dynamic and can be created/assigned by super-admin or admin

## Services

-   StudentService
-   TeacherService
-   CourseService
-   AttendanceService
-   AccountingService
-   InventoryService
-   ReportService
-   NotificationService
-   SmsService
-   UploadService

## Repositories

Create one repository per aggregate only when database logic becomes
non-trivial.

## Notifications

``` text
Controller
  ↓
NotificationService
  ├── Database
  ├── Firebase (FCM)
  ├── Mail
  └── SMS Provider
```

## Integrations

``` text
Integrations
├── Firebase
├── Sms
│   ├── SslWirelessClient
│   ├── BulkSmsBdClient
│   └── ...
├── Payment
└── Storage
```


## Search

1.  Eloquent `where()`
2.  MySQL FULLTEXT indexes
3.  Optional Meilisearch/Typesense later

## Cache


-   Redis

## Packages

Required: - laravel/breeze - livewire/livewire - laravel/sanctum -
spatie/laravel-permission

Development: - pestphp/pest - laravel/pint - phpstan/phpstan -
barryvdh/laravel-debugbar

Optional: - intervention/image - spatie/laravel-activitylog -
spatie/laravel-backup

## Guiding Principles

-   Prefer simplicity over abstraction.
-   Introduce interfaces only when multiple implementations are
    required.
-   Keep controllers thin.
-   Keep models focused on relationships, scopes and casts.
-   Keep business rules inside services.
-   Organize the admin by business modules, not user roles.
-   Reuse services for web and mobile APIs.
