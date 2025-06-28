<?php

namespace App\Services\Mail;

//* libraries
use Illuminate\Support\Facades\Mail;

class SendMailService
{
    public function testMail($emailTest)
    {
        $emailsList = $emailTest->emails;
        $subject = $emailTest->subject;
        $message = $emailTest->message;

        if (empty($emailsList) || empty($subject) || empty($message)) {
            return [
                'message' => 'La lista de correos, el asunto o el mensaje no pueden estar vacíos',
                'status' => 'error'
            ];
        }

        $failedEmails = [];
        $successCount = 0;

        foreach ($emailsList as $email) {
            $emailData = [
                'email' => $email['email'],
                'asunto' => $subject
            ];

            $htmlContent = str_replace(
                ['{{name}}'],
                [$email['name']],
                $message
            );

            try {
                Mail::html($htmlContent, function ($message) use ($emailData) {
                    $message->to($emailData['email'])
                        ->subject($emailData['asunto']);
                });

                $successCount++;
            } catch (\Exception $e) {
                $failedEmails[] = [
                    'email' => $email['email'],
                    'error' => $e->getMessage(),
                ];
            }
        }

        if (!empty($failedEmails)) {
            return [
                'message' => 'Algunos correos no se pudieron enviar',
                'status' => 'partial_success',
                'success_count' => $successCount,
                'failed_emails' => $failedEmails
            ];
        }

        return [
            'message' => 'Todos los correos fueron enviados correctamente',
            'status' => 'success',
            'success_count' => $successCount
        ];
    }
}
