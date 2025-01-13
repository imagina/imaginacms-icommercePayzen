<?php

namespace Modules\Icommercepayzen\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Isite\Jobs\ProcessSeeds;

class IcommercepayzenDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    ProcessSeeds::dispatch([
      "baseClass" => "\Modules\Icommercepayzen\Database\Seeders",
      "seeds" => ["IcommercepayzenModuleTableSeeder", "IcommercepayzenSeeder"]
    ]);

  }
}
