<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $expiringCount = \App\Models\Membership::where('status', 'Activa')
                ->whereDate('end_date', '<=', \Carbon\Carbon::today()->addDays(3))
                ->count();
                
            $expiredCount = \App\Models\Membership::where('status', 'Pendiente')
                ->count();
                
            // Obtener dinámicamente el plan de Pase Diario vigente del catálogo
            $dailyPlan = \App\Models\Plan::where('validity_days', 1)
                ->orWhere('name', 'like', '%diario%')
                ->orWhere('name', 'like', '%express%')
                ->orWhere('name', 'like', '%pase%')
                ->orderBy('id', 'desc')
                ->first();
                
            $dailyPassPrice = $dailyPlan ? (float)$dailyPlan->price : 3.00;
                
            $view->with('notificationsCount', $expiringCount + $expiredCount);
            $view->with('expiringCount', $expiringCount);
            $view->with('expiredCount', $expiredCount);
            $view->with('globalDailyPassPrice', $dailyPassPrice);
            $view->with('globalDailyPlan', $dailyPlan);
        });
    }
}
