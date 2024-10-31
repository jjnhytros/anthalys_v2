<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SimulateGovernmentPolicies;
use App\Http\Controllers\School\LessonController;
use App\Http\Controllers\City\{
    ChatController,
    CitizenController,
    CityController,
    DashboardController,
    DistrictController,
    DroneController,
    EconomyController,
    EmailController,
    EmploymentCenterController,
    GovernmentController,
    InfrastructureController,
    LocalMarketController,
    MaterialAnalysisController,
    MigrationController,
    NotificationController,
    PolicyController,
    ProductionReportController,
    RewardController,
    RobotController,
    SubsidyController,
    TimeController,
};
use App\Http\Controllers\Market\{
    MarketProductController,
    OnlineOrderController,
    ProductReviewController,
    StallController,
    SupplyController,
};
use App\Http\Controllers\Agricolture\{
    AgriculturalController,
    AnimalController,
    AquacultureController,
    CropController,
    FarmController,
    FarmDashboardController,
    GreenhouseController,
    SensorController,
};
use App\Http\Controllers\Recycling\BonusController;
use App\Http\Controllers\Resource\{
    ResourceController,
    ResourceTransferController,
};
use App\Http\Controllers\MegaWarehouse\{
    WarehouseController,
    WarehouseTransactionController
};

use App\Http\Controllers\Production\AlcoholicController;

Route::get('/time/get', [TimeController::class, 'calculateElapsedTime']);


// Pagina principale
Route::get('/', [CityController::class, 'index'])->name('home');

Route::get('/dashboard/farm', [FarmDashboardController::class, 'index'])->name('dashboard.farm');
Route::get('/dashboard/farm/stats', [FarmDashboardController::class, 'getStats'])->name('dashboard.farm.stats');

// Rotte con prefix cities
Route::prefix('cities')->middleware(['auth'])->group(function () {
    Route::resource('/', CityController::class);
    Route::post('/{city}/increase-production', [CityController::class, 'increaseResourceProduction'])->name('city.increaseProduction');
    Route::resource('{city}/districts', DistrictController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/map', [DashboardController::class, 'map'])->name('map');
});

// Rotte per il governo con prefix government
Route::prefix('government')->middleware(['auth'])->group(function () {
    Route::get('/balance', [CityController::class, 'getGovernmentBalance'])->name('api.government.balance');
    Route::get('/policies', [GovernmentController::class, 'showPolicies'])->name('government.policies');
    Route::post('/policies', [GovernmentController::class, 'updatePolicies'])->name('government.policies.update');
    Route::get('/reports', [GovernmentController::class, 'showReports'])->name('government.reports');
    Route::get('/reports/{year}', [GovernmentController::class, 'viewReport'])->name('government.report.view');
});

// Rotte per risorse
Route::prefix('resources')->middleware(['auth'])->group(function () {
    Route::get('/analysis', [ResourceController::class, 'index'])->name('resources.analysis');
    Route::get('/transfer', [ResourceController::class, 'transferView'])->name('resource.transfer');
    Route::post('/transfer', [ResourceTransferController::class, 'transfer'])->name('resource.transfer');
    Route::get('/real-time-monitoring', [ResourceController::class, 'realTimeMonitoring'])->name('resources.real_time_monitoring');
    Route::get('/send-alerts', [ResourceController::class, 'sendResourceAlerts'])->name('resources.send_alerts');
    Route::get('/update-history', [ResourceController::class, 'updateResourceHistory'])->name('resources.update_history');
});

// Infrastrutture
Route::prefix('infrastructures')->middleware(['auth'])->group(function () {
    Route::post('/{infrastructure}/maintain', [InfrastructureController::class, 'maintain'])->name('infrastructures.maintain');
    Route::get('/{infrastructure}/history', [InfrastructureController::class, 'history'])->name('infrastructures.history');
    Route::post('/apply-deterioration', [InfrastructureController::class, 'applyDeterioration'])->name('infrastructures.apply-deterioration');
    Route::get('/{infrastructure}/monitor-efficiency', [InfrastructureController::class, 'monitorInfrastructureEfficiency']);
});

// Rotte per i cittadini e i bonus
Route::prefix('citizens')->middleware(['auth'])->group(function () {
    Route::get('/{citizen}/bonuses', [BonusController::class, 'showBonuses'])->name('citizens.bonuses');
    Route::post('/{citizen}/claim-voucher', [BonusController::class, 'claimVoucher'])->name('citizens.claimVoucher');
    Route::get('/{citizen}/recycling-progress', [CitizenController::class, 'showRecyclingProgress'])->name('citizens.recyclingProgress');
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');
});

// Email
Route::prefix('emails')->middleware(['auth'])->group(function () {
    Route::get('/', [EmailController::class, 'inbox'])->name('email.inbox');
    Route::get('/compose', [EmailController::class, 'compose'])->name('email.compose');
    Route::post('/send', [EmailController::class, 'sendEmail'])->name('email.send');
    Route::get('/search', [EmailController::class, 'search'])->name('email.search');
    Route::get('/{id}', [EmailController::class, 'show'])->name('emails.show');
    Route::get('/sent', [EmailController::class, 'sent'])->name('emails.sent');
    Route::get('/archived', [EmailController::class, 'archived'])->name('emails.archived');
});

// Chat
Route::prefix('chat')->middleware(['auth'])->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});

// Notifiche
Route::prefix('notifications')->middleware(['auth'])->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/archive/{id}', [NotificationController::class, 'archive'])->name('notifications.archive');
    Route::get('/unread', [NotificationController::class, 'loadUnreadNotifications'])->name('notifications.unread');
});

// Politiche
Route::prefix('policies')->middleware(['auth'])->group(function () {
    Route::resource('/', PolicyController::class);
    Route::get('/simulate/government-policies', function () {
        Artisan::call(SimulateGovernmentPolicies::class);
        return response()->json(['message' => 'Simulazione delle politiche governative completata con successo.']);
    });
});

// Agricoltura
Route::prefix('agriculture')->middleware(['auth'])->group(function () {
    Route::get('/production', [AgriculturalController::class, 'index'])->name('agricultural.production');
});

// Produzione di Alcolici
Route::resource('alcoholics', AlcoholicController::class)->middleware('auth');

// Rotte aggiuntive per bonus, sussidi e multe
Route::post('/bonus/recycling/{citizen}/{amountRecycled}', [BonusController::class, 'rewardRecycling']);
Route::post('/bonus/improvement/{citizen}/{activityType}', [BonusController::class, 'rewardCityImprovement']);
Route::post('/bonus/sustainability/{citizen}', [BonusController::class, 'rewardSustainableActivities']);
Route::post('/impose-fines', [CitizenController::class, 'imposeFinesForNonCompliance']);
Route::post('/distribute-subsidies', [SubsidyController::class, 'distributeSubsidies']);

// Risorse per le fattorie, coltivazioni, allevamento
Route::resource('farms', FarmController::class);
Route::resource('crops', CropController::class);
Route::resource('animals', AnimalController::class);
Route::resource('aquaculture', AquacultureController::class);
Route::resource('greenhouses', GreenhouseController::class);
Route::resource('sensors', SensorController::class);
Route::resource('drones', DroneController::class);
Route::resource('robots', RobotController::class);
Route::resource('reports', ProductionReportController::class);

// Market
Route::resource('markets', LocalMarketController::class);
Route::get('/markets/{market}/stalls', [StallController::class, 'index'])->name('markets.stalls.index');
Route::resource('products', MarketProductController::class);
Route::post('/reviews', [ProductReviewController::class, 'store'])->name('reviews.store');
Route::get('/supplies/create', [SupplyController::class, 'create'])->name('supplies.create');
Route::post('/supplies/store', [SupplyController::class, 'store'])->name('supplies.store');
Route::get('/stalls/{stall}/inventory', [StallController::class, 'inventory'])->name('stalls.inventory');
Route::get('/stalls/{stall}/supplies/create', [SupplyController::class, 'create'])->name('supplies.create');
Route::post('/stalls/{stall}/supplies/store', [SupplyController::class, 'store'])->name('supplies.store');
Route::get('/markets/pricing', [LocalMarketController::class, 'pricing'])->name('localmarket.pricing');
Route::resource('orders', OnlineOrderController::class)->only(['store', 'index']);
Route::post('/orders/{id}/confirm', [OnlineOrderController::class, 'confirm'])->name('orders.confirm');
Route::post('/orders/{id}/cancel', [OnlineOrderController::class, 'cancel'])->name('orders.cancel');
Route::get('/orders/history', [OnlineOrderController::class, 'history'])->name('orders.history');
Route::get('/market/check-stock', [LocalMarketController::class, 'checkStock'])->name('localmarket.checkStock');

// MegaWarehouse
Route::get('/warehouse', [WarehouseController::class, 'index'])->name('mega.warehouse');
Route::post('/warehouse/order/{id}/process', [WarehouseController::class, 'processOrder'])->name('mega.warehouse.process');
Route::get('/warehouse/reports', [WarehouseController::class, 'dashboard'])->name('warehouse.reports');
Route::prefix('warehouse-transactions')->middleware(['auth'])->group(function () {
    Route::get('/', [WarehouseTransactionController::class, 'index'])->name('warehouse.transactions.index');
    Route::post('/', [WarehouseTransactionController::class, 'store'])->name('warehouse.transactions.store');
    Route::get('/{id}', [WarehouseTransactionController::class, 'show'])->name('warehouse.transactions.show');
});
Route::get('/warehouse/donations', [WarehouseController::class, 'donationDashboard'])->name('warehouse.donations');

Route::get('/warehouse/check-stock', [WarehouseController::class, 'checkStock'])->name('warehouse.checkStock');

Route::prefix('employment')->group(function () {
    Route::get('/', [EmploymentCenterController::class, 'index'])->name('employment.index');
    Route::get('/{id}', [EmploymentCenterController::class, 'show'])->name('employment.show');
    Route::post('/apply/{occupationId}', [EmploymentCenterController::class, 'apply'])->name('employment.apply');
    Route::get('/employment/search', [EmploymentCenterController::class, 'search'])->name('employment.search');
});

Route::get('/economy/{city}', [EconomyController::class, 'showEconomy']);
Route::get('/material/{material}', [MaterialAnalysisController::class, 'showMaterialAnalysis']);
