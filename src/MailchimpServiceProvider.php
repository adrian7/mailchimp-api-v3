<?php

namespace MailChimp;

use Illuminate\Support\ServiceProvider;

class MailChimpServiceProvider extends ServiceProvider {

    /**
     * Register paths to be published by the publish command.
     *
     * @return void
     */
    public function boot() {

        $this->publishes([
            __DIR__ . '/config/mailchimp.php' => config_path('mailchimp.php')
        ]);

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {

        $this->app->bind('MailChimp\API', function ($app, $args) {
            
            $config = $app['config']['mailchimp'];
            
            $apikey        = isset($args[0]) ? $args[0] : $config['apikey'];
            $clientOptions = isset($args[1]) ? $args[1] : [];
            
            return new API($apikey, $clientOptions);

        });

    }

}
