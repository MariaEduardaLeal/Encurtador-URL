<?php

declare(strict_types=1);

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUrlsTable extends Migration
{
    /**
     * Executa as alterações no banco de dados (Cria a tabela).
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->bigIncrements('id'); // Chave primária padrão e super rápida
            
            // O código Base62 precisa de poucos caracteres. Tamanho 20 é mais que suficiente.
            $table->string('short_code', 20);
            
            // Usamos TEXT porque algumas URLs originais podem ter milhares de caracteres 
            // (ex: links com muitos UTMs e parâmetros de rastreio).
            $table->text('long_url');
            
            $table->timestamps(); // Cria as colunas created_at e updated_at automaticamente


            $table->unique('short_code'); 
        });
    }

    /**
     * Reverte as alterações no banco de dados (Destrói a tabela).
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
}