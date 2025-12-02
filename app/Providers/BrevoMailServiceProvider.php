<?php

namespace App\Providers;

use App\Mail\BrevoTransport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;

class BrevoMailServiceProvider extends ServiceProvider
{
   public function register(): void
   {
      //
   }

   public function boot(): void
   {
      $this->app->extend('mail.manager', function (MailManager $manager) {
         $manager->extend('brevo', function () {
            return new BrevoTransport(config('services.brevo.key'));
         });
         return $manager;
      });
   }
}
