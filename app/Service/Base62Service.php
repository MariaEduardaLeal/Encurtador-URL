<?php

declare(strict_types=1);

namespace App\Service;

use InvalidArgumentException;

class Base62Service
{
    /**
     * @var string O alfabeto utilizado para a conversão de base. Contém 62 caracteres (0-9, a-z, A-Z).
     * Este conjunto de caracteres define a 'linguagem' do nosso encurtador.
     */
    private string $character_set = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var int A base numérica utilizada para o cálculo (62).
     */
    private int $numeric_base = 62;
    
    /**
     * @var array<string, int> Hash Map para busca em O(1).
     * Mapeia cada caractere do alfabeto para seu respectivo valor numérico.
     * Essencial para a performance do processo de decodificação.
     */
    private array $decode_map = [];

    /**
     * Construtor da classe.
     * Inicializa o mapa de decodificação para permitir buscas rápidas durante a conversão de volta para Base 10.
     * No ambiente Hyperf/Swoole, este construtor é executado apenas uma vez no boot do Worker,
     * garantindo que o mapa esteja sempre pronto em memória.
     */
    public function __construct()
    {
        $length = strlen($this->character_set);
        for ($i = 0; $i < $length; $i++) {
            $this->decode_map[$this->character_set[$i]] = $i;
        }
    }

    /**
     * Converte um identificador numérico (ID em Base 10) para um código curto (Base 62).
     * 
     * O algoritmo funciona realizando divisões sucessivas pelo valor da base (62) e 
     * utilizando os restos da divisão para selecionar os caracteres no alfabeto definido.
     * 
     * @param int $identifier O número único (geralmente uma PK do banco ou contador) a ser convertido.
     * @return string O código encurtado resultante.
     * @throws InvalidArgumentException Caso o identificador seja um número negativo.
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
            
            // Otimização: Divisão inteira nativa para evitar problemas com precisão de ponto flutuante em números grandes.
            $current_value = intdiv($current_value, $this->numeric_base);
        }

        return $encoded_string;
    }

    /**
     * Converte um código curto (Base 62) de volta para o seu identificador numérico original (Base 10).
     * 
     * O processo realiza a engenharia reversa do código, multiplicando o valor acumulado pela base 
     * e somando a posição do caractere atual no alfabeto. O uso do 'decode_map' torna esta busca O(1).
     * 
     * @param string $short_code O código encurtado (slug) que será decodificado.
     * @return int O identificador ID original em Base 10.
     * @throws InvalidArgumentException Caso o código contenha caracteres que não pertencem ao alfabeto Base 62.
     */
    public function decode_string(string $short_code): int
    {
        $decoded_id = 0;
        $string_length = strlen($short_code);

        for ($index = 0; $index < $string_length; $index++) {
            $current_char = $short_code[$index];

            // Validação rápida usando o Hash Map em memória.
            if (!isset($this->decode_map[$current_char])) {
                throw new InvalidArgumentException("Caractere inválido encontrado no código: {$current_char}");
            }

            $char_position = $this->decode_map[$current_char];
            $decoded_id = ($decoded_id * $this->numeric_base) + $char_position;
        }

        return $decoded_id;
    }
}