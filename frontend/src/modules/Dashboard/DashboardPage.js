import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../../services/api';

const MODULES = [
  { to: '/flights', label: 'Flight Scheduling' },
  { to: '/aircraft', label: 'Aircraft Management' },
  { to: '/crew', label: 'Crew Scheduling' },
  { to: '/bookings', label: 'Passenger Booking' },
  { to: '/seats', label: 'Seat Selection' },
  { to: '/checkins', label: 'Check-in' },
  { to: '/boarding', label: 'Boarding' },
  { to: '/gates', label: 'Gate Management' },
  { to: '/baggage', label: 'Baggage Tracking' },
  { to: '/cargo', label: 'Cargo' },
  { to: '/maintenance', label: 'Maintenance' },
  { to: '/pricing', label: 'Ticket Pricing' },
  { to: '/payments', label: 'Payments' },
  { to: '/loyalty', label: 'Loyalty Program' },
  { to: '/airports', label: 'Airport Operations' },
  { to: '/tracking', label: 'Flight Tracking' },
  { to: '/weather', label: 'Weather Integration' },
  { to: '/analytics', label: 'Analytics' },
  { to: '/reports', label: 'Reporting' },
  { to: '/admin', label: 'Admin Portal' },
];

export default function DashboardPage() {
  const [health, setHealth] = useState(null);

  useEffect(() => {
    api.get('/health').then((r) => setHealth(r.data)).catch(() => setHealth({ status: 'unreachable' }));
  }, []);

  return (
    <div>
      <h1 className="arop-page-title">Airline Reservation &amp; Operations Platform</h1>
      <p className="arop-page-sub">
        Internal airline operations cockpit for scheduling, passenger processing, airport control, and analytics.
      </p>
      <p style={{ marginBottom: '1.25rem' }}>
        API health: <strong>{health?.status || 'checking…'}</strong>
      </p>
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(220px, 1fr))', gap: '0.85rem' }}>
        {MODULES.map((m) => (
          <Link key={m.to} to={m.to} style={{
            display: 'block',
            padding: '1rem',
            background: 'rgba(255,255,255,0.9)',
            border: '1px solid #d7e0ec',
            borderRadius: 10,
            color: '#0B3D91',
            fontWeight: 600,
          }}>
            {m.label}
          </Link>
        ))}
      </div>
    </div>
  );
}
