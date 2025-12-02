<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearQueueTables extends Command
{
   protected $signature = 'queue:clear-tables';
   protected $description = 'Clear jobs and failed_jobs tables';

   public function handle()
   {
      try {
         DB::table('jobs')->truncate();
         DB::table('failed_jobs')->truncate();
         $this->info('Queue tables cleared successfully');
         return 0;
      } catch (\Exception $e) {
         $this->error('Failed to clear queue tables: ' . $e->getMessage());
         return 1;
      }
   }
}
