<?php

namespace Kodgrenade\NaijaFaker;

use Illuminate\Support\ServiceProvider;

class NaijaFakerServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   * 
   * @return void
   */
  public function register()
  {

  }

  /**
   * Bootstrap any application services.
   * 
   * @return void
   */
  public function boot()
  {
    $this->commands([
      \Kodegrenade\NaijaFaker\Commands\FakerGenerator::class
    ]);
  }
}
