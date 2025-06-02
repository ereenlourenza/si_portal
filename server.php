<?php

$port = $_ENV['PORT'] ?? 8000;

echo "Starting Laravel on port $port...\n";

passthru("php artisan serve --host=0.0.0.0 --port={$port}");
