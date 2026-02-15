<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\TelegramService;

class TestTelegram extends BaseController
{
    public function index()
    {
        $telegram = new TelegramService();
        $adminId = env('TELEGRAM_ADMIN_CHAT_ID');

        if (empty($adminId) || $adminId == '[CHAT_ID_ANDA]') {
            return "Gagal: Mohon isi TELEGRAM_ADMIN_CHAT_ID di file .env dengan ID dari @IDBot.";
        }

        $response = $telegram->sendMessage($adminId, "🔔 <b>Tes Koneksi!</b>\n\nSelamat! Sistem Monitoring BPOM sudah berhasil terhubung dengan Bot Telegram Anda.");

        if ($response && isset($response['ok']) && $response['ok']) {
            return "Berhasil! Silakan cek Telegram Anda.";
        } else {
            return "Gagal mengirim pesan. Error: " . json_encode($response);
        }
    }
}
