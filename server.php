<?php

$port = getenv('PORT') ?: 8000; // Railway kasih PORT sendiri, lokal fallback ke 8000

echo "Starting Laravel on port {$port}...\n";

// Jalankan Laravel dengan host 0.0.0.0 dan port yang benar
passthru("php artisan serve --host=0.0.0.0 --port={$port}");
