<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use App\Controller\UrlController;

// Rota de teste padrão do Hyperf (pode manter ou apagar)
Router::get('/favicon.ico', function () {
    return '';
});

// A nossa API de criação
Router::post('/api/v1/shorten', [UrlController::class, 'shorten']);

Router::get('/{shortCode}', [UrlController::class, 'redirect']);