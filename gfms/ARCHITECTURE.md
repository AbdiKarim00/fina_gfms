# GFMS System Architecture

## System Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                    Kenya Government Fleet Management System          │
│                              (GFMS)                                  │
└─────────────────────────────────────────────────────────────────────┘

┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐
│   Web Frontend   │  │   Mobile App     │  │  External APIs   │
│  React + Inertia │  │  Flutter 3.24    │  │  NTSA/IFMIS/CMTE │
│   TypeScript     │  │  Offline-first   │  │                  │
└────────┬─────────┘  └────────┬─────────┘  └────────┬─────────┘
         │                     │                      │
         │ HTTP/WebSocket      │ REST API             │ OAuth2/SOAP
         │                     │                      │
         └─────────────────────┼──────────────────────┘
                               │
                    ┌──────────▼──────────┐
                    │   Laravel 11 API    │
                    │   PHP 8.3 + Octane  │
                    │   Sanctum/Passport  │
                    └──────────┬──────────┘
                               │
         ┌─────────────────────┼─────────────────────┐
         │                     │                     │
    ┌────▼────┐         ┌──────▼──────┐      ┌──────▼──────┐
    │PostgreSQL│         │    Redis    │      │   Reverb    │
    │ + PostGIS│         │   Cache +   │      │  WebSocket  │
    │  16.3.4  │         │   Queues    │      │   Server    │
    └──────────┘         └─────────────┘      └─────────────┘
```

## Container Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Docker Network                            │
│                                                                  │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐       │
│  │  Nginx   │  │   App    │  │  Queue   │  │ Scheduler│       │
│  │  :8000   │─▶│ PHP-FPM  │  │  Worker  │  │  Cron    │       │
│  └──────────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘       │
│                     │              │              │             │
│                     └──────────────┼──────────────┘             │
│                                    │                            │
│  ┌──────────┐  ┌──────────┐  ┌────▼─────┐  ┌──────────┐       │
│  │PostgreSQL│  │  Redis   │  │  Reverb  │  │ MailHog  │       │
│  │  :5432   │  │  :6379   │  │  :8080   │  │ :1025/25 │       │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘       │
│                                                                  │
│  ┌──────────┐                                                   │
│  │ pgAdmin  │                                                   │
│  │  :5050   │                                                   │
│  └──────────┘                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## Database Schema Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    PostgreSQL + PostGIS                          │
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │ auth schema  │  │ fleet schema │  │ maintenance  │         │
│  │              │  │              │  │   schema     │         │
│  │ - users      │  │ - vehicles   │  │ - records    │         │
│  │ - roles      │  │ - drivers    │  │ - schedules  │         │
│  │ - permissions│  │ - assignments│  │ - work_orders│         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │  tracking    │  │   finance    │  │ integrations │         │
│  │   schema     │  │   schema     │  │   schema     │         │
│  │              │  │              │  │              │         │
│  │ - gps_logs   │  │ - budgets    │  │ - ntsa_sync  │         │
│  │ - geofences  │  │ - expenses   │  │ - ifmis_sync │         │
│  │ - routes     │  │ - fuel_costs │  │ - cmte_sync  │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
│                                                                  │
│  ┌──────────────┐                                               │
│  │ audit schema │                                               │
│  │              │                                               │
│  │ - activity   │                                               │
│  │ - changes    │                                               │
│  │ - logs       │                                               │
│  └──────────────┘                                               │
└─────────────────────────────────────────────────────────────────┘
```

## Application Layer Architecture

### Backend (Laravel 11)

```
┌─────────────────────────────────────────────────────────────────┐
│                        Laravel Application                       │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                    HTTP Layer                             │  │
│  │  Routes → Middleware → Controllers → Resources            │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                   Service Layer                          │  │
│  │  - VehicleService                                        │  │
│  │  - DriverService                                         │  │
│  │  - TrackingService                                       │  │
│  │  - IntegrationService (NTSA, IFMIS, CMTE)               │  │
│  └──────────────────────────▼──────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                   Repository Layer                       │  │
│  │  - VehicleRepository                                     │  │
│  │  - DriverRepository                                      │  │
│  │  - TrackingRepository                                    │  │
│  └──────────────────────────▼──────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                    Data Layer                            │  │
│  │  Eloquent Models + Spatial Queries                       │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                  Background Jobs                          │  │
│  │  - ProcessGpsData                                        │  │
│  │  - SendNotifications                                     │  │
│  │  - GenerateReports                                       │  │
│  │  - SyncExternalData                                      │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                  Broadcasting                             │  │
│  │  - VehicleLocationUpdated                                │  │
│  │  - WorkTicketAssigned                                    │  │
│  │  - GeoFenceViolation                                     │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

### Frontend (React + Inertia.js)

```
┌─────────────────────────────────────────────────────────────────┐
│                      React Application                           │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                    Pages (Inertia)                        │  │
│  │  Dashboard | Fleet | Drivers | Maintenance | Reports     │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                    Layouts                               │  │
│  │  AuthLayout | DashboardLayout | AdminLayout             │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                   Components                             │  │
│  │  VehicleCard | DriverList | MapView | Charts            │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                 State Management                         │  │
│  │  Zustand (Client) + React Query (Server)                │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                    API Layer                             │  │
│  │  Axios + Inertia.js                                      │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

### Mobile (Flutter)

```
┌─────────────────────────────────────────────────────────────────┐
│                     Flutter Application                          │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                  Presentation Layer                       │  │
│  │  Screens | Widgets | Providers (Riverpod)                │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                   Domain Layer                           │  │
│  │  Use Cases | Entities | Repository Interfaces           │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                    Data Layer                            │  │
│  │  - Remote: Retrofit API Client                          │  │
│  │  - Local: Drift SQLite Database                         │  │
│  │  - Sync: Background Service                             │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │                    Services                              │  │
│  │  - LocationService (GPS tracking)                       │  │
│  │  - NotificationService (Push notifications)             │  │
│  │  - SyncService (Offline sync)                           │  │
│  │  - BackgroundService (Background tasks)                 │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

## Data Flow

### GPS Tracking Flow

```
┌──────────────┐
│ Mobile App   │ GPS Location (every 30s)
│ (Driver)     │────────────────────────────┐
└──────────────┘                            │
                                            ▼
                                   ┌─────────────────┐
                                   │  Laravel API    │
                                   │  /api/v1/gps    │
                                   └────────┬────────┘
                                            │
                                   ┌────────▼────────┐
                                   │  Queue Job      │
                                   │ ProcessGpsData  │
                                   └────────┬────────┘
                                            │
                        ┌───────────────────┼───────────────────┐
                        │                   │                   │
                ┌───────▼────────┐  ┌──────▼──────┐  ┌────────▼────────┐
                │   PostgreSQL   │  │   Redis     │  │    Reverb       │
                │ tracking.gps   │  │   Cache     │  │  Broadcast      │
                │    _logs       │  │             │  │  to Web/Mobile  │
                └────────────────┘  └─────────────┘  └─────────────────┘
```

### Work Ticket Flow

```
┌──────────────┐
│ Web Frontend │ Create Work Ticket
│ (Fleet Mgr)  │────────────────────────────┐
└──────────────┘                            │
                                            ▼
                                   ┌─────────────────┐
                                   │  Laravel API    │
                                   │ /api/v1/tickets │
                                   └────────┬────────┘
                                            │
                        ┌───────────────────┼───────────────────┐
                        │                   │                   │
                ┌───────▼────────┐  ┌──────▼──────┐  ┌────────▼────────┐
                │   PostgreSQL   │  │   Queue     │  │    Reverb       │
                │ fleet.tickets  │  │ Notification│  │  Broadcast      │
                └────────────────┘  └──────┬──────┘  └────────┬────────┘
                                           │                   │
                                           ▼                   ▼
                                   ┌─────────────┐   ┌─────────────────┐
                                   │   Email     │   │  Mobile App     │
                                   │  MailHog    │   │  Push Notif     │
                                   └─────────────┘   └─────────────────┘
```

## Security Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Security Layers                           │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Layer 1: Network Security                               │  │
│  │  - HTTPS/TLS 1.3                                         │  │
│  │  - WAF (ModSecurity)                                     │  │
│  │  - Rate Limiting                                         │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │  Layer 2: Authentication                                 │  │
│  │  - Laravel Sanctum (Mobile API tokens)                  │  │
│  │  - Laravel Passport (OAuth2 for G2G)                    │  │
│  │  - Session-based (Web)                                  │  │
│  │  - MFA (Multi-factor authentication)                    │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │  Layer 3: Authorization                                  │  │
│  │  - RBAC (Spatie Permissions)                            │  │
│  │  - Policy-based access control                          │  │
│  │  - Resource-level permissions                           │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │  Layer 4: Data Security                                  │  │
│  │  - Encryption at rest                                    │  │
│  │  - Encryption in transit                                 │  │
│  │  - Secure storage (Laravel Vault)                       │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────▼──────────────────────────────┐  │
│  │  Layer 5: Audit & Monitoring                             │  │
│  │  - Activity logging (Spatie)                            │  │
│  │  - Audit trails                                         │  │
│  │  - Security event monitoring                            │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

## Integration Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    External Integrations                         │
│                                                                  │
│  ┌──────────────┐         ┌──────────────┐                     │
│  │     NTSA     │◄────────┤ Integration  │                     │
│  │  (License    │  REST   │   Service    │                     │
│  │ Validation)  │         └──────┬───────┘                     │
│  └──────────────┘                │                             │
│                                   │                             │
│  ┌──────────────┐                │                             │
│  │    IFMIS     │◄───────────────┤                             │
│  │  (Budget     │  SOAP           │                             │
│  │   Sync)      │                │                             │
│  └──────────────┘                │                             │
│                                   │                             │
│  ┌──────────────┐                │                             │
│  │     CMTE     │◄───────────────┤                             │
│  │ (Inspection  │  REST           │                             │
│  │  Approval)   │                │                             │
│  └──────────────┘                │                             │
│                                   │                             │
│  ┌──────────────┐                │                             │
│  │ Fuel Card    │◄───────────────┤                             │
│  │  Providers   │  REST           │                             │
│  └──────────────┘                │                             │
│                                   │                             │
│  ┌──────────────┐                │                             │
│  │ GPS Vendors  │◄───────────────┘                             │
│  │ (Tracking)   │  WebHook/API                                 │
│  └──────────────┘                                               │
└─────────────────────────────────────────────────────────────────┘
```

## Deployment Architecture (Future)

```
┌─────────────────────────────────────────────────────────────────┐
│                         AWS Cloud                                │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                    Load Balancer                          │  │
│  │                  (Application LB)                         │  │
│  └──────────────────────────▼────────────────────────────────┘  │
│                              │                                  │
│         ┌────────────────────┼────────────────────┐            │
│         │                    │                    │            │
│  ┌──────▼──────┐      ┌──────▼──────┐     ┌──────▼──────┐    │
│  │   ECS/EKS   │      │   ECS/EKS   │     │   ECS/EKS   │    │
│  │  Container  │      │  Container  │     │  Container  │    │
│  │   Instance  │      │   Instance  │     │   Instance  │    │
│  └──────┬──────┘      └──────┬──────┘     └──────┬──────┘    │
│         │                    │                    │            │
│         └────────────────────┼────────────────────┘            │
│                              │                                  │
│         ┌────────────────────┼────────────────────┐            │
│         │                    │                    │            │
│  ┌──────▼──────┐      ┌──────▼──────┐     ┌──────▼──────┐    │
│  │     RDS     │      │ ElastiCache │     │     S3      │    │
│  │ PostgreSQL  │      │    Redis    │     │   Storage   │    │
│  └─────────────┘      └─────────────┘     └─────────────┘    │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                    Monitoring                             │  │
│  │  CloudWatch | X-Ray | Prometheus | Grafana               │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

## Technology Stack Summary

### Backend
- **Framework:** Laravel 11
- **Language:** PHP 8.3
- **Database:** PostgreSQL 16 + PostGIS 3.4
- **Cache/Queue:** Redis 7.2
- **WebSocket:** Laravel Reverb
- **Server:** Nginx 1.25 + PHP-FPM

### Frontend
- **Framework:** React 18
- **Router:** Inertia.js
- **Language:** TypeScript 5.3
- **Styling:** Tailwind CSS 3.4
- **Build:** Vite 5
- **State:** Zustand + React Query

### Mobile
- **Framework:** Flutter 3.24
- **Language:** Dart 3.5
- **State:** Riverpod
- **Database:** Drift (SQLite)
- **HTTP:** Dio + Retrofit

### DevOps
- **Containers:** Docker + Docker Compose
- **CI/CD:** GitHub Actions
- **Monitoring:** Prometheus + Grafana
- **Logging:** Loki + Promtail

---

**This architecture supports:**
- ✅ 20,000+ vehicles
- ✅ Real-time GPS tracking (30s intervals)
- ✅ Offline-first mobile app
- ✅ Scalable microservices
- ✅ High availability (99.5% uptime)
- ✅ Government compliance
