<?php

namespace App\Database;

use Illuminate\Database\Connectors\PostgresConnector;
use PDO;

class NeonPostgresConnector extends PostgresConnector
{
   /**
    * Get the DSN string for a host / port configuration.
    *
    * @param  array  $config
    * @return string
    */
   protected function getDsn(array $config)
   {
      $dsn = parent::getDsn($config);

      // Add Neon endpoint parameter if connecting to Neon
      if (isset($config['host']) && strpos($config['host'], 'neon.tech') !== false) {
         // Extract endpoint ID from host (e.g., ep-cool-king-a19ddd9i)
         preg_match('/^(ep-[^-.]+-[^.]+)/', $config['host'], $matches);
         if (!empty($matches[1])) {
            $endpointId = $matches[1];
            $dsn .= ";options='endpoint={$endpointId}'";
         }
      }

      return $dsn;
   }
}
