<?php

namespace Aleahy\ArtisanVueMake;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * In register, only bind things to the container
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([MakeVueComponent::class]);
        }
    }
}