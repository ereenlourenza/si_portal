<?php

$port = $_ENV['PORT'] ?? 8000;

passthru("php artisan serve --host=0.0.0.0 --port={$port}");
