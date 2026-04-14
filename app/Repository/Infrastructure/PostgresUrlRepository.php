<?php

declare(strict_types=1);

namespace App\Repository\Infrastructure;

use App\Repository\Contracts\UrlRepositoryInterface;
use Hyperf\DbConnection\Db;

class PostgresUrlRepository implements UrlRepositoryInterface
{
    /**
     * Salva a nova URL no PostgreSQL.
     *
     * @param string $shortCode
     * @param string $longUrl
     * @return bool
     * @throws \Throwable Se o banco falhar
     */
    public function save(string $shortCode, string $longUrl): bool
    {
        return Db::table('urls')->insert([
            'short_code' => $shortCode,
            'long_url' => $longUrl,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Busca a URL de destino de forma otimizada.
     *
     * @param string $shortCode
     * @return string|null
     */
    public function findByCode(string $shortCode): ?string
    {
        $record = Db::table('urls')
            ->where('short_code', $shortCode)
            ->value('long_url');

        return $record ?: null;
    }
}