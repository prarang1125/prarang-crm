<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessage implements ShouldQueue
{
    use Queueable;
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $phone;
    protected $message;
    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsappService)
    {
        try {
            $response = $whatsappService->sendMessage($this->phone, $this->message,'hello_world');
            Log::info("Message sent to {$this->phone} {$response}");
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Log::error("WhatsApp Message Error for {$this->phone}: " . $e->getMessage());
        }
    }
}
