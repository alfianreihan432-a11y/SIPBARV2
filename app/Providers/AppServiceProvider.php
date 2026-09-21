<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();

        Gate::define('manage-site-settings', function (User $user): bool {
            return $user->hasRole('superadmin');
        });

        View::composer('*', function ($view) {
            static $brand = null;

            if ($brand === null) {
                $legacyLogo = SiteSetting::get('site_logo', '/logossmkn1.png');

                $brand = [
                    'siteLogo'         => $legacyLogo,
                    'siteName'         => SiteSetting::get('site_name', 'SIPBAR'),
                    'siteSubtitle'     => SiteSetting::get('site_subtitle', 'SMKN 1 BANGSRI'),
                    // 3 logo terpisah — fallback ke legacy logo jika belum diset
                    'siteLogoLanding'  => SiteSetting::get('site_logo_landing') ?: $legacyLogo,
                    'siteLogoLogin'    => SiteSetting::get('site_logo_login') ?: $legacyLogo,
                    'siteLogoDashboard'=> SiteSetting::get('site_logo_dashboard') ?: $legacyLogo,
                ];
            }

            $view->with($brand);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
