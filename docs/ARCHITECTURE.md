# Architecture

## Overview
Airline Reservation & Operations Platform is a modular monolith:
- React SPA for operations consoles
- Laravel API for domain services
- MySQL for transactional data
- Redis for cache, queues, and metrics

## Module Boundaries
Each operational module owns:
- Eloquent models and migrations
- API controllers and form requests
- Application services
- Domain workflow services
- Feature tests
- React console pages

## Cross-cutting Concerns
- Airline multi-tenancy via `airline_id` scope middleware
- RBAC via role + permission resolver
- Audit logging on create/update/delete
- Scheduled artisan jobs for ops automation
