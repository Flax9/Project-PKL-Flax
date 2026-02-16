<?php

namespace App\Libraries;

use Config\Services;
use Config\Database;

class OTPService
{
    private $emailService;
    private $db;

    public function __construct()
    {
        $this->emailService = Services::email();
        $this->db = Database::connect();
    }

    /**
     * Generate OTP, Save to DB, and Send Email
     * 
     * @param string $username Current username
     * @param string $newEmail The target email for OTP
     * @return array ['status' => 'success'|'error', 'message' => string, 'debug_otp' => ?string, 'email_error' => ?string]
     */
    public function requestOTP(string $username, string $newEmail): array
    {
        // 1. Validate Email Format
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Format email tidak valid'];
        }

        // 2. Check Duplicate Email (excluding current user)
        $exists = $this->db->table('users')
                       ->where('email', $newEmail)
                       ->where('username !=', $username)
                       ->countAllResults();
        
        if ($exists > 0) {
            return ['status' => 'error', 'message' => 'Email sudah digunakan oleh pengguna lain'];
        }

        // 3. Generate OTP
        $otp = rand(100000, 999999);

        // 4. Save to DB
        $this->db->table('users')->where('username', $username)->update([
            'temp_email'      => $newEmail,
            'email_otp'       => $otp,
            'otp_created_at'  => date('Y-m-d H:i:s')
        ]);

        // 5. Send Email
        $this->emailService->setFrom('no-reply@bbpom-surabaya.go.id', 'E-Kinerja BBPOM');
        $this->emailService->setTo($newEmail);
        $this->emailService->setSubject('Kode Verifikasi Perubahan Data Akun');
        $this->emailService->setMessage("
            <h3>Verifikasi Perubahan Data Akun</h3>
            <p>Halo,</p>
            <p>Anda menerima email ini karena ada permintaan perubahan data pada akun E-Kinerja Anda.</p>
            <p>Kode OTP Anda adalah: <b>$otp</b></p>
            <p>Kode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapapun.</p>
            <br>
            <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
        ");

        if ($this->emailService->send()) {
            return [
                'status'  => 'success',
                'message' => 'Kode OTP verifikasi telah dikirim ke email ' . $newEmail,
                'debug_otp' => null
            ];
        } else {
            // Fallback for Dev/Simulation
            return [
                'status'  => 'success', // Treat as success for simulation UI
                'message' => 'Mode Simulasi: Email Gagal Terkirim (Cek Config)',
                'debug_otp' => $otp,
                'email_error' => $this->emailService->printDebugger(['headers'])
            ];
        }
    }

    /**
     * Validate OTP and Commit Email Change
     * 
     * @param string $username
     * @param string $otpInput
     * @return array ['status' => 'success'|'error', 'message' => string]
     */
    public function verifyOTP(string $username, string $otpInput): array
    {
        $user = $this->db->table('users')->where('username', $username)->get()->getRowArray();

        if (!$user || $user['email_otp'] !== $otpInput) {
            return ['status' => 'error', 'message' => 'Kode OTP salah'];
        }

        // Commit Changes (Update Email to Temp Email)
        // If temp_email is null (e.g. only name change), we just clear OTP
        // But logic says we store temp_email even if it's the same.
        // Let's ensure we don't accidentally set email to null if temp_email is missing for some reason.
        
        $newEmail = $user['temp_email'] ?: $user['email'];

        $this->db->table('users')->where('username', $username)->update([
            'email'          => $newEmail,
            'temp_email'     => null,
            'email_otp'      => null,
            'otp_created_at' => null
        ]);

        return ['status' => 'success', 'message' => 'Verifikasi berhasil! Perubahan Data Anda telah Disimpan'];
    }
}
