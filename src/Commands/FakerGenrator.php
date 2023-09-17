<?php

namespace Kodgrenade\NaijaFaker\Commands;

use Illuminate\Console\Command;
use Kodgrenade\NaijaFaker\NaijaFaker;

class FakerGenerator extends Command
{
  /**
   * The name and signature of the console command.
   * 
   * @var string
   */
  protected $signature = 'faker:generator';

  /**
   * The console command description.
   * 
   * @var string
   */
  protected $description = 'Generate faker information.';

  /**
   * Create a new command instance.
   * 
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command
   * 
   * @return int
   */
  public function handle()
  {
    try {
      $generate = NaijaFaker::person();
      $this->info($generate);
    } catch (\Exception $e) {
      $this->error("Error:: {$e->getMessage()}");
    }

    return 0;
  }
}
