<?php

declare(strict_types=1);

namespace App\Service;

use InvalidArgumentException;

class Base62Service
{
    private string $character_set = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private int $numeric_base = 62;
    
    /**
     * @var array<string, int> Hash Map para busca em O(1)
     */
    private array $decode_map = [];

    public function __construct()
    {
        // No Hyperf (Swoole), isso roda apenas uma vez no boot do Worker.
        // Transformamos a string em um dicionário: ['0' => 0, '1' => 1, ... 'Z' => 61]
        $length = strlen($this->character_set);
        for ($i = 0; $i < $length; $i++) {
            $this->decode_map[$this->character_set[$i]] = $i;
        }
    }

    /**
     * Converte um ID (Base 10) para Código Curto (Base 62).
     */
    public function encode_id(int $identifier): string
    {
        if ($identifier < 0) {
            throw new InvalidArgumentException('O identificador não pode ser um número negativo.');
        }

        if ($identifier === 0) {
            return $this->character_set[0];
        }

        $encoded_string = '';
        $current_value = $identifier;

        while ($current_value > 0) {
            $remainder = $current_value % $this->numeric_base;
            $encoded_string = $this->character_set[$remainder] . $encoded_string;
            
            // Otimização 1: Divisão inteira nativa, prevenindo perda de precisão float
            $current_value = intdiv($current_value, $this->numeric_base);
        }

        return $encoded_string;
    }

    /**
     * Converte um Código Curto (Base 62) de volta para ID (Base 10).
     */
    public function decode_string(string $short_code): int
    {
        $decoded_id = 0;
        $string_length = strlen($short_code);

        for ($index = 0; $index < $string_length; $index++) {
            $current_char = $short_code[$index];

            // Otimização 2: Acesso direto à memória em O(1) via Hash Map
            if (!isset($this->decode_map[$current_char])) {
                throw new InvalidArgumentException("Caractere inválido encontrado no código: {$current_char}");
            }

            $char_position = $this->decode_map[$current_char];
            $decoded_id = ($decoded_id * $this->numeric_base) + $char_position;
        }

        return $decoded_id;
    }
}