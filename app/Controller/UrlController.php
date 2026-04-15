<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\ShortenUrlRequest;
use App\Repository\Contracts\UrlRepositoryInterface;
use App\Service\Base62Service;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\ResponseInterface;

class UrlController
{
    public function __construct(
        private UrlRepositoryInterface $repository,
        private Base62Service $base62,
        private ResponseInterface $response
    ) {}

    public function shorten(ShortenUrlRequest $request)
    {
        $longUrl = $request->validated()['long_url'];

        $sequence = Db::selectOne("Select nextval('urls_id_seq') AS id");
        $nextId = (int) $sequence->id;
        $shortCode = $this->base62->encode_id($nextId);
        $this->repository->save($shortCode, $longUrl);
        return $this->response->json([
            'success' => true,
            'data' => [
                'short_url' => env('APP_URL', 'http://localhost:9501') . '/' . $shortCode,
                'short_code' => $shortCode,
                'original_url' => $longUrl
            ]
        ])->withStatus(201);
    }
}
