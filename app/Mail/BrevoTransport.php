<?php

namespace App\Mail;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class BrevoTransport extends AbstractTransport
{
   protected $api;

   public function __construct(string $apiKey)
   {
      parent::__construct();

      $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
      $this->api = new TransactionalEmailsApi(null, $config);
   }

   protected function doSend(SentMessage $message): void
   {
      $email = MessageConverter::toEmail($message->getOriginalMessage());

      $sendEmail = new SendSmtpEmail([
         'sender' => [
            'email' => $email->getFrom()[0]->getAddress(),
            'name' => $email->getFrom()[0]->getName() ?? ''
         ],
         'to' => array_map(fn($addr) => ['email' => $addr->getAddress()], $email->getTo()),
         'subject' => $email->getSubject(),
         'htmlContent' => $email->getHtmlBody()
      ]);

      $this->api->sendTransacEmail($sendEmail);
   }

   public function __toString(): string
   {
      return 'brevo';
   }
}
