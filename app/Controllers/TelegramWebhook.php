<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\TelegramService;
use App\Models\PengajuanModel;

class TelegramWebhook extends BaseController
{
    public function index()
    {
        $telegram = new TelegramService();
        $update = $this->request->getJSON(true);

        if (!$update) {
            return $this->response->setStatusCode(400)->setBody('No update received');
        }

        $result = $telegram->handleWebhook($update);
        if (!$result) return $this->response->setStatusCode(200);

        $chatId = $result['chat_id'];
        $type = $result['type'];
        $payload = $result['payload'] ?? null;

        // Security check: Only respond to the admin ID in .env
        $adminId = env('TELEGRAM_ADMIN_CHAT_ID');
        if ($chatId != $adminId) {
            $telegram->sendMessage($chatId, "⚠️ Maaf, Anda tidak memiliki akses ke bot ini.");
            return $this->response->setStatusCode(200);
        }

        switch ($type) {
            case 'status':
                $this->handleStatusQuery($telegram, $chatId, $payload);
                break;
            case 'list':
                $this->handleListQuery($telegram, $chatId);
                break;
            default:
                $telegram->sendMessage($chatId, "❓ Perintah tidak dikenal. Gunakan /status [ID] atau /list.");
                break;
        }

        return $this->response->setStatusCode(200);
    }

    protected function handleStatusQuery($telegram, $chatId, $id)
    {
        if (!$id) {
            $telegram->sendMessage($chatId, "Format salah. Gunakan: /status [ID]\nContoh: /status 123");
            return;
        }

        $model = new PengajuanModel();
        $request = $model->find($id);

        if (!$request) {
            $telegram->sendMessage($chatId, "❌ Data pengajuan dengan ID #{$id} tidak ditemukan.");
            return;
        }

        $statusLabel = [
            'diajukan'  => 'Menunggu Disposisi',
            'disposisi' => 'Sudah Disposisi',
            'roren'     => 'Proses Roren',
            'selesai'   => 'Selesai'
        ];

        $statusEmoji = [
            'diajukan'  => '�',
            'disposisi' => '�',
            'roren'     => '🟣',
            'selesai'   => '🟢'
        ];

        $emoji = $statusEmoji[$request['status']] ?? 'ℹ️';
        $label = $statusLabel[$request['status']] ?? strtoupper($request['status']);
        
        $msg = "📌 <b>Detail Pengajuan #{$id}</b>\n\n";
        $msg .= "IKU: {$request['no_iku']} - {$request['nama_indikator']}\n";
        $msg .= "Fungsi: {$request['fungsi']}\n";
        $msg .= "Jenis: {$request['jenis_revisi']}\n";
        $msg .= "Status: {$emoji} <b>" . $label . "</b>\n";
        $msg .= "Terakhir Update: " . date('d/m/Y H:i', strtotime($request['created_at']));

        $telegram->sendMessage($chatId, $msg);
    }

    protected function handleListQuery($telegram, $chatId)
    {
        $model = new PengajuanModel();
        $requests = $model->where('status !=', 'selesai')
                          ->orderBy('created_at', 'DESC')
                          ->findAll(5);

        if (empty($requests)) {
            $telegram->sendMessage($chatId, "✅ Tidak ada antrean pengajuan yang perlu diproses.");
            return;
        }

        $statusLabel = [
            'diajukan'  => 'Menunggu Disposisi',
            'disposisi' => 'Sudah Disposisi',
            'roren'     => 'Proses Roren',
            'selesai'   => 'Selesai'
        ];

        $statusEmoji = [
            'diajukan'  => '🔵',
            'disposisi' => '🟡',
            'roren'     => '🟣',
            'selesai'   => '🟢'
        ];

        $msg = "📋 <b>5 Antrean Terakhir</b>\n";
        $msg .= "<i>Sesuai Dashboard Monitoring</i>\n\n";

        foreach ($requests as $r) {
            $waktu = date('d M Y | H:i', strtotime($r['created_at'])) . " WIB";
            $statusStr = $statusLabel[$r['status']] ?? strtoupper($r['status']);
            $emoji = $statusEmoji[$r['status']] ?? '⚪';
            
            $msg .= "🆔 <b>#{$r['id']}</b>\n";
            $msg .= "⏰ {$waktu}\n";
            $msg .= "👤 {$r['fungsi']}\n";
            $msg .= "📊 {$r['no_iku']} - " . substr($r['nama_indikator'], 0, 40) . "...\n";
            $msg .= "📝 {$r['jenis_revisi']}\n";
            $msg .= "{$emoji} <b>{$statusStr}</b>\n";
            $msg .= "----------------------------\n";
        }
        $msg .= "\nGunakan <code>/status [ID]</code> untuk detail lengkap.";

        $telegram->sendMessage($chatId, $msg);
    }
}
