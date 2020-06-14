<?php

namespace SoufieneSlimi\TemplateOperation;

use Illuminate\Support\ServiceProvider;

class TemplateOperationServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'template-operation');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'template-operation');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/backpack'),
        ], 'template-operation.views');

        // Publishing the translation files.
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/backpack'),
        ], 'template-operation.lang');
    }
}
