import React from 'react';
import { NavLink, Outlet } from 'react-router-dom';

export default function AppShell() {
  return (
    <div className="arop-shell">
      <aside className="arop-sidebar">
        <div className="arop-brand">Airline Reservation &amp; Operations Platform</div>
        <nav className="arop-nav">
          <NavLink to="/" end>Operations Dashboard</NavLink>
        <NavLink to="/flights">Flight Scheduling</NavLink>
        <NavLink to="/aircraft">Aircraft Management</NavLink>
        <NavLink to="/crew">Crew Scheduling</NavLink>
        <NavLink to="/bookings">Passenger Booking</NavLink>
        <NavLink to="/seats">Seat Selection</NavLink>
        <NavLink to="/checkins">Check-in</NavLink>
        <NavLink to="/boarding">Boarding</NavLink>
        <NavLink to="/gates">Gate Management</NavLink>
        <NavLink to="/baggage">Baggage Tracking</NavLink>
        <NavLink to="/cargo">Cargo</NavLink>
        <NavLink to="/maintenance">Maintenance</NavLink>
        <NavLink to="/pricing">Ticket Pricing</NavLink>
        <NavLink to="/payments">Payments</NavLink>
        <NavLink to="/loyalty">Loyalty Program</NavLink>
        <NavLink to="/airports">Airport Operations</NavLink>
        <NavLink to="/tracking">Flight Tracking</NavLink>
        <NavLink to="/weather">Weather Integration</NavLink>
        <NavLink to="/analytics">Analytics</NavLink>
        <NavLink to="/reports">Reporting</NavLink>
        <NavLink to="/admin">Admin Portal</NavLink>
        </nav>
      </aside>
      <main className="arop-main">
        <Outlet />
      </main>
    </div>
  );
}
