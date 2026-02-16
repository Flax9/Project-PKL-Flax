<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestEmail extends Controller
{
    public function index()
    {
        $email = \Config\Services::email();

        // Hardcode a recipient for testing (or use a query param if needed, but keep it simple)
        // Using the config's fromEmail as recipient to test loopback is usually safe
        $recipient = env('email.SMTPUser') ?: 'test@example.com'; 

        $email->setFrom(env('email.fromEmail', 'no-reply@test.com'), 'Test Sender');
        $email->setTo($recipient);
        $email->setSubject('Test Email Configuration - ' . date('Y-m-d H:i:s'));
        $email->setMessage('<p>This is a test email to verify SMTP configuration.</p>');

        echo "<h1>Email Configuration Diagnostics</h1>";
        echo "<hr>";
        echo "<h3>Environment Status</h3>";
        echo "CI_ENVIRONMENT: " . env('CI_ENVIRONMENT', 'production (default)') . "<br>";
        echo "Loaded .env file? " . (file_exists(ROOTPATH . '.env') ? '✅ Yes' : '❌ No (File not found)') . "<br>";
        echo "<hr>";
        echo "<h3>SMTP Variables</h3>";
        echo "SMTP Host: " . (env('email.SMTPHost') ? '✅ Set (' . env('email.SMTPHost') . ')' : '❌ Not Set') . "<br>";
        echo "SMTP User: " . (env('email.SMTPUser') ? '✅ Set' : '❌ Not Set') . "<br>";
        echo "SMTP Port: " . (env('email.SMTPPort') ? '✅ Set (' . env('email.SMTPPort') . ')' : '❌ Not Set') . "<br>";
        echo "<hr>";
        echo "<h3>Sending Attempt...</h3>";

        if ($email->send()) {
            echo "<h2 style='color:green'>✅ Email Sent Successfully!</h2>";
            echo "<p>Check inbox for: $recipient</p>";
        } else {
            echo "<h2 style='color:red'>❌ Email Failed to Send</h2>";
            echo "<p>Here is the debugger output:</p>";
            echo "<pre style='background:#eee; padding:10px; border:1px solid #ccc; overflow:auto;'>";
            print_r($email->printDebugger(['headers']));
            echo "</pre>";
        }
    }
}
