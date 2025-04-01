<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $accessToken;
    protected $phoneId;

    public function __construct()
    {
        $this->apiUrl = 'https://graph.facebook.com/v22.0'; // Meta API URL
        $this->accessToken = config('services.whatsapp.token'); // Bearer Token
        $this->phoneId = config('services.whatsapp.phone_id'); // Your WhatsApp Business phone ID
    }

    /**
     * Send WhatsApp Message (Text or Template)
     */
    public function sendMessage($to, $message = null, $templateName = null, $templateParams = [])
    {
        try {
            // Prepare Payload based on whether it's a template or text
            $payload = $templateName
                ? $this->getTemplatePayload($to, $templateName, $templateParams)
                : $this->getTextPayload($to, $message);

            // Send Request to WhatsApp API
            $response = Http::withToken($this->accessToken)->post("{$this->apiUrl}/{$this->phoneId}/messages", $payload);

            $result = $response->json();
            if ($response->failed()) {
                throw new \Exception($result['error']['message'] ?? 'Unknown WhatsApp API Error');
            }

            Log::info("WhatsApp Message Sent", ['to' => $to, 'response' => $result]);
            return $result;
        } catch (\Exception $e) {
            Log::error("WhatsApp Message Error", ['to' => $to, 'error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Prepare Text Message Payload
     */
    private function getTextPayload($to, $message)
    {
        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $message],
        ];
    }

    /**
     * Prepare Template Message Payload
     */
    private function getTemplatePayload($to, $templateName, $params)
    {
        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => 'en_US'], // You can change the language code
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => array_map(function ($param) {
                            return ['type' => 'text', 'text' => $param];
                        }, $params),
                    ]
                ]
            ]
        ];
    }
}
