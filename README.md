# Airline Reservation & Operations Platform

Enterprise airline reservation and operations software for internal airline use — covering flight scheduling, passenger processing, airport control, commercial services, and analytics in one modular platform.

Built as a **React** operations console backed by a **PHP Laravel** API, with airline multi-tenancy, role-based access, audit logging, and scheduled operational jobs.

---

## Table of contents

- [Overview](#overview)
- [Features](#features)
- [Tech stack](#tech-stack)
- [Repository structure](#repository-structure)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API examples](#api-examples)
- [Testing](#testing)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

Airlines need more than a booking website. This platform models the **internal ops stack** used by airline staff:

| Layer | Purpose |
| --- | --- |
| **Commercial** | Booking, seats, pricing, payments, loyalty |
| **Passenger day-of-travel** | Check-in, boarding, baggage |
| **Airport & network** | Gates, airport ops, tracking, weather |
| **Fleet & people** | Aircraft, crew, maintenance, cargo |
| **Insight & control** | Analytics, reporting, admin portal |

Each module follows the same API pattern (search, CRUD, activate/deactivate/archive, bulk actions, statistics, export, timeline, clone) so new operational workflows stay consistent.

---

## Features

### Core operations (20 modules)

1. **Flight Scheduling** — schedules, soft conflict warnings, station/slot oriented workflows  
2. **Aircraft Management** — fleet registry and utilization enrichment  
3. **Crew Scheduling** — rostering records and duty-risk indicators  
4. **Passenger Booking** — PNR-style bookings with external-ref search  
5. **Seat Selection** — seat maps and exit-row SSR guards  
6. **Check-in** — document expiry validation helpers  
7. **Boarding** — boarding passes and closeout checklist support  
8. **Gate Management** — gate records with conflict scoring  
9. **Baggage Tracking** — journey tracking and mishandled filters  
10. **Cargo** — shipments with dangerous-goods warnings  
11. **Maintenance** — work orders and overdue spotlighting  
12. **Ticket Pricing** — fare rules and effective-window helpers  
13. **Payments** — payment records and refund eligibility checks  
14. **Loyalty Program** — accounts with tier progress snapshots  
15. **Airport Operations** — station ops and curfew compliance flags  
16. **Flight Tracking** — positions with stale-update detection  
17. **Weather Integration** — observations and severity alert levels  
18. **Analytics** — KPI rollups including OTP-oriented fields  
19. **Reporting** — report definitions with next-run stamps  
20. **Admin Portal** — admin audit logs with severity classification  

### Platform capabilities

- Airline-scoped multi-tenancy (`airline_id` middleware)
- Sanctum token auth + role/permission resolver
- Entity audit logging on create/update/delete
- Redis-backed cache, sessions, and queues
- Scheduled artisan jobs (flight status sync, weather refresh, analytics rollup, and more)
- React ops console with Redux Toolkit and module pages

---

## Tech stack

| Area | Technology |
| --- | --- |
| Frontend | React 18, Redux Toolkit, React Router 6, Axios, Chart.js, Formik/Yup |
| Backend | PHP 8.1+, Laravel 9, Laravel Sanctum |
| Data | MySQL, Redis (Predis) |
| Exports | DomPDF, Maatwebsite Excel |
| Testing | PHPUnit feature tests |

---

## Repository structure

```text
.
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Console/Commands # Operational schedulers
│   │   ├── Http/            # Controllers, middleware, form requests
│   │   ├── Models/          # Domain models per module
│   │   └── Services/        # Application + domain services
│   ├── database/            # Migrations & seeders
│   ├── routes/api.php       # REST API routes
│   └── tests/Feature/       # Module API tests
├── frontend/                # React operations console
│   └── src/
│       ├── modules/         # One console page per ops module
│       ├── store/           # Redux slices
│       ├── services/api.js  # Axios client
│       └── components/      # Shared UI
├── docs/                    # Architecture, modules, API notes
├── CONTRIBUTING.md
└── README.md
```

---

## Prerequisites

- **PHP** `>= 8.1` with extensions commonly required by Laravel (`openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- **Composer** 2.x
- **Node.js** `>= 16` and npm
- **MySQL** 8.x (or compatible)
- **Redis** (recommended for cache/queues/sessions)

---

## Installation

### 1. Clone the repository

```bash
git clone git@github.com:Biruk-ak/Airline-Reservation-Operations-Platform.git
cd Airline-Reservation-Operations-Platform
```

### 2. Backend setup

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Create the database:

```sql
CREATE DATABASE airline_ops CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update `.env` with your MySQL and Redis credentials, then:

```bash
php artisan migrate --seed
php artisan serve
```

API default: `http://localhost:8000`

### 3. Frontend setup

In a second terminal:

```bash
cd frontend
npm install
```

Optional — point the SPA at your API:

```bash
# Linux / macOS
export REACT_APP_API_URL=http://localhost:8000/api

# Windows (PowerShell)
$env:REACT_APP_API_URL="http://localhost:8000/api"
```

Start the console:

```bash
npm start
```

UI default: `http://localhost:3000`

---

## Configuration

Key variables from `backend/.env.example`:

| Variable | Description |
| --- | --- |
| `APP_URL` | Backend base URL |
| `DB_*` | MySQL connection |
| `REDIS_*` | Cache / queue / session Redis |
| `WEATHER_API_KEY` | Weather integration provider key |
| `PAYMENT_GATEWAY_KEY` / `PAYMENT_GATEWAY_SECRET` | Payments gateway credentials |
| `JWT_SECRET` | Reserved secret slot for token-related config |

Never commit real `.env` files or production secrets. See [`.gitignore`](.gitignore).

---

## Usage

### Demo login (after seeding)

| Field | Value |
| --- | --- |
| Email | `birukaklilu0110@gmail.com` |
| Password | `password` |
| Role | `super_admin` |
| Station | `ADD` (Demo Air) |

Open the React console, sign in, and use the sidebar to navigate modules (Flights, Aircraft, Crew, Bookings, etc.).

### Typical operator flows

1. **Schedule a flight** — Flight Scheduling → New → set station/region/priority → Activate  
2. **Assign fleet context** — Aircraft Management → create/update aircraft → review utilization fields  
3. **Process passengers** — Bookings → Seats → Check-in → Boarding  
4. **Station ops** — Gates, Baggage, Airport Operations, Weather, Tracking  
5. **Commercial** — Pricing, Payments, Loyalty  
6. **Oversight** — Analytics, Reporting, Admin Portal  

### Useful artisan jobs

```bash
cd backend

php artisan flights:sync-status
php artisan crew:check-duty-limits
php artisan weather:refresh-airports
php artisan analytics:rollup-daily
php artisan gates:auto-assign
php artisan tracking:poll-positions
```

Run the scheduler locally:

```bash
php artisan schedule:work
```

---

## API examples

Base URL: `http://localhost:8000/api`

### Health check

```bash
curl -s http://localhost:8000/api/health | jq
```

### Login

```bash
curl -s -X POST http://localhost:8000/api/auth/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "birukaklilu0110@gmail.com",
    "password": "password"
  }' | jq
```

Save the returned `token`, then:

### List flights

```bash
TOKEN="<your-token>"

curl -s "http://localhost:8000/api/flights?station_code=ADD&per_page=10" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" | jq
```

### Create a flight schedule record

```bash
curl -s -X POST http://localhost:8000/api/flights \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "ADD-DXB Morning Bank",
    "station_code": "ADD",
    "region": "AFR",
    "priority": 8,
    "description": "Demo schedule record"
  }' | jq
```

### Module statistics

```bash
curl -s http://localhost:8000/api/flights/statistics \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" | jq
```

### Shared resource pattern

Every module resource (`/flights`, `/aircraft`, `/crew`, `/bookings`, `/seats`, `/checkins`, `/boarding`, `/gates`, `/baggage`, `/cargo`, `/maintenance`, `/pricing`, `/payments`, `/loyalty`, `/airports`, `/tracking`, `/weather`, `/analytics`, `/reports`, `/admin`) supports:

| Method | Path | Action |
| --- | --- | --- |
| `GET` | `/` | Search / list |
| `POST` | `/` | Create |
| `GET` | `/{id}` | Show |
| `PUT` | `/{id}` | Update |
| `DELETE` | `/{id}` | Soft-delete path |
| `POST` | `/{id}/activate` | Activate |
| `POST` | `/{id}/deactivate` | Deactivate |
| `POST` | `/{id}/archive` | Archive |
| `POST` | `/bulk` | Bulk lifecycle actions |
| `GET` | `/statistics` | Module stats |
| `GET` | `/export` | Export rows |
| `GET` | `/{id}/timeline` | Audit / version timeline |
| `POST` | `/{id}/clone` | Clone record |

More detail: [`docs/API.md`](docs/API.md)

---

## Testing

```bash
cd backend
./vendor/bin/phpunit
# or, when PHPUnit is configured via artisan:
php artisan test
```

Feature tests live under `backend/tests/Feature/<Module>/`.

Frontend:

```bash
cd frontend
npm test
```

---

## Documentation

| Doc | Description |
| --- | --- |
| [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) | Modular monolith architecture |
| [`docs/MODULES.md`](docs/MODULES.md) | Module responsibility map |
| [`docs/API.md`](docs/API.md) | REST API conventions |
| [`CONTRIBUTING.md`](CONTRIBUTING.md) | How to contribute |

---

## Contributing

Contributions are welcome via issues and pull requests. Please read **[CONTRIBUTING.md](CONTRIBUTING.md)** for clone/run steps, branch naming, commit style, and PR expectations.

---

## License

Proprietary — All rights reserved unless otherwise stated by the repository owner.

---

## Author

**Biruk-ak** — [GitHub](https://github.com/Biruk-ak)

Repository: [Airline-Reservation-Operations-Platform](https://github.com/Biruk-ak/Airline-Reservation-Operations-Platform)
