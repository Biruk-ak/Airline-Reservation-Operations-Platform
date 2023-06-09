<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HealthController;

Route::get('/health', [HealthController::class, 'show']);

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum', 'airline.scope'])->group(function () {

Route::prefix('flights')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\FlightScheduling\FlightController::class, 'syncExternal']);
});

Route::prefix('aircraft')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\AircraftManagement\AircraftController::class, 'syncExternal']);
});

Route::prefix('crew')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\CrewScheduling\CrewMemberController::class, 'syncExternal']);
});

Route::prefix('bookings')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\PassengerBooking\BookingController::class, 'syncExternal']);
});

Route::prefix('seats')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\SeatSelection\SeatMapController::class, 'syncExternal']);
});

Route::prefix('checkins')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\CheckIn\CheckInController::class, 'syncExternal']);
});

Route::prefix('boarding')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\Boarding\BoardingPassController::class, 'syncExternal']);
});

Route::prefix('gates')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\GateManagement\GateController::class, 'syncExternal']);
});

Route::prefix('baggage')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\BaggageTracking\BaggageItemController::class, 'syncExternal']);
});

Route::prefix('cargo')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\Cargo\CargoShipmentController::class, 'syncExternal']);
});

Route::prefix('maintenance')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\Maintenance\MaintenanceWorkOrderController::class, 'syncExternal']);
});

Route::prefix('pricing')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\TicketPricing\FareRuleController::class, 'syncExternal']);
});

Route::prefix('payments')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\Payments\PaymentController::class, 'syncExternal']);
});

Route::prefix('loyalty')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\LoyaltyProgram\LoyaltyAccountController::class, 'syncExternal']);
});

Route::prefix('airports')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\AirportOperations\AirportController::class, 'syncExternal']);
});

Route::prefix('tracking')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\FlightTracking\FlightPositionController::class, 'syncExternal']);
});

Route::prefix('weather')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\WeatherIntegration\WeatherObservationController::class, 'syncExternal']);
});

Route::prefix('analytics')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\Analytics\AnalyticsMetricController::class, 'syncExternal']);
});

Route::prefix('reports')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\Reporting\ReportDefinitionController::class, 'syncExternal']);
});

Route::prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'store']);
    Route::get('/statistics', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'statistics']);
    Route::get('/export', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'export']);
    Route::post('/bulk', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'bulk']);
    Route::get('/{id}', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'destroy']);
    Route::post('/{id}/activate', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'activate']);
    Route::post('/{id}/deactivate', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'deactivate']);
    Route::post('/{id}/archive', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'archive']);
    Route::get('/{id}/timeline', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'timeline']);
    Route::post('/{id}/clone', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'cloneRecord']);
    Route::post('/{id}/sync-external', [\App\Http\Controllers\Api\AdminPortal\AdminAuditLogController::class, 'syncExternal']);
});

});
