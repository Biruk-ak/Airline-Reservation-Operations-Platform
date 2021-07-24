# Airline Reservation & Operations Platform

Enterprise-grade airline reservation and operations management system used for flight scheduling, passenger booking, crew management, airport operations, and analytics.

## Stack
- **Frontend**: React 18, Redux Toolkit, React Router, Axios, Chart.js
- **Backend**: PHP 8.1, Laravel 9, MySQL, Redis, Queue workers
- **Auth**: JWT / Sanctum role-based access control

## Modules
1. Flight Scheduling
2. Aircraft Management
3. Crew Scheduling
4. Passenger Booking
5. Seat Selection
6. Check-in
7. Boarding
8. Gate Management
9. Baggage Tracking
10. Cargo
11. Maintenance
12. Ticket Pricing
13. Payments
14. Loyalty Program
15. Airport Operations
16. Flight Tracking
17. Weather Integration
18. Analytics
19. Reporting
20. Admin Portal

## Getting Started

### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend
```bash
cd frontend
npm install
npm start
```

## License
Proprietary — All rights reserved.
