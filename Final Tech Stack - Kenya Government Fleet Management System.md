

## Final Tech Stack Structure
## Kenya Government Fleet Management System
Version: 1.0 APPROVED
## Date: December 5, 2025
Status: Ready for Development
##  TECHNOLOGY DECISIONS - APPROVED STACK
## Core Principles
- ✅ Production-ready, battle-tested technologies only
- ✅ Optimized for Kenya government infrastructure
- ✅ Real-time GPS tracking capable
- ✅ Offline-first mobile architecture
- ✅ Cost-effective and maintainable
- ✅ Scalable from pilot (1 ministry) to national (47 counties + all ministries)
##  BACKEND STACK
## Runtime & Framework
## Core Laravel Packages
yaml
## Language
## Language
## :
## :
## PHP 8.3.13
## PHP 8.3.13
## Framework
## Framework
## :
## :
Laravel 11.31.0 (LTS)
Laravel 11.31.0 (LTS)
## Server
## Server
## :
## :
Nginx 1.25.4 + PHP
Nginx 1.25.4 + PHP
## -
## -
## FPM 8.3
## FPM 8.3
## Process Manager
## Process Manager
## :
## :
## Supervisor 4.2.5
## Supervisor 4.2.5
## Application Server
## Application Server
## :
## :
Laravel Octane 2.3 (Swoole 5.1)
Laravel Octane 2.3 (Swoole 5.1)
## [
## [
Optional for high
Optional for high
## -
## -
performance
performance
## ]
## ]

## Essential Packages
json
## {
## {
## "require"
## "require"
## :
## :
## {
## {
## "php"
## "php"
## :
## :
## "^8.3"
## "^8.3"
## ,
## ,
## "laravel/framework"
## "laravel/framework"
## :
## :
## "^11.31"
## "^11.31"
## ,
## ,
## "laravel/sanctum"
## "laravel/sanctum"
## :
## :
## "^4.0"
## "^4.0"
## ,
## ,
## "laravel/horizon"
## "laravel/horizon"
## :
## :
## "^5.24"
## "^5.24"
## ,
## ,
## "laravel/telescope"
## "laravel/telescope"
## :
## :
## "^5.1"
## "^5.1"
## ,
## ,
## "laravel/reverb"
## "laravel/reverb"
## :
## :
## "^1.0"
## "^1.0"
## ,
## ,
## "inertiajs/inertia-laravel"
## "inertiajs/inertia-laravel"
## :
## :
## "^1.0"
## "^1.0"
## }
## }
## }
## }

API Structure
json
## {
## {
## "require"
## "require"
## :
## :
## {
## {
## // Authentication & Authorization
## // Authentication & Authorization
## "spatie/laravel-permission"
## "spatie/laravel-permission"
## :
## :
## "^6.4"
## "^6.4"
## ,
## ,
## "laravel/passport"
## "laravel/passport"
## :
## :
## "^12.0"
## "^12.0"
## ,
## ,


## // Geospatial
## // Geospatial
## "matanyadaev/laravel-eloquent-spatial"
## "matanyadaev/laravel-eloquent-spatial"
## :
## :
## "^4.2"
## "^4.2"
## ,
## ,


// API Documentation
// API Documentation
## "knuckleswtf/scribe"
## "knuckleswtf/scribe"
## :
## :
## "^4.35"
## "^4.35"
## ,
## ,


## // Logging & Monitoring
## // Logging & Monitoring
## "spatie/laravel-activitylog"
## "spatie/laravel-activitylog"
## :
## :
## "^4.8"
## "^4.8"
## ,
## ,
## "spatie/laravel-backup"
## "spatie/laravel-backup"
## :
## :
## "^8.7"
## "^8.7"
## ,
## ,


## // Data Processing
## // Data Processing
## "maatwebsite/excel"
## "maatwebsite/excel"
## :
## :
## "^3.1"
## "^3.1"
## ,
## ,
## "barryvdh/laravel-dompdf"
## "barryvdh/laravel-dompdf"
## :
## :
## "^2.2"
## "^2.2"
## ,
## ,


## // Utilities
## // Utilities
## "spatie/laravel-query-builder"
## "spatie/laravel-query-builder"
## :
## :
## "^5.8"
## "^5.8"
## ,
## ,
## "spatie/laravel-medialibrary"
## "spatie/laravel-medialibrary"
## :
## :
## "^11.4"
## "^11.4"
## ,
## ,
## "intervention/image"
## "intervention/image"
## :
## :
## "^3.5"
## "^3.5"
## }
## }
## ,
## ,
## "require-dev"
## "require-dev"
## :
## :
## {
## {
## "laravel/pint"
## "laravel/pint"
## :
## :
## "^1.13"
## "^1.13"
## ,
## ,
## "pestphp/pest"
## "pestphp/pest"
## :
## :
## "^2.34"
## "^2.34"
## ,
## ,
## "pestphp/pest-plugin-laravel"
## "pestphp/pest-plugin-laravel"
## :
## :
## "^2.3"
## "^2.3"
## ,
## ,
## "nunomaduro/collision"
## "nunomaduro/collision"
## :
## :
## "^8.1"
## "^8.1"
## ,
## ,
## "spatie/laravel-ignition"
## "spatie/laravel-ignition"
## :
## :
## "^2.4"
## "^2.4"
## }
## }
## }
## }

 FRONTEND STACK (Web Application)
## Core Framework
## Build Tools
UI Framework & Components
## Routes:
## Routes:
- /api/v1/* (REST API for mobile app)
- /api/v1/* (REST API for mobile app)
- /api/v1/gfmd/* (GFMD-specific endpoints)
- /api/v1/gfmd/* (GFMD-specific endpoints)
- /api/v1/integrations/* (G2G integrations: NTSA, IFMIS, CMTE)
- /api/v1/integrations/* (G2G integrations: NTSA, IFMIS, CMTE)
- /broadcasting/auth (WebSocket authentication)
- /broadcasting/auth (WebSocket authentication)
## Authentication:
## Authentication:
- Laravel Sanctum: Stateless API tokens (mobile app)
- Laravel Sanctum: Stateless API tokens (mobile app)
- Laravel Passport: OAuth2 (G2G integrations)
- Laravel Passport: OAuth2 (G2G integrations)
- Session-based: Web application (Inertia.js)
- Session-based: Web application (Inertia.js)
json
## {
## {
## "dependencies"
## "dependencies"
## :
## :
## {
## {
## "react"
## "react"
## :
## :
## "^18.3.1"
## "^18.3.1"
## ,
## ,
## "react-dom"
## "react-dom"
## :
## :
## "^18.3.1"
## "^18.3.1"
## ,
## ,
## "@inertiajs/react"
## "@inertiajs/react"
## :
## :
## "^1.0.15"
## "^1.0.15"
## ,
## ,
## "typescript"
## "typescript"
## :
## :
## "^5.3.3"
## "^5.3.3"
## }
## }
## }
## }
json
## {
## {
"devDependencies"
"devDependencies"
## :
## :
## {
## {
## "vite"
## "vite"
## :
## :
## "^5.0.11"
## "^5.0.11"
## ,
## ,
## "@vitejs/plugin-react"
## "@vitejs/plugin-react"
## :
## :
## "^4.2.1"
## "^4.2.1"
## ,
## ,
## "laravel-vite-plugin"
## "laravel-vite-plugin"
## :
## :
## "^1.0.2"
## "^1.0.2"
## ,
## ,
## "autoprefixer"
## "autoprefixer"
## :
## :
## "^10.4.17"
## "^10.4.17"
## ,
## ,
## "postcss"
## "postcss"
## :
## :
## "^8.4.33"
## "^8.4.33"
## }
## }
## }
## }

## State Management
## Data Visualization & Maps
json
## {
## {
## "dependencies"
## "dependencies"
## :
## :
## {
## {
## // Styling
## // Styling
## "tailwindcss"
## "tailwindcss"
## :
## :
## "^3.4.1"
## "^3.4.1"
## ,
## ,
## "@headlessui/react"
## "@headlessui/react"
## :
## :
## "^1.7.18"
## "^1.7.18"
## ,
## ,
## "clsx"
## "clsx"
## :
## :
## "^2.1.0"
## "^2.1.0"
## ,
## ,
## "tailwind-merge"
## "tailwind-merge"
## :
## :
## "^2.2.1"
## "^2.2.1"
## ,
## ,


// Shadcn/UI (copy-paste components)
// Shadcn/UI (copy-paste components)
## "@radix-ui/react-dialog"
## "@radix-ui/react-dialog"
## :
## :
## "^1.0.5"
## "^1.0.5"
## ,
## ,
## "@radix-ui/react-dropdown-menu"
## "@radix-ui/react-dropdown-menu"
## :
## :
## "^2.0.6"
## "^2.0.6"
## ,
## ,
## "@radix-ui/react-select"
## "@radix-ui/react-select"
## :
## :
## "^2.0.0"
## "^2.0.0"
## ,
## ,
## "@radix-ui/react-tabs"
## "@radix-ui/react-tabs"
## :
## :
## "^1.0.4"
## "^1.0.4"
## ,
## ,
## "@radix-ui/react-toast"
## "@radix-ui/react-toast"
## :
## :
## "^1.1.5"
## "^1.1.5"
## ,
## ,
## "@radix-ui/react-tooltip"
## "@radix-ui/react-tooltip"
## :
## :
## "^1.0.7"
## "^1.0.7"
## ,
## ,


## // Icons
## // Icons
## "lucide-react"
## "lucide-react"
## :
## :
## "^0.309.0"
## "^0.309.0"
## }
## }
## }
## }
json
## {
## {
## "dependencies"
## "dependencies"
## :
## :
## {
## {
## // Client State
## // Client State
## "zustand"
## "zustand"
## :
## :
## "^4.5.0"
## "^4.5.0"
## ,
## ,


## // Server State
## // Server State
## "@tanstack/react-query"
## "@tanstack/react-query"
## :
## :
## "^5.17.19"
## "^5.17.19"
## ,
## ,


## // Form State
## // Form State
## "react-hook-form"
## "react-hook-form"
## :
## :
## "^7.49.3"
## "^7.49.3"
## ,
## ,
## "zod"
## "zod"
## :
## :
## "^3.22.4"
## "^3.22.4"
## ,
## ,
## "@hookform/resolvers"
## "@hookform/resolvers"
## :
## :
## "^3.3.4"
## "^3.3.4"
## }
## }
## }
## }

## Project Structure
json
## {
## {
## "dependencies"
## "dependencies"
## :
## :
## {
## {
## // Charts
## // Charts
## "recharts"
## "recharts"
## :
## :
## "^2.10.3"
## "^2.10.3"
## ,
## ,


// Maps (Choose one)
// Maps (Choose one)
## "react-leaflet"
## "react-leaflet"
## :
## :
## "^4.2.1"
## "^4.2.1"
## ,
## ,
// Primary (OpenStreetMap, free)
// Primary (OpenStreetMap, free)
## "leaflet"
## "leaflet"
## :
## :
## "^1.9.4"
## "^1.9.4"
## ,
## ,


## // OR
## // OR
## "@react-google-maps/api"
## "@react-google-maps/api"
## :
## :
## "^2.19.2"
## "^2.19.2"
## ,
## ,
// Alternative (if Google Maps budget approved)
// Alternative (if Google Maps budget approved)


// Date/Time
// Date/Time
## "date-fns"
## "date-fns"
## :
## :
## "^3.0.6"
## "^3.0.6"
## ,
## ,

## // Utilities
## // Utilities
## "axios"
## "axios"
## :
## :
## "^1.6.5"
## "^1.6.5"
## ,
## ,
## "lodash"
## "lodash"
## :
## :
## "^4.17.21"
## "^4.17.21"
## }
## }
## }
## }

 MOBILE STACK (Driver App)
## Core Framework
## Project Dependencies (pubspec.yaml)
resources/
resources/
├── js/
├── js/
│   ├── app.tsx                 # Inertia app entry point
│   ├── app.tsx                 # Inertia app entry point
│   ├── Components/             # Reusable React components
│   ├── Components/             # Reusable React components
│   │   ├── ui/                 # Shadcn/UI components
│   │   ├── ui/                 # Shadcn/UI components
│   │   ├── layouts/            # Layout components
│   │   ├── layouts/            # Layout components
│   │   └── shared/             # Shared business components
│   │   └── shared/             # Shared business components
│   ├── Pages/                  # Inertia pages (Laravel routes)
│   ├── Pages/                  # Inertia pages (Laravel routes)
## │   │   ├── Auth/
## │   │   ├── Auth/
## │   │   ├── Dashboard/
## │   │   ├── Dashboard/
## │   │   ├── Fleet/
## │   │   ├── Fleet/
## │   │   ├── Drivers/
## │   │   ├── Drivers/
## │   │   ├── Maintenance/
## │   │   ├── Maintenance/
## │   │   ├── Reports/
## │   │   ├── Reports/
## │   │   └── Admin/
## │   │   └── Admin/
│   ├── Hooks/                  # Custom React hooks
│   ├── Hooks/                  # Custom React hooks
│   ├── Stores/                 # Zustand stores
│   ├── Stores/                 # Zustand stores
│   ├── Utils/                  # Helper functions
│   ├── Utils/                  # Helper functions
│   └── Types/                  # TypeScript types
│   └── Types/                  # TypeScript types
├── css/
├── css/
│   └── app.css                 # Tailwind CSS entry
│   └── app.css                 # Tailwind CSS entry
└── views/
└── views/
└── app.blade.php           # Single-page entry point
└── app.blade.php           # Single-page entry point
yaml
flutter
flutter
## :
## :
## 3.24.5
## 3.24.5
dart
dart
## :
## :
## 3.5.4
## 3.5.4

yaml
dependencies
dependencies
## :
## :
flutter
flutter
## :
## :
sdk
sdk
## :
## :
flutter
flutter


## # State Management
## # State Management
flutter_riverpod
flutter_riverpod
## :
## :
## ^2.4.10
## ^2.4.10
riverpod_annotation
riverpod_annotation
## :
## :
## ^2.3.4
## ^2.3.4


## # Networking
## # Networking
dio
dio
## :
## :
## ^5.4.0
## ^5.4.0
retrofit
retrofit
## :
## :
## ^4.1.0
## ^4.1.0
pretty_dio_logger
pretty_dio_logger
## :
## :
## ^1.3.1
## ^1.3.1


## # Local Storage & Offline
## # Local Storage & Offline
drift
drift
## :
## :
## ^2.14.1
## ^2.14.1
sqlite3_flutter_libs
sqlite3_flutter_libs
## :
## :
## ^0.5.20
## ^0.5.20
path_provider
path_provider
## :
## :
## ^2.1.2
## ^2.1.2
shared_preferences
shared_preferences
## :
## :
## ^2.2.2
## ^2.2.2
hive
hive
## :
## :
## ^2.2.3
## ^2.2.3
hive_flutter
hive_flutter
## :
## :
## ^1.1.0
## ^1.1.0


## # Location & Maps
## # Location & Maps
geolocator
geolocator
## :
## :
## ^11.0.0
## ^11.0.0
google_maps_flutter
google_maps_flutter
## :
## :
## ^2.5.3
## ^2.5.3
flutter_map
flutter_map
## :
## :
## ^6.1.0
## ^6.1.0
latlong2
latlong2
## :
## :
## ^0.9.0
## ^0.9.0


## # Background Tasks
## # Background Tasks
workmanager
workmanager
## :
## :
## ^0.5.2
## ^0.5.2
flutter_background_service
flutter_background_service
## :
## :
## ^5.0.5
## ^5.0.5

## # Connectivity
## # Connectivity
connectivity_plus
connectivity_plus
## :
## :
## ^5.0.2
## ^5.0.2
internet_connection_checker_plus
internet_connection_checker_plus
## :
## :
## ^2.1.0
## ^2.1.0

## # Camera & Media
## # Camera & Media
image_picker
image_picker
## :
## :
## ^1.0.7
## ^1.0.7
camera
camera
## :
## :
## ^0.10.5+9
## ^0.10.5+9
permission_handler
permission_handler
## :
## :
## ^11.2.0
## ^11.2.0


## # Notifications
## # Notifications
firebase_messaging
firebase_messaging
## :
## :
## ^14.7.9
## ^14.7.9
flutter_local_notifications
flutter_local_notifications
## :
## :
## ^16.3.0
## ^16.3.0


# UI Components
# UI Components

## Project Structure
# UI Components
# UI Components
flutter_screenutil
flutter_screenutil
## :
## :
## ^5.9.0
## ^5.9.0
cached_network_image
cached_network_image
## :
## :
## ^3.3.1
## ^3.3.1
shimmer
shimmer
## :
## :
## ^3.0.0
## ^3.0.0


## # Forms & Validation
## # Forms & Validation
flutter_form_builder
flutter_form_builder
## :
## :
## ^9.1.1
## ^9.1.1
form_builder_validators
form_builder_validators
## :
## :
## ^9.1.0
## ^9.1.0


## # Authentication
## # Authentication
flutter_secure_storage
flutter_secure_storage
## :
## :
## ^9.0.0
## ^9.0.0
local_auth
local_auth
## :
## :
## ^2.1.8
## ^2.1.8


## # Utilities
## # Utilities
intl
intl
## :
## :
## ^0.19.0
## ^0.19.0
logger
logger
## :
## :
## ^2.0.2+1
## ^2.0.2+1
equatable
equatable
## :
## :
## ^2.0.5
## ^2.0.5
freezed_annotation
freezed_annotation
## :
## :
## ^2.4.1
## ^2.4.1
json_annotation
json_annotation
## :
## :
## ^4.8.1
## ^4.8.1
dev_dependencies
dev_dependencies
## :
## :
flutter_test
flutter_test
## :
## :
sdk
sdk
## :
## :
flutter
flutter


## # Code Generation
## # Code Generation
build_runner
build_runner
## :
## :
## ^2.4.8
## ^2.4.8
riverpod_generator
riverpod_generator
## :
## :
## ^2.3.11
## ^2.3.11
drift_dev
drift_dev
## :
## :
## ^2.14.1
## ^2.14.1
retrofit_generator
retrofit_generator
## :
## :
## ^8.1.0
## ^8.1.0
freezed
freezed
## :
## :
## ^2.4.7
## ^2.4.7
json_serializable
json_serializable
## :
## :
## ^6.7.1
## ^6.7.1


## # Linting
## # Linting
flutter_lints
flutter_lints
## :
## :
## ^3.0.1
## ^3.0.1
very_good_analysis
very_good_analysis
## :
## :
## ^5.1.0
## ^5.1.0

Offline Database Schema (Drift)
lib/
lib/
├── main.dart
├── main.dart
├── app/
├── app/
│   ├── app.dart                 # App widget
│   ├── app.dart                 # App widget
│   └── router.dart              # Navigation routes
│   └── router.dart              # Navigation routes
├── core/
├── core/
│   ├── constants/               # App constants
│   ├── constants/               # App constants
│   ├── errors/                  # Error handling
│   ├── errors/                  # Error handling
│   ├── network/                 # API client (Dio)
│   ├── network/                 # API client (Dio)
│   ├── storage/                 # Local database (Drift)
│   ├── storage/                 # Local database (Drift)
│   └── utils/                   # Utilities
│   └── utils/                   # Utilities
├── features/
├── features/
│   ├── auth/
│   ├── auth/
│   │   ├── data/                # API models, repositories
│   │   ├── data/                # API models, repositories
│   │   ├── domain/              # Business logic
│   │   ├── domain/              # Business logic
│   │   └── presentation/        # UI (screens, widgets, providers)
│   │   └── presentation/        # UI (screens, widgets, providers)
│   ├── work_tickets/
│   ├── work_tickets/
│   ├── gp55_logbook/
│   ├── gp55_logbook/
│   ├── incidents/
│   ├── incidents/
│   ├── inspections/
│   ├── inspections/
│   └── profile/
│   └── profile/
├── shared/
├── shared/
│   ├── widgets/                 # Reusable widgets
│   ├── widgets/                 # Reusable widgets
│   ├── providers/               # Shared providers
│   ├── providers/               # Shared providers
│   └── models/                  # Shared models
│   └── models/                  # Shared models
└── services/
└── services/
├── location_service.dart
├── location_service.dart
├── notification_service.dart
├── notification_service.dart
├── sync_service.dart
├── sync_service.dart
└── background_service.dart
└── background_service.dart

##  DATABASE STACK
## Primary Database
## Database Schema Structure
dart
// lib/core/storage/database.dart
// lib/core/storage/database.dart
@DriftDatabase
@DriftDatabase
## (
## (
tables
tables
## :
## :
## [
## [
WorkTickets
WorkTickets
## ,
## ,
GP55Entries
GP55Entries
## ,
## ,
## Incidents
## Incidents
## ,
## ,
## Inspections
## Inspections
## ,
## ,
SyncQueue
SyncQueue
## ]
## ]
## )
## )
class
class
AppDatabase
AppDatabase
extends
extends
## _$
## _$
AppDatabase
AppDatabase
## {
## {
AppDatabase
AppDatabase
## (
## (
## )
## )
## :
## :
super
super
## (
## (
_openConnection
_openConnection
## (
## (
## )
## )
## )
## )
## ;
## ;
## @override
## @override
int
int
get
get
schemaVersion
schemaVersion
## =
## =
## >
## >
## 1
## 1
## ;
## ;
## }
## }
yaml
## Database
## Database
## :
## :
PostgreSQL 16.1
PostgreSQL 16.1
## Extensions
## Extensions
## :
## :
## -
## -
PostGIS 3.4.1 (geospatial)
PostGIS 3.4.1 (geospatial)
## -
## -
pg_stat_statements (query monitoring)
pg_stat_statements (query monitoring)
## -
## -
pg_cron 1.6.2 (scheduled jobs)
pg_cron 1.6.2 (scheduled jobs)
## -
## -
uuid
uuid
## -
## -
ossp (UUID generation)
ossp (UUID generation)
## Connection Pooling
## Connection Pooling
## :
## :
PgBouncer 1.21.0
PgBouncer 1.21.0
## Backup
## Backup
## :
## :
pgBackRest 2.50
pgBackRest 2.50

sql
## -- Core Schemas
## -- Core Schemas
## CREATE
## CREATE
## SCHEMA
## SCHEMA
auth
auth
## ;
## ;
-- Users, roles, permissions
-- Users, roles, permissions
## CREATE
## CREATE
## SCHEMA
## SCHEMA
fleet
fleet
## ;
## ;
-- Vehicles, drivers, assignments
-- Vehicles, drivers, assignments
## CREATE
## CREATE
## SCHEMA
## SCHEMA
maintenance
maintenance
## ;
## ;
-- Service records, work orders
-- Service records, work orders
## CREATE
## CREATE
## SCHEMA
## SCHEMA
tracking
tracking
## ;
## ;
-- GPS logs, geo-fences
-- GPS logs, geo-fences
## CREATE
## CREATE
## SCHEMA
## SCHEMA
finance
finance
## ;
## ;
-- Budgets, costs, expenditure
-- Budgets, costs, expenditure
## CREATE
## CREATE
## SCHEMA
## SCHEMA
integrations
integrations
## ;
## ;
-- External system data
-- External system data
## CREATE
## CREATE
## SCHEMA
## SCHEMA
audit
audit
## ;
## ;
-- Audit trails, activity logs
-- Audit trails, activity logs
-- Example: Vehicle table with PostGIS
-- Example: Vehicle table with PostGIS
## CREATE
## CREATE
## TA B L E
## TA B L E
fleet
fleet
## .
## .
vehicles
vehicles
## (
## (
id UUID
id UUID
## PRIMARY
## PRIMARY
## KEY
## KEY
## DEFAULT
## DEFAULT
uuid_generate_v4
uuid_generate_v4
## (
## (
## )
## )
## ,
## ,
registration
registration
## VA R C H A R
## VA R C H A R
## (
## (
## 20
## 20
## )
## )
## UNIQUE
## UNIQUE
## NOT
## NOT
## NULL
## NULL
## ,
## ,
make
make
## VA R C H A R
## VA R C H A R
## (
## (
## 100
## 100
## )
## )
## NOT
## NOT
## NULL
## NULL
## ,
## ,
model
model
## VA R C H A R
## VA R C H A R
## (
## (
## 100
## 100
## )
## )
## NOT
## NOT
## NULL
## NULL
## ,
## ,
year
year
## INTEGER
## INTEGER
## NOT
## NOT
## NULL
## NULL
## ,
## ,
engine_cc
engine_cc
## INTEGER
## INTEGER
## NOT
## NOT
## NULL
## NULL
## ,
## ,
vehicle_type
vehicle_type
## VA R C H A R
## VA R C H A R
## (
## (
## 50
## 50
## )
## )
## NOT
## NOT
## NULL
## NULL
## ,
## ,
mdac_id UUID
mdac_id UUID
## REFERENCES
## REFERENCES
auth
auth
## .
## .
mdacs
mdacs
## (
## (
id
id
## )
## )
## ,
## ,
current_location GEOGRAPHY
current_location GEOGRAPHY
## (
## (
## POINT
## POINT
## ,
## ,
## 4326
## 4326
## )
## )
## ,
## ,
status
status
## VA R C H A R
## VA R C H A R
## (
## (
## 50
## 50
## )
## )
## DEFAULT
## DEFAULT
## 'available'
## 'available'
## ,
## ,
created_at TIMESTAMPTZ
created_at TIMESTAMPTZ
## DEFAULT
## DEFAULT
## NOW
## NOW
## (
## (
## )
## )
## ,
## ,
updated_at TIMESTAMPTZ
updated_at TIMESTAMPTZ
## DEFAULT
## DEFAULT
## NOW
## NOW
## (
## (
## )
## )
## )
## )
## ;
## ;
-- Spatial index for geo-fencing
-- Spatial index for geo-fencing
## CREATE
## CREATE
## INDEX
## INDEX
idx_vehicles_location
idx_vehicles_location
## ON
## ON
fleet
fleet
## .
## .
vehicles
vehicles
## USING
## USING
## GIST
## GIST
## (
## (
current_location
current_location
## )
## )
## ;
## ;
-- Example: GPS tracking table (partitioned by month)
-- Example: GPS tracking table (partitioned by month)
## CREATE
## CREATE
## TA B L E
## TA B L E
tracking
tracking
## .
## .
gps_logs
gps_logs
## (
## (
id BIGSERIAL
id BIGSERIAL
## ,
## ,
vehicle_id UUID
vehicle_id UUID
## REFERENCES
## REFERENCES
fleet
fleet
## .
## .
vehicles
vehicles
## (
## (
id
id
## )
## )
## ,
## ,
location GEOGRAPHY
location GEOGRAPHY
## (
## (
## POINT
## POINT
## ,
## ,
## 4326
## 4326
## )
## )
## NOT
## NOT
## NULL
## NULL
## ,
## ,
speed
speed
## DECIMAL
## DECIMAL
## (
## (
## 5
## 5
## ,
## ,
## 2
## 2
## )
## )
## ,
## ,
heading
heading
## DECIMAL
## DECIMAL
## (
## (
## 5
## 5
## ,
## ,
## 2
## 2
## )
## )
## ,
## ,
accuracy
accuracy
## DECIMAL
## DECIMAL
## (
## (
## 8
## 8
## ,
## ,
## 2
## 2
## )
## )
## ,
## ,
recorded_at TIMESTAMPTZ
recorded_at TIMESTAMPTZ
## NOT
## NOT
## NULL
## NULL
## ,
## ,
created_at TIMESTAMPTZ
created_at TIMESTAMPTZ
## DEFAULT
## DEFAULT
## NOW
## NOW
## (
## (
## )
## )
## )
## )
## PA RT I T I O N
## PA RT I T I O N
## BY
## BY
## RANGE
## RANGE
## (
## (
recorded_at
recorded_at
## )
## )
## ;
## ;
-- Create partitions for each month
-- Create partitions for each month
## CREATE
## CREATE
## TA B L E
## TA B L E
tracking
tracking
## .
## .
gps_logs_2025_12
gps_logs_2025_12
## PA RT I T I O N
## PA RT I T I O N
## OF
## OF
tracking
tracking
## .
## .
gps_logs
gps_logs
## FOR
## FOR
## VA L U E S
## VA L U E S
## FROM
## FROM
## (
## (
## '2025-12-01'
## '2025-12-01'
## )
## )
## TO
## TO
## (
## (
## '2026-01-01'
## '2026-01-01'
## )
## )
## ;
## ;

## Migration Strategy
##  CACHING & QUEUES
## Redis Stack
## Queue Configuration
bash
# Laravel migrations structure
# Laravel migrations structure
database/
database/
├── migrations/
├── migrations/
## │   ├── 2025_12_01_000001_create_auth_schema.php
## │   ├── 2025_12_01_000001_create_auth_schema.php
## │   ├── 2025_12_01_000002_create_fleet_schema.php
## │   ├── 2025_12_01_000002_create_fleet_schema.php
## │   ├── 2025_12_01_000003_create_maintenance_schema.php
## │   ├── 2025_12_01_000003_create_maintenance_schema.php
## │   ├── 2025_12_01_000004_create_tracking_schema.php
## │   ├── 2025_12_01_000004_create_tracking_schema.php
## │   ├── 2025_12_01_000005_create_finance_schema.php
## │   ├── 2025_12_01_000005_create_finance_schema.php
## │   ├── 2025_12_01_000006_create_integrations_schema.php
## │   ├── 2025_12_01_000006_create_integrations_schema.php
## │   └── 2025_12_01_000007_create_audit_schema.php
## │   └── 2025_12_01_000007_create_audit_schema.php
└── seeders/
└── seeders/
├── RolePermissionSeeder.php
├── RolePermissionSeeder.php
├── MdacSeeder.php
├── MdacSeeder.php
## (
## (
## 47
## 47
counties + ministries
counties + ministries
## )
## )
├── VehicleTypeSeeder.php
├── VehicleTypeSeeder.php
└── TestDataSeeder.php
└── TestDataSeeder.php
## (
## (
development only
development only
## )
## )
yaml
## Redis
## Redis
## :
## :
## 7.2.4
## 7.2.4
## Configuration
## Configuration
## :
## :
maxmemory
maxmemory
## :
## :
## 2GB
## 2GB
maxmemory-policy
maxmemory-policy
## :
## :
allkeys
allkeys
## -
## -
lru
lru
appendonly
appendonly
## :
## :
yes
yes
save
save
## :
## :
## "900 1 300 10 60 10000"
## "900 1 300 10 60 10000"
## Laravel Configuration
## Laravel Configuration
## :
## :
CACHE_DRIVER=redis
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_DRIVER=redis
BROADCAST_DRIVER=redis
BROADCAST_DRIVER=redis

##  REAL-TIME STACK
WebSocket Server
## Broadcasting Channels
php
// config/queue.php
// config/queue.php
## 'connections'
## 'connections'
## =>
## =>
## [
## [
## 'redis'
## 'redis'
## =>
## =>
## [
## [
## 'driver'
## 'driver'
## =>
## =>
## 'redis'
## 'redis'
## ,
## ,
## 'connection'
## 'connection'
## =>
## =>
## 'default'
## 'default'
## ,
## ,
## 'queue'
## 'queue'
## =>
## =>
env
env
## (
## (
## 'REDIS_QUEUE'
## 'REDIS_QUEUE'
## ,
## ,
## 'default'
## 'default'
## )
## )
## ,
## ,
## 'retry_after'
## 'retry_after'
## =>
## =>
## 90
## 90
## ,
## ,
## 'block_for'
## 'block_for'
## =>
## =>
null
null
## ,
## ,
## 'after_commit'
## 'after_commit'
## =>
## =>
false
false
## ,
## ,
## ]
## ]
## ,
## ,
## ]
## ]
## ,
## ,
// Queue structure
// Queue structure
## 'queues'
## 'queues'
## =>
## =>
## [
## [
## 'gps-processing'
## 'gps-processing'
## ,
## ,
// High priority - GPS data ingestion
// High priority - GPS data ingestion
## 'notifications'
## 'notifications'
## ,
## ,
// Medium priority - alerts, emails
// Medium priority - alerts, emails
## 'reports'
## 'reports'
## ,
## ,
// Low priority - background report generation
// Low priority - background report generation
## 'integrations'
## 'integrations'
## ,
## ,
// External API calls (NTSA, IFMIS)
// External API calls (NTSA, IFMIS)
## 'default'
## 'default'
## ]
## ]
yaml
## Service
## Service
## :
## :
## Laravel Reverb 1.0
## Laravel Reverb 1.0
## Configuration
## Configuration
## :
## :
## REVERB_HOST
## REVERB_HOST
## :
## :
## 0.0.0.0
## 0.0.0.0
## REVERB_PORT
## REVERB_PORT
## :
## :
## 8080
## 8080
## REVERB_SCHEME
## REVERB_SCHEME
## :
## :
https
https
## BROADCAST_DRIVER
## BROADCAST_DRIVER
## :
## :
reverb
reverb
## REVERB_APP_ID
## REVERB_APP_ID
## :
## :
fleet
fleet
## -
## -
management
management
## REVERB_APP_KEY
## REVERB_APP_KEY
## :
## :
## $
## $
## {
## {
## APP_KEY
## APP_KEY
## }
## }
## REVERB_APP_SECRET
## REVERB_APP_SECRET
## :
## :
## $
## $
## {
## {
## SECRET
## SECRET
## }
## }

##  DEVOPS & INFRASTRUCTURE
## Containerization
php
// routes/channels.php
// routes/channels.php
## Broadcast
## Broadcast
## ::
## ::
channel
channel
## (
## (
'fleet.{mdacId}'
'fleet.{mdacId}'
## ,
## ,
function
function
## (
## (
## $user
## $user
## ,
## ,
$mdacId
$mdacId
## )
## )
## {
## {
return
return
## $user
## $user
## ->
## ->
mdac_id
mdac_id
## ===
## ===
$mdacId
$mdacId
## ;
## ;
## }
## }
## )
## )
## ;
## ;
## Broadcast
## Broadcast
## ::
## ::
channel
channel
## (
## (
'vehicle.{vehicleId}'
'vehicle.{vehicleId}'
## ,
## ,
function
function
## (
## (
## $user
## $user
## ,
## ,
$vehicleId
$vehicleId
## )
## )
## {
## {
return
return
## $user
## $user
## ->
## ->
can
can
## (
## (
## 'view'
## 'view'
## ,
## ,
## Vehicle
## Vehicle
## ::
## ::
find
find
## (
## (
$vehicleId
$vehicleId
## )
## )
## )
## )
## ;
## ;
## }
## }
## )
## )
## ;
## ;
## Broadcast
## Broadcast
## ::
## ::
channel
channel
## (
## (
## 'gfmd'
## 'gfmd'
## ,
## ,
function
function
## (
## (
## $user
## $user
## )
## )
## {
## {
return
return
## $user
## $user
## ->
## ->
hasRole
hasRole
## (
## (
## 'gfmd-admin'
## 'gfmd-admin'
## )
## )
## ;
## ;
## }
## }
## )
## )
## ;
## ;
// Broadcasting events
// Broadcasting events
php artisan make
php artisan make
## :
## :
event VehicleLocationUpdated
event VehicleLocationUpdated
php artisan make
php artisan make
## :
## :
event WorkTicketAssigned
event WorkTicketAssigned
php artisan make
php artisan make
## :
## :
event GeoFenceViolation
event GeoFenceViolation
php artisan make
php artisan make
## :
## :
event DisposalApproved
event DisposalApproved

yaml
## Docker
## Docker
## :
## :
## 25.0.0
## 25.0.0
## Docker Compose
## Docker Compose
## :
## :
## 2.24.0
## 2.24.0
# docker-compose.yml structure
# docker-compose.yml structure
services
services
## :
## :
app
app
## :
## :
build
build
## :
## :
## ./docker/php
## ./docker/php
volumes
volumes
## :
## :
## -
## -
## ./
## ./
## :
## :
## /var/www/html
## /var/www/html
depends_on
depends_on
## :
## :
## -
## -
postgres
postgres
## -
## -
redis
redis
environment
environment
## :
## :
## -
## -
APP_ENV=production
APP_ENV=production
## -
## -
DB_HOST=postgres
DB_HOST=postgres
## -
## -
REDIS_HOST=redis
REDIS_HOST=redis


reverb
reverb
## :
## :
build
build
## :
## :
## ./docker/reverb
## ./docker/reverb
command
command
## :
## :
php artisan reverb
php artisan reverb
## :
## :
start
start
ports
ports
## :
## :
## -
## -
## "8080:8080"
## "8080:8080"


queue
queue
## :
## :
build
build
## :
## :
## ./docker/php
## ./docker/php
command
command
## :
## :
php artisan queue
php artisan queue
## :
## :
work
work
## -
## -
## -
## -
tries=3
tries=3
## -
## -
## -
## -
timeout=90
timeout=90
deploy
deploy
## :
## :
replicas
replicas
## :
## :
## 3
## 3


scheduler
scheduler
## :
## :
build
build
## :
## :
## ./docker/php
## ./docker/php
command
command
## :
## :
php artisan schedule
php artisan schedule
## :
## :
work
work


postgres
postgres
## :
## :
image
image
## :
## :
postgis/postgis
postgis/postgis
## :
## :
## 16
## 16
## -
## -
## 3.4
## 3.4
volumes
volumes
## :
## :
## -
## -
postgres
postgres
## -
## -
data
data
## :
## :
## /var/lib/postgresql/data
## /var/lib/postgresql/data
environment
environment
## :
## :
## POSTGRES_DB
## POSTGRES_DB
## :
## :
fleet_management
fleet_management
## POSTGRES_USER
## POSTGRES_USER
## :
## :
fleet_user
fleet_user
## POSTGRES_PASSWORD
## POSTGRES_PASSWORD
## :
## :
## $
## $
## {
## {
## DB_PASSWORD
## DB_PASSWORD
## }
## }


redis
redis
## :
## :
image
image
## :
## :
redis
redis
## :
## :
## 7.2
## 7.2
## -
## -
alpine
alpine
volumes
volumes
## :
## :

## Container Registry
##  MONITORING STACK
## Metrics & Dashboards
volumes
volumes
## :
## :
## -
## -
redis
redis
## -
## -
data
data
## :
## :
## /data
## /data

nginx
nginx
## :
## :
image
image
## :
## :
nginx
nginx
## :
## :
## 1.25
## 1.25
## -
## -
alpine
alpine
ports
ports
## :
## :
## -
## -
## "80:80"
## "80:80"
## -
## -
## "443:443"
## "443:443"
volumes
volumes
## :
## :
## -
## -
## ./docker/nginx/nginx.conf
## ./docker/nginx/nginx.conf
## :
## :
## /etc/nginx/nginx.conf
## /etc/nginx/nginx.conf
## -
## -
## ./public
## ./public
## :
## :
## /var/www/html/public
## /var/www/html/public
## -
## -
## ./ssl
## ./ssl
## :
## :
## /etc/nginx/ssl
## /etc/nginx/ssl
yaml
## Registry
## Registry
## :
## :
## Harbor 2.10.0 (self
## Harbor 2.10.0 (self
## -
## -
hosted)
hosted)
## Location
## Location
## :
## :
registry.fleet.go.ke
registry.fleet.go.ke
## Images
## Images
## :
## :
## -
## -
fleet
fleet
## -
## -
app
app
## :
## :
latest
latest
## -
## -
fleet
fleet
## -
## -
app
app
## :
## :
v1.0.0
v1.0.0
## -
## -
fleet
fleet
## -
## -
reverb
reverb
## :
## :
latest
latest
## -
## -
fleet
fleet
## -
## -
queue
queue
## :
## :
latest
latest

## Logging Stack
yaml
## Prometheus
## Prometheus
## :
## :
## 2.49.1
## 2.49.1
## Grafana
## Grafana
## :
## :
## 10.3.3
## 10.3.3
## Node Exporter
## Node Exporter
## :
## :
## 1.7.0
## 1.7.0
PostgreSQL Exporter
PostgreSQL Exporter
## :
## :
## 0.15.0
## 0.15.0
## Redis Exporter
## Redis Exporter
## :
## :
## 1.55.0
## 1.55.0
## Laravel Prometheus Exporter
## Laravel Prometheus Exporter
## :
## :
## 1.0
## 1.0
# Prometheus configuration
# Prometheus configuration
scrape_configs
scrape_configs
## :
## :
## -
## -
job_name
job_name
## :
## :
## 'fleet-app'
## 'fleet-app'
static_configs
static_configs
## :
## :
## -
## -
targets
targets
## :
## :
## [
## [
## 'app:9090'
## 'app:9090'
## ]
## ]


## -
## -
job_name
job_name
## :
## :
## 'postgres'
## 'postgres'
static_configs
static_configs
## :
## :
## -
## -
targets
targets
## :
## :
## [
## [
## 'postgres-exporter:9187'
## 'postgres-exporter:9187'
## ]
## ]


## -
## -
job_name
job_name
## :
## :
## 'redis'
## 'redis'
static_configs
static_configs
## :
## :
## -
## -
targets
targets
## :
## :
## [
## [
## 'redis-exporter:9121'
## 'redis-exporter:9121'
## ]
## ]

##  SECURITY STACK
## SSL/TLS
yaml
## Loki
## Loki
## :
## :
## 2.9.3
## 2.9.3
## Promtail
## Promtail
## :
## :
## 2.9.3
## 2.9.3
## Grafana
## Grafana
## :
## :
## 10.3.3
## 10.3.3
# Promtail configuration
# Promtail configuration
positions
positions
## :
## :
filename
filename
## :
## :
## /tmp/positions.yaml
## /tmp/positions.yaml
clients
clients
## :
## :
## -
## -
url
url
## :
## :
http
http
## :
## :
## //loki
## //loki
## :
## :
## 3100/loki/api/v1/push
## 3100/loki/api/v1/push
scrape_configs
scrape_configs
## :
## :
## -
## -
job_name
job_name
## :
## :
laravel
laravel
static_configs
static_configs
## :
## :
## -
## -
targets
targets
## :
## :
## -
## -
localhost
localhost
labels
labels
## :
## :
job
job
## :
## :
laravel
laravel
## __path__
## __path__
## :
## :
## /var/www/html/storage/logs/
## /var/www/html/storage/logs/
## *.log
## *.log


## -
## -
job_name
job_name
## :
## :
nginx
nginx
static_configs
static_configs
## :
## :
## -
## -
targets
targets
## :
## :
## -
## -
localhost
localhost
labels
labels
## :
## :
job
job
## :
## :
nginx
nginx
## __path__
## __path__
## :
## :
## /var/log/nginx/
## /var/log/nginx/
## *log
## *log

## Web Application Firewall
## Authentication Packages
##  INTEGRATION STACK
G2G Integration Endpoints
yaml
## Certificate Authority
## Certificate Authority
## :
## :
## Let's Encrypt
## Let's Encrypt
## Tool
## Tool
## :
## :
## Certbot 2.8.0
## Certbot 2.8.0
## Auto-renewal
## Auto-renewal
## :
## :
Enabled (cron job every 12 hours)
Enabled (cron job every 12 hours)
# Nginx SSL configuration
# Nginx SSL configuration
ssl_protocols TLSv1.2 TLSv1.3;
ssl_protocols TLSv1.2 TLSv1.3;
ssl_ciphers 'ECDHE
ssl_ciphers 'ECDHE
## -
## -
## ECDSA
## ECDSA
## -
## -
## AES128
## AES128
## -
## -
## GCM
## GCM
## -
## -
## SHA256
## SHA256
## :
## :
## ECDHE
## ECDHE
## -
## -
## RSA
## RSA
## -
## -
## AES128
## AES128
## -
## -
## GCM
## GCM
## -
## -
## SHA256';
## SHA256';
ssl_prefer_server_ciphers off;
ssl_prefer_server_ciphers off;
ssl_session_cache shared
ssl_session_cache shared
## :
## :
## SSL
## SSL
## :
## :
## 10m;
## 10m;
ssl_session_timeout 10m;
ssl_session_timeout 10m;
yaml
## WA F
## WA F
## :
## :
ModSecurity 3.0.10
ModSecurity 3.0.10
## Rule Set
## Rule Set
## :
## :
OWASP Core Rule Set (CRS) 4.0.0
OWASP Core Rule Set (CRS) 4.0.0
# ModSecurity configuration
# ModSecurity configuration
SecRuleEngine On
SecRuleEngine On
SecRequestBodyAccess On
SecRequestBodyAccess On
SecResponseBodyAccess Off
SecResponseBodyAccess Off
SecAuditLog /var/log/modsec_audit.log
SecAuditLog /var/log/modsec_audit.log
## Include /etc/nginx/modsecurity/crs
## Include /etc/nginx/modsecurity/crs
## -
## -
setup.conf
setup.conf
## Include /etc/nginx/modsecurity/rules/
## Include /etc/nginx/modsecurity/rules/
## *.conf
## *.conf
json
## {
## {
## "require"
## "require"
## :
## :
## {
## {
## "laravel/sanctum"
## "laravel/sanctum"
## :
## :
## "^4.0"
## "^4.0"
## ,
## ,
// Stateless API authentication
// Stateless API authentication
## "laravel/passport"
## "laravel/passport"
## :
## :
## "^12.0"
## "^12.0"
## ,
## ,
// OAuth2 for G2G integrations
// OAuth2 for G2G integrations
## "spatie/laravel-permission"
## "spatie/laravel-permission"
## :
## :
## "^6.4"
## "^6.4"
## // RBAC
## // RBAC
## }
## }
## }
## }

## Integration Services
## 離離 TESTING STACK
php
// config/integrations.php
// config/integrations.php
return
return
## [
## [
## 'gfmd'
## 'gfmd'
## =>
## =>
## [
## [
## 'base_url'
## 'base_url'
## =>
## =>
env
env
## (
## (
## 'GFMD_API_URL'
## 'GFMD_API_URL'
## )
## )
## ,
## ,
## 'api_key'
## 'api_key'
## =>
## =>
env
env
## (
## (
## 'GFMD_API_KEY'
## 'GFMD_API_KEY'
## )
## )
## ,
## ,
## 'timeout'
## 'timeout'
## =>
## =>
## 30
## 30
## ,
## ,
## ]
## ]
## ,
## ,


## 'ntsa'
## 'ntsa'
## =>
## =>
## [
## [
## 'base_url'
## 'base_url'
## =>
## =>
env
env
## (
## (
## 'NTSA_API_URL'
## 'NTSA_API_URL'
## )
## )
## ,
## ,
## 'certificate'
## 'certificate'
## =>
## =>
env
env
## (
## (
## 'NTSA_CERT_PATH'
## 'NTSA_CERT_PATH'
## )
## )
## ,
## ,
## 'private_key'
## 'private_key'
## =>
## =>
env
env
## (
## (
## 'NTSA_KEY_PATH'
## 'NTSA_KEY_PATH'
## )
## )
## ,
## ,
## 'timeout'
## 'timeout'
## =>
## =>
## 15
## 15
## ,
## ,
## ]
## ]
## ,
## ,

## 'ifmis'
## 'ifmis'
## =>
## =>
## [
## [
## 'wsdl_url'
## 'wsdl_url'
## =>
## =>
env
env
## (
## (
## 'IFMIS_WSDL_URL'
## 'IFMIS_WSDL_URL'
## )
## )
## ,
## ,
## 'username'
## 'username'
## =>
## =>
env
env
## (
## (
## 'IFMIS_USERNAME'
## 'IFMIS_USERNAME'
## )
## )
## ,
## ,
## 'password'
## 'password'
## =>
## =>
env
env
## (
## (
## 'IFMIS_PASSWORD'
## 'IFMIS_PASSWORD'
## )
## )
## ,
## ,
## 'soap_version'
## 'soap_version'
## =>
## =>
## SOAP_1_2
## SOAP_1_2
## ,
## ,
## ]
## ]
## ,
## ,


## 'cmte'
## 'cmte'
## =>
## =>
## [
## [
## 'base_url'
## 'base_url'
## =>
## =>
env
env
## (
## (
## 'CMTE_API_URL'
## 'CMTE_API_URL'
## )
## )
## ,
## ,
## 'api_key'
## 'api_key'
## =>
## =>
env
env
## (
## (
## 'CMTE_API_KEY'
## 'CMTE_API_KEY'
## )
## )
## ,
## ,
## 'timeout'
## 'timeout'
## =>
## =>
## 20
## 20
## ,
## ,
## ]
## ]
## ,
## ,
## ]
## ]
## ;
## ;
php
// app/Services/Integrations/
// app/Services/Integrations/
├── NTSAService
├── NTSAService
## .
## .
php
php
// Vehicle registration, insurance validation
// Vehicle registration, insurance validation
├── IFMISService
├── IFMISService
## .
## .
php
php
// Budget queries, payment processing
// Budget queries, payment processing
├── CMTEService
├── CMTEService
## .
## .
php
php
// Garage registry, inspection approvals
// Garage registry, inspection approvals
├── GFMDService
├── GFMDService
## .
## .
php
php
// Central fleet registry sync
// Central fleet registry sync
└── FuelCardService
└── FuelCardService
## .
## .
php
php
// Fuel transaction validation
// Fuel transaction validation

## Testing Framework
## Test Structure
## Test Commands
json
## {
## {
## "require-dev"
## "require-dev"
## :
## :
## {
## {
## "pestphp/pest"
## "pestphp/pest"
## :
## :
## "^2.34"
## "^2.34"
## ,
## ,
## "pestphp/pest-plugin-laravel"
## "pestphp/pest-plugin-laravel"
## :
## :
## "^2.3"
## "^2.3"
## ,
## ,
## "pestphp/pest-plugin-faker"
## "pestphp/pest-plugin-faker"
## :
## :
## "^2.0"
## "^2.0"
## ,
## ,
## "mockery/mockery"
## "mockery/mockery"
## :
## :
## "^1.6"
## "^1.6"
## ,
## ,
## "nunomaduro/collision"
## "nunomaduro/collision"
## :
## :
## "^8.1"
## "^8.1"
## ,
## ,
## "fakerphp/faker"
## "fakerphp/faker"
## :
## :
## "^1.23"
## "^1.23"
## }
## }
## }
## }
tests/
tests/
├── Feature/                    # Integration tests
├── Feature/                    # Integration tests
## │   ├── Auth/
## │   ├── Auth/
## │   ├── Fleet/
## │   ├── Fleet/
│   ├── WorkTickets/
│   ├── WorkTickets/
## │   ├── Maintenance/
## │   ├── Maintenance/
## │   └── Integrations/
## │   └── Integrations/
├── Unit/                       # Unit tests
├── Unit/                       # Unit tests
## │   ├── Models/
## │   ├── Models/
## │   ├── Services/
## │   ├── Services/
## │   └── Helpers/
## │   └── Helpers/
├── Browser/                    # Laravel Dusk (E2E)
├── Browser/                    # Laravel Dusk (E2E)
│   ├── LoginTest.php
│   ├── LoginTest.php
│   ├── WorkTicketFlowTest.php
│   ├── WorkTicketFlowTest.php
│   └── DashboardTest.php
│   └── DashboardTest.php
└── Pest.php                    # Pest configuration
└── Pest.php                    # Pest configuration

##  CODE QUALITY & STANDARDS
PHP Code Style
bash
# Run all tests
# Run all tests
php artisan
php artisan
test
test
# Run specific suite
# Run specific suite
php artisan
php artisan
test
test
## --testsuite
## --testsuite
## =
## =
## Feature
## Feature
# Run with coverage
# Run with coverage
php artisan
php artisan
test
test
## --coverage
## --coverage
# Run browser tests
# Run browser tests
php artisan dusk
php artisan dusk
# Flutter mobile tests
# Flutter mobile tests
cd
cd
mobile
mobile
## &&
## &&
flutter
flutter
test
test
json
## {
## {
## "require-dev"
## "require-dev"
## :
## :
## {
## {
## "laravel/pint"
## "laravel/pint"
## :
## :
## "^1.13"
## "^1.13"
## }
## }
## }
## }

TypeScript/React Linting
## Flutter Linting
bash
# Run Pint (Laravel's opinionated PHP CS Fixer)
# Run Pint (Laravel's opinionated PHP CS Fixer)
## ./vendor/bin/pint
## ./vendor/bin/pint
# pint.json configuration
# pint.json configuration
## {
## {
## "preset"
## "preset"
## :
## :
## "laravel"
## "laravel"
## ,
## ,
## "rules"
## "rules"
## :
## :
## {
## {
## "simplified_null_return"
## "simplified_null_return"
## :
## :
true,
true,
## "no_unused_imports"
## "no_unused_imports"
## :
## :
true,
true,
## "ordered_imports"
## "ordered_imports"
## :
## :
## {
## {
## "sort_algorithm"
## "sort_algorithm"
## :
## :
## "alpha"
## "alpha"
## }
## }
## }
## }
## }
## }
json
## {
## {
"devDependencies"
"devDependencies"
## :
## :
## {
## {
## "eslint"
## "eslint"
## :
## :
## "^8.56.0"
## "^8.56.0"
## ,
## ,
## "@typescript-eslint/eslint-plugin"
## "@typescript-eslint/eslint-plugin"
## :
## :
## "^6.19.0"
## "^6.19.0"
## ,
## ,
## "@typescript-eslint/parser"
## "@typescript-eslint/parser"
## :
## :
## "^6.19.0"
## "^6.19.0"
## ,
## ,
## "eslint-plugin-react"
## "eslint-plugin-react"
## :
## :
## "^7.33.2"
## "^7.33.2"
## ,
## ,
## "eslint-plugin-react-hooks"
## "eslint-plugin-react-hooks"
## :
## :
## "^4.6.0"
## "^4.6.0"
## ,
## ,
## "prettier"
## "prettier"
## :
## :
## "^3.2.4"
## "^3.2.4"
## }
## }
## ,
## ,
## "scripts"
## "scripts"
## :
## :
## {
## {
## "lint"
## "lint"
## :
## :
"eslint resources/js --ext .ts,.tsx"
"eslint resources/js --ext .ts,.tsx"
## ,
## ,
## "format"
## "format"
## :
## :
## "prettier --write \"resources/js/**/*.{ts,tsx}\""
## "prettier --write \"resources/js/**/*.{ts,tsx}\""
## }
## }
## }
## }

##  CI/CD PIPELINE
GitHub Actions Workflow
yaml
dev_dependencies
dev_dependencies
## :
## :
flutter_lints
flutter_lints
## :
## :
## ^3.0.1
## ^3.0.1
very_good_analysis
very_good_analysis
## :
## :
## ^5.1.0
## ^5.1.0
# analysis_options.yaml
# analysis_options.yaml
include
include
## :
## :
package
package
## :
## :
very_good_analysis/analysis_options.yaml
very_good_analysis/analysis_options.yaml
linter
linter
## :
## :
rules
rules
## :
## :
always_declare_return_types
always_declare_return_types
## :
## :
true
true
avoid_print
avoid_print
## :
## :
true
true
prefer_const_constructors
prefer_const_constructors
## :
## :
true
true

yaml
## # .github/workflows/deploy.yml
## # .github/workflows/deploy.yml
name
name
## :
## :
## Deploy Fleet Management System
## Deploy Fleet Management System
on
on
## :
## :
push
push
## :
## :
branches
branches
## :
## :
## [
## [
main
main
## ,
## ,
staging
staging
## ]
## ]
pull_request
pull_request
## :
## :
branches
branches
## :
## :
## [
## [
main
main
## ]
## ]
jobs
jobs
## :
## :
test-backend
test-backend
## :
## :
runs-on
runs-on
## :
## :
ubuntu
ubuntu
## -
## -
latest
latest
services
services
## :
## :
postgres
postgres
## :
## :
image
image
## :
## :
postgis/postgis
postgis/postgis
## :
## :
## 16
## 16
## -
## -
## 3.4
## 3.4
env
env
## :
## :
## POSTGRES_PASSWORD
## POSTGRES_PASSWORD
## :
## :
postgres
postgres
options
options
## :
## :
## >
## >
## -
## -
## -
## -
## -
## -
health
health
## -
## -
cmd pg_isready
cmd pg_isready
## -
## -
## -
## -
health
health
## -
## -
interval 10s
interval 10s
## -
## -
## -
## -
health
health
## -
## -
timeout 5s
timeout 5s
## -
## -
## -
## -
health
health
## -
## -
retries 5
retries 5
redis
redis
## :
## :
image
image
## :
## :
redis
redis
## :
## :
## 7.2
## 7.2
## -
## -
alpine
alpine
options
options
## :
## :
## >
## >
## -
## -
## -
## -
## -
## -
health
health
## -
## -
cmd "redis
cmd "redis
## -
## -
cli ping"
cli ping"
## -
## -
## -
## -
health
health
## -
## -
interval 10s
interval 10s
## -
## -
## -
## -
health
health
## -
## -
timeout 5s
timeout 5s
## -
## -
## -
## -
health
health
## -
## -
retries 5
retries 5
steps
steps
## :
## :
## -
## -
uses
uses
## :
## :
actions/checkout@v4
actions/checkout@v4


## -
## -
name
name
## :
## :
Setup PHP
Setup PHP
uses
uses
## :
## :
shivammathur/setup
shivammathur/setup
## -
## -
php@v2
php@v2
with
with
## :
## :
php-version
php-version
## :
## :
## '8.3'
## '8.3'
extensions
extensions
## :
## :
pdo
pdo
## ,
## ,
pdo_pgsql
pdo_pgsql
## ,
## ,
redis
redis
## ,
## ,
gd
gd
coverage
coverage
## :
## :
xdebug
xdebug


## -
## -
name
name
## :
## :
Install Composer dependencies
Install Composer dependencies
run
run
## :
## :
composer install
composer install
## -
## -
## -
## -
no
no
## -
## -
progress
progress
## -
## -
## -
## -
prefer
prefer
## -
## -
dist
dist
## -
## -
## -
## -
optimize
optimize
## -
## -
autoloader
autoloader


## -
## -
name
name
## :
## :
## Copy .env
## Copy .env
run
run
## :
## :
php
php
## -
## -
r "copy('.env.testing'
r "copy('.env.testing'
## ,
## ,
## '.env');"
## '.env');"



## -
## -
name
name
## :
## :
Generate application key
Generate application key
run
run
## :
## :
php artisan key
php artisan key
## :
## :
generate
generate


## -
## -
name
name
## :
## :
Run migrations
Run migrations
run
run
## :
## :
php artisan migrate
php artisan migrate
## -
## -
## -
## -
force
force

## -
## -
name
name
## :
## :
Run tests
Run tests
run
run
## :
## :
php artisan test
php artisan test
## -
## -
## -
## -
coverage
coverage
## -
## -
## -
## -
min=80
min=80


## -
## -
name
name
## :
## :
Run Pint (code style)
Run Pint (code style)
run
run
## :
## :
## ./vendor/bin/pint
## ./vendor/bin/pint
## -
## -
## -
## -
test
test


test-frontend
test-frontend
## :
## :
runs-on
runs-on
## :
## :
ubuntu
ubuntu
## -
## -
latest
latest
steps
steps
## :
## :
## -
## -
uses
uses
## :
## :
actions/checkout@v4
actions/checkout@v4


## -
## -
name
name
## :
## :
## Setup Node.js
## Setup Node.js
uses
uses
## :
## :
actions/setup
actions/setup
## -
## -
node@v4
node@v4
with
with
## :
## :
node-version
node-version
## :
## :
## '20'
## '20'
cache
cache
## :
## :
## 'npm'
## 'npm'


## -
## -
name
name
## :
## :
Install dependencies
Install dependencies
run
run
## :
## :
npm ci
npm ci


## -
## -
name
name
## :
## :
Run ESLint
Run ESLint
run
run
## :
## :
npm run lint
npm run lint


## -
## -
name
name
## :
## :
Type check
Type check
run
run
## :
## :
npx tsc
npx tsc
## -
## -
## -
## -
noEmit
noEmit


## -
## -
name
name
## :
## :
## Build
## Build
run
run
## :
## :
npm run build
npm run build


test-mobile
test-mobile
## :
## :
runs-on
runs-on
## :
## :
ubuntu
ubuntu
## -
## -
latest
latest
steps
steps
## :
## :
## -
## -
uses
uses
## :
## :
actions/checkout@v4
actions/checkout@v4


## -
## -
name
name
## :
## :
## Setup Flutter
## Setup Flutter
uses
uses
## :
## :
subosito/flutter
subosito/flutter
## -
## -
action@v2
action@v2
with
with
## :
## :
flutter-version
flutter-version
## :
## :
## '3.24.5'
## '3.24.5'
channel
channel
## :
## :
## 'stable'
## 'stable'



## -
## -
name
name
## :
## :
Install dependencies
Install dependencies
run
run
## :
## :
cd mobile
cd mobile
## &&
## &&
flutter pub get
flutter pub get


## -
## -
name
name
## :
## :
Run tests
Run tests
run
run
## :
## :
cd mobile
cd mobile
## &&
## &&
flutter test
flutter test


## -
## -
name
name
## :
## :
Analyze code
Analyze code
run
run
## :
## :
cd mobile
cd mobile
## &&
## &&
flutter analyze
flutter analyze


build-and-push
build-and-push
## :
## :
needs
needs
## :
## :
## [
## [
test
test
## -
## -
backend
backend
## ,
## ,
test
test
## -
## -
frontend
frontend
## ,
## ,
test
test
## -
## -
mobile
mobile
## ]
## ]
runs-on
runs-on
## :
## :
ubuntu
ubuntu
## -
## -
latest
latest
if
if
## :
## :
github.ref == 'refs/heads/main'
github.ref == 'refs/heads/main'
steps
steps
## :
## :
## -
## -
uses
uses
## :
## :
actions/checkout@v4
actions/checkout@v4


## -
## -
name
name
## :
## :
Build Docker images
Build Docker images
run
run
## :
## :
## |
## |
docker build -t harbor.fleet.go.ke/fleet-app:${{ github.sha }} .
docker build -t harbor.fleet.go.ke/fleet-app:${{ github.sha }} .
docker build -t harbor.fleet.go.ke/fleet-app:latest .
docker build -t harbor.fleet.go.ke/fleet-app:latest .


## -
## -
name
name
## :
## :
Push to Harbor
Push to Harbor
run
run
## :
## :
## |
## |
echo ${{ secrets.HARBOR_PASSWORD }} | docker login harbor.fleet.go.ke -u ${{ secrets.HARBOR_USERNAME }} --password-stdin
echo ${{ secrets.HARBOR_PASSWORD }} | docker login harbor.fleet.go.ke -u ${{ secrets.HARBOR_USERNAME }} --password-stdin
docker push harbor.fleet.go.ke/fleet-app:${{ github.sha }}
docker push harbor.fleet.go.ke/fleet-app:${{ github.sha }}
docker push harbor.fleet.go.ke/fleet-app:
docker push harbor.fleet.go.ke/fleet-app: