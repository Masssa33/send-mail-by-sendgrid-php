<?php
namespace App\Console\Commands;

use SendGrid

class SendEmailBySendgrid extends Command
{
    function sendEmail(
        array $tos,
        string $from,
        string $subject,
        string $body
    ): void {
        $apiKey = env('SENDGRID_API_KEY');
        $sg = new SendGrid($apiKey);

        $data = [
            "personalizations" => array_map(function ($to) {
                return [
                    "to" => [["email" => $to]]
                ];
            }, $tos),
            "from" => ["email" => $from],
            "subject" => $subject,
            "content" => [
                [
                    "type" => "text/plain",
                    "value" => $body
                ]
            ]
        ];

        try {
            $response = $sg->client->mail()->send()->post($data);
            echo $response->statusCode() . "\n";
            print_r($response->headers());
            echo $response->body() . "\n";
        } catch (Exception $e) {
            echo 'メール送信に失敗しました: ' . $e->getMessage() . "\n";
        }
    }
}
