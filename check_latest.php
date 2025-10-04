<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$latest = App\Models\ByletralPortal::latest()->first();
echo "Latest Record:\n";
echo "ID: {$latest->id}\n";
echo "Title: {$latest->title}\n";
echo "Header Image: " . ($latest->header_image ?? 'NULL') . "\n";
echo "Footer Image: " . ($latest->footer_image ?? 'NULL') . "\n";
echo "\nAll data:\n";
print_r($latest->toArray());
