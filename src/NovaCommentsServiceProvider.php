<?php

declare(strict_types=1);

namespace Uzbek\NovaComments;

use Illuminate\Support\ServiceProvider;
use Uzbek\NovaComments\Nova\Comment;
use Laravel\Nova\Nova;

class NovaCommentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../resources/lang/' => resource_path('lang/vendor/nova-comments'),
        ]);

        $this->loadJSONTranslationsFrom(resource_path('lang/vendor/nova-comments'));

        $this->config();
        $this->migrations();
        $this->nova();

        $this->app->booted(function (): void {
            $this->translations();
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nova-comments.php', 'nova-comments');
    }

    /**
     * Bootstrap configurations files.
     */
    protected function config(): void
    {
        $this->publishes(
            [
                __DIR__.'/../config/nova-comments.php' => config_path('nova-comments.php'),
            ]
        );
    }

    /**
     * Bootstrap database migrations.
     */
    protected function migrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }

    /**
     * Bootstrap Nova resources and components.
     */
    protected function nova(): void
    {
        Nova::resources([Comment::class]);

        Nova::serving(
            function (): void {
                Nova::script('commentable', __DIR__.'/../dist/js/tool.js');
                Nova::style('commentable', __DIR__.'/../dist/css/tool.css');
            }
        );
    }

    public function translations(): void
    {
        $locale = $this->app->getLocale();

        Nova::translations(__DIR__.'/../resources/lang/'.$locale.'.json');
        Nova::translations(resource_path('lang/vendor/nova-comments/'.$locale.'.json'));
    }
}
