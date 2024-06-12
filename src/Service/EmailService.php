<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;
    private string $mailFrom;

    public function __construct(MailerInterface $mailer, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->mailFrom = $mailFrom;
    }

    // send an email, should send an email to the user with the PDF attached
    public function sendEmail(string $to, string $subject, string $body, string $pdfPath): void
    {
        try {
            $email = (new Email())
                ->from($this->mailFrom)
                ->to($to)
                ->subject($subject)
                ->text($body)
                ->attachFromPath($pdfPath);
            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Log the exception message
            error_log($e->getMessage());
        }
    }
}