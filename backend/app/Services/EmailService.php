<?php

namespace App\Services;

use SendGrid;
use SendGrid\Mail\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class EmailService
{
    protected $sendgrid;

    public function __construct()
    {
        $this->sendgrid = new SendGrid(config('sendgrid.api_key'));
    }

    /**
     * Send email using SendGrid template
     */
    public function sendTemplatedEmail(
        string $to,
        string $templateId,
        array $templateData = [],
        ?string $toName = null,
        ?string $subject = null
    ): bool {
        try {
            $email = new Mail();
            
            // Set from
            $email->setFrom(
                config('sendgrid.from.email'),
                config('sendgrid.from.name')
            );

            // Set to
            $email->addTo($to, $toName);

            // Set template
            $email->setTemplateId($templateId);

            // Set dynamic template data
            if (!empty($templateData)) {
                $email->addDynamicTemplateData($templateData);
            }

            // Set subject if provided (for non-template emails)
            if ($subject) {
                $email->setSubject($subject);
            }

            // Enable sandbox mode if configured
            if (config('sendgrid.sandbox_mode')) {
                $email->enableSandBoxMode();
            }

            $response = $this->sendgrid->send($email);

            Log::info('Email sent successfully', [
                'to' => $to,
                'template_id' => $templateId,
                'status_code' => $response->statusCode()
            ]);

            return $response->statusCode() >= 200 && $response->statusCode() < 300;

        } catch (\Exception $e) {
            Log::error('Failed to send email', [
                'to' => $to,
                'template_id' => $templateId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send simple text/HTML email
     */
    public function sendSimpleEmail(
        string $to,
        string $subject,
        string $content,
        ?string $toName = null,
        bool $isHtml = true
    ): bool {
        try {
            $email = new Mail();
            
            $email->setFrom(
                config('sendgrid.from.email'),
                config('sendgrid.from.name')
            );

            $email->addTo($to, $toName);
            $email->setSubject($subject);

            if ($isHtml) {
                $email->addContent("text/html", $content);
            } else {
                $email->addContent("text/plain", $content);
            }

            if (config('sendgrid.sandbox_mode')) {
                $email->enableSandBoxMode();
            }

            $response = $this->sendgrid->send($email);

            Log::info('Simple email sent successfully', [
                'to' => $to,
                'subject' => $subject,
                'status_code' => $response->statusCode()
            ]);

            return $response->statusCode() >= 200 && $response->statusCode() < 300;

        } catch (\Exception $e) {
            Log::error('Failed to send simple email', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send bulk emails
     */
    public function sendBulkEmail(
        array $recipients,
        string $templateId,
        array $templateData = []
    ): bool {
        try {
            $email = new Mail();
            
            $email->setFrom(
                config('sendgrid.from.email'),
                config('sendgrid.from.name')
            );

            // Add multiple recipients
            foreach ($recipients as $recipient) {
                $email->addTo(
                    $recipient['email'],
                    $recipient['name'] ?? null
                );
            }

            $email->setTemplateId($templateId);

            if (!empty($templateData)) {
                $email->addDynamicTemplateData($templateData);
            }

            if (config('sendgrid.sandbox_mode')) {
                $email->enableSandBoxMode();
            }

            $response = $this->sendgrid->send($email);

            Log::info('Bulk email sent successfully', [
                'recipient_count' => count($recipients),
                'template_id' => $templateId,
                'status_code' => $response->statusCode()
            ]);

            return $response->statusCode() >= 200 && $response->statusCode() < 300;

        } catch (\Exception $e) {
            Log::error('Failed to send bulk email', [
                'recipient_count' => count($recipients),
                'template_id' => $templateId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}