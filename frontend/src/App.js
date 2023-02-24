import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import AppShell from './layout/AppShell';
import DashboardPage from './modules/Dashboard/DashboardPage';
import LoginPage from './modules/Auth/LoginPage';
import FlightSchedulingPage from './modules/FlightScheduling/FlightSchedulingPage';
import AircraftManagementPage from './modules/AircraftManagement/AircraftManagementPage';
import CrewSchedulingPage from './modules/CrewScheduling/CrewSchedulingPage';
import PassengerBookingPage from './modules/PassengerBooking/PassengerBookingPage';
import SeatSelectionPage from './modules/SeatSelection/SeatSelectionPage';
import CheckInPage from './modules/CheckIn/CheckInPage';
import BoardingPage from './modules/Boarding/BoardingPage';
import GateManagementPage from './modules/GateManagement/GateManagementPage';
import BaggageTrackingPage from './modules/BaggageTracking/BaggageTrackingPage';
import CargoPage from './modules/Cargo/CargoPage';
import MaintenancePage from './modules/Maintenance/MaintenancePage';
import TicketPricingPage from './modules/TicketPricing/TicketPricingPage';
import PaymentsPage from './modules/Payments/PaymentsPage';
import LoyaltyProgramPage from './modules/LoyaltyProgram/LoyaltyProgramPage';
import AirportOperationsPage from './modules/AirportOperations/AirportOperationsPage';
import FlightTrackingPage from './modules/FlightTracking/FlightTrackingPage';
import WeatherIntegrationPage from './modules/WeatherIntegration/WeatherIntegrationPage';
import AnalyticsPage from './modules/Analytics/AnalyticsPage';
import ReportingPage from './modules/Reporting/ReportingPage';
import AdminPortalPage from './modules/AdminPortal/AdminPortalPage';

function App() {
  return (
    <Routes>
      <Route path="/login" element={<LoginPage />} />
      <Route path="/" element={<AppShell />}>
        <Route index element={<DashboardPage />} />
          <Route path="/flights" element={<FlightSchedulingPage />} />
          <Route path="/aircraft" element={<AircraftManagementPage />} />
          <Route path="/crew" element={<CrewSchedulingPage />} />
          <Route path="/bookings" element={<PassengerBookingPage />} />
          <Route path="/seats" element={<SeatSelectionPage />} />
          <Route path="/checkins" element={<CheckInPage />} />
          <Route path="/boarding" element={<BoardingPage />} />
          <Route path="/gates" element={<GateManagementPage />} />
          <Route path="/baggage" element={<BaggageTrackingPage />} />
          <Route path="/cargo" element={<CargoPage />} />
          <Route path="/maintenance" element={<MaintenancePage />} />
          <Route path="/pricing" element={<TicketPricingPage />} />
          <Route path="/payments" element={<PaymentsPage />} />
          <Route path="/loyalty" element={<LoyaltyProgramPage />} />
          <Route path="/airports" element={<AirportOperationsPage />} />
          <Route path="/tracking" element={<FlightTrackingPage />} />
          <Route path="/weather" element={<WeatherIntegrationPage />} />
          <Route path="/analytics" element={<AnalyticsPage />} />
          <Route path="/reports" element={<ReportingPage />} />
          <Route path="/admin" element={<AdminPortalPage />} />
      </Route>
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}

export default App;
