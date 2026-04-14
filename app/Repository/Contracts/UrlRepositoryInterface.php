<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

interface UrlRepositoryInterface
{
    /**
     * Salva a URL encurtada associada à URL original.
     *
     * @param string $shortCode O código base62 gerado (ex: "aB3")
     * @param string $longUrl A URL completa original
     * @return bool Retorna true se salvou com sucesso
     */
    public function save(string $shortCode, string $longUrl): bool;

    /**
     * Busca a URL original baseada no código encurtado.
     *
     * @param string $shortCode O código encurtado recebido no clique
     * @return string|null A URL original ou null se não encontrada
     */
    public function findByCode(string $shortCode): ?string;
}