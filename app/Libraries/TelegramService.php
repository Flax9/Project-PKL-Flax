<?php

namespace App\Libraries;

/**
 * TelegramService
 * 
 * Library untuk mengelola integrasi dengan Telegram Bot API.
 */
class TelegramService
{
    protected $token;
    protected $apiUrl;

    public function __construct()
    {
        $this->token = env('TELEGRAM_BOT_TOKEN');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}/";
    }

    public function sendMessage($chatId, $text, $parseMode = 'HTML')
    {
        $topicArn = env('AWS_SNS_TOPIC_ARN');
        if (empty($topicArn)) {
            log_message('error', 'Telegram Service: AWS_SNS_TOPIC_ARN tidak ditemukan di .env');
            return false;
        }

        try {
            $snsClient = new \Aws\Sns\SnsClient([
                'version' => 'latest',
                'region'  => env('AWS_REGION', 'us-east-1')
            ]);

            $payload = [
                'chat_id' => $chatId,
                'message' => $text,
                'parse_mode' => $parseMode
            ];

            $result = $snsClient->publish([
                'Message'  => json_encode($payload),
                'TopicArn' => $topicArn,
            ]);

            return $result ? ['ok' => true] : false;
        } catch (\Aws\Exception\AwsException $e) {
            log_message('error', 'SNS Publish Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Parse Update dari Webhook
     */
    public function handleWebhook($update)
    {
        if (!isset($update['message'])) return null;

        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        // Logika parsing perintah sederhana
        if (strpos($text, '/status') === 0) {
            $parts = explode(' ', $text);
            $id = $parts[1] ?? null;
            return [
                'type' => 'status',
                'chat_id' => $chatId,
                'payload' => $id
            ];
        }

        if (strpos($text, '/list') === 0) {
            return [
                'type' => 'list',
                'chat_id' => $chatId
            ];
        }

        return [
            'type' => 'unknown',
            'chat_id' => $chatId,
            'text' => $text
        ];
    }
}
