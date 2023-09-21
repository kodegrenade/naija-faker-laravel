<?php

/*
 * This file is part of the Laravel NaijaFaker package.
 *
 * (c) Temitope Ayotunde <brhamix@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Kodegrenade\NaijaFaker;

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
