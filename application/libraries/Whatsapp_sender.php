<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_sender {
    /** @var CI_Controller */
    protected $CI;
    protected $token;
    protected $endpoint = 'https://api.fonnte.com/send';

    public function __construct() {
        $this->CI = &get_instance();
        // Read token from env var for security (preferred)
        $this->token = getenv('FONNTE_TOKEN') ?: '';
        // Fallback: read from application/config/whatsapp.php if present
        if (empty($this->token)) {
            $path = APPPATH . 'config/whatsapp.php';
            if (@file_exists($path)) {
                // The file should set $config['fonnte_token'] or $config['whatsapp']['fonnte_token']
                $config = [];
                @include $path;
                if (isset($config['fonnte_token']) && !empty($config['fonnte_token'])) {
                    $this->token = $config['fonnte_token'];
                } elseif (isset($config['whatsapp']['fonnte_token']) && !empty($config['whatsapp']['fonnte_token'])) {
                    $this->token = $config['whatsapp']['fonnte_token'];
                }
            }
        }
        // Optionally you can set endpoint/token via env or config only to keep it simple
    }

    public function set_token($token) {
        $this->token = $token;
    }

    /** Normalize phone to 62xxxxxxxxxx */
    public function normalize_phone($phone) {
        $p = preg_replace('/\D+/', '', (string)$phone);
        if (strpos($p, '0') === 0) {
            $p = '62' . substr($p, 1);
        }
        if (strpos($p, '62') !== 0) {
            // If still not starting with 62, assume Indonesian number missing prefix
            $p = '62' . ltrim($p, '0');
        }
        return $p;
    }

    /**
     * Send WhatsApp message via Fonnte
     * @param string $to E164 without + (e.g., 62812xxxx)
     * @param string $message
     * @return array [success => bool, status => int, body => string]
     */
    public function send($to, $message) {
        if (empty($this->token)) {
            return ['success' => false, 'status' => 0, 'body' => 'Missing FONNTE_TOKEN'];
        }
        $to = $this->normalize_phone($to);
        $payload = [
            'target' => $to,
            'message' => $message,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->token,
                'Content-Type: application/x-www-form-urlencoded'
            ],
            CURLOPT_TIMEOUT => 15,
        ]);
    $response = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return ['success' => false, 'status' => 0, 'body' => $err ?: 'cURL error'];
        }
        // Try to parse JSON to determine success field
        $ok = $http >= 200 && $http < 300;
        $json = json_decode($response, true);
        if (is_array($json)) {
            if (isset($json['status'])) { $ok = $ok && (bool)$json['status']; }
            if (isset($json['success'])) { $ok = $ok && (bool)$json['success']; }
        }
        if (!$ok) {
            log_message('error', 'WhatsApp send failed: HTTP '.$http.'; body='.$response.'; err='.$err);
        }
        return ['success' => $ok, 'status' => $http, 'body' => $response];
    }
}
