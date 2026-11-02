<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class XenditService{
    protected string $secretKey;
    public function __construct(){
        $this->secretKey = config('xendit.secret_key');
    }

    public function buatPaymentRequest(
    string $referenceId,
    int $jumlah,
    string $channelCode,
    array $channelProperties = []
) {
    $response = Http::withBasicAuth($this->secretKey, '')
        ->withHeaders([
            'api-version' => '2024-11-11',
            'Content-Type' => 'application/json',
        ])
        ->post('https://api.xendit.co/v3/payment_requests', [
            'reference_id' => $referenceId,
            'type' => 'PAY',
            'country' => 'ID',
            'currency' => 'IDR',
            'request_amount' => $jumlah,
            'channel_code' => $channelCode,
            'channel_properties' => $channelProperties,
        ]);

    if ($response->failed()) {
        throw new \Exception(
            'Gagal membuat pembayaran Xendit: ' . $response->body()
        );
    }

    return $response->json();
}
}