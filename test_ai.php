<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$key = config('services.anthropic.key');
echo "Key length: " . strlen($key) . PHP_EOL;
$r = \Illuminate\Support\Facades\Http::withHeaders([
    'x-api-key' => $key,
    'anthropic-version' => '2023-06-01',
    'content-type' => 'application/json',
])->post('https://api.anthropic.com/v1/messages', [
    'model' => 'claude-sonnet-4-5',
    'max_tokens' => 100,
    'messages' => [['role' => 'user', 'content' => 'Say OK']],
]);
echo "Status: " . $r->status() . PHP_EOL;
echo "Body: " . substr($r->body(), 0, 200) . PHP_EOL;
