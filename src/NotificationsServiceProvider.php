<?php

namespace Developcreativo\Notifications;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Developcreativo\Notifications\Http\Middleware\Authorize;

class NotificationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'notifications');

        $this->publishes([
            __DIR__.'/../config/notifications.php' => config_path('notifications.php'),
        ]);


		$this->publishes([
			__DIR__.'/../resources/lang/' => resource_path('lang/vendor/notifications'),
		]);

		$this->registerTranslations();

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::provideToScript([
                'user_model_namespace' => config('notifications.user_model'),
                'play_sound' => config('notifications.play_sound'),
                'default_sound' => config('notifications.default_sound'),
                'toasted_enabled' => config('notifications.toasted_enabled'),
            ]);
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/notifications')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/notifications.php', 'notifications'
        );
    }

	protected function registerTranslations()
	{
		$locale = app()->getLocale();

		Nova::translations(__DIR__.'/../resources/lang/' . $locale . '.json');
		Nova::translations(resource_path('lang/vendor/notifications/' . $locale . '.json'));

		$this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'Notifications');
		$this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');
		$this->loadJSONTranslationsFrom(resource_path('lang/vendor/notifications'));
    }
}
