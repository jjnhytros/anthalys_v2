<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CLAIR\Comprehension\ComprehensionService;
use App\Services\CLAIR\Learning\LearningService;
use App\Services\CLAIR\Adaptation\AdaptationService;
use App\Services\CLAIR\Integration\IntegrationService;
use App\Services\CLAIR\Resilience\ResilienceService;

class CLAIRServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('clair.comprehension', function ($app) {
            return new ComprehensionService();
        });

        $this->app->singleton('clair.learning', function ($app) {
            return new LearningService();
        });

        $this->app->singleton('clair.adaptation', function ($app) {
            return new AdaptationService();
        });

        $this->app->singleton('clair.integration', function ($app) {
            return new IntegrationService();
        });

        $this->app->singleton('clair.resilience', function ($app) {
            return new ResilienceService();
        });
    }

    public function boot()
    {
        //
    }
}
