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

    /**
     * Mengirim pesan ke Chat ID tertentu.
     * 
     * @param string|int $chatId
     * @param string $text
     * @param string $parseMode (HTML atau Markdown)
     * @return array|bool
     */
    public function sendMessage($chatId, $text, $parseMode = 'HTML')
    {
        if (empty($this->token)) {
            log_message('error', 'Telegram Service: Token tidak ditemukan di .env');
            return false;
        }

        $url = $this->apiUrl . "sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => $parseMode
        ];

        return $this->sendRequest($url, $data);
    }

    /**
     * Helper untuk mengirim request CURL ke Telegram
     */
    protected function sendRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Matikan jika ada masalah SSL di localhost

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', 'Telegram Service CURL Error: ' . $error);
            return false;
        }

        return json_decode($response, true);
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
