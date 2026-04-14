# Padrão Repository e Injeção de Dependência

Este documento explica a arquitetura de acesso a dados adotada no projeto e as razões por trás dessas escolhas.

## 1. O que é o Padrão Repository?

O **Padrão Repository** atua como uma camada mediadora entre a lógica de negócio da aplicação e a camada de persistência de dados (Banco de Dados). 

Em vez de a aplicação chamar o Query Builder ou o Eloquent diretamente nos Controllers ou Services, ela interage com uma **Interface**.

### Componentes Criados:

1.  **Interface (`app/Repository/Contracts/UrlRepositoryInterface.php`)**: Define o *contrato*. Ela diz **o que** o sistema precisa fazer (ex: `save`, `findByCode`), mas não **como** fazer.
2.  **Implementação (`app/Repository/Infrastructure/PostgresUrlRepository.php`)**: Define o *como*. Esta classe contém o código específico do PostgreSQL para realizar as operações.

## 2. Por que usar esse padrão?

### Desacoplamento (Liskov Substitution & Dependency Inversion)
A aplicação depende de uma abstração (`UrlRepositoryInterface`), não de uma implementação concreta (`PostgresUrlRepository`).
- **Flexibilidade**: Se amanhã decidirmos usar MongoDB ou Redis como banco principal, basta criar uma nova implementação da interface. O restante do código da aplicação não precisará de **nenhuma alteração**.

### Testabilidade
Fica muito mais fácil criar testes unitários. Podemos criar um "Mock" ou um repositório em memória para testar a lógica de negócio sem precisar de um banco de dados real rodando.

### Organização
Centraliza toda a lógica de persistência em um único lugar. Se uma query precisar de otimização, você sabe exatamente onde alterá-la.

## 3. Injeção de Dependência (`config/autoload/dependencies.php`)

No framework Hyperf, utilizamos o arquivo de dependências para dizer ao container de DI (Injeção de Dependência) qual classe concreta deve ser entregue quando uma interface for solicitada.

**Configuração realizada:**
```php
return [
    \App\Repository\Contracts\UrlRepositoryInterface::class => \App\Repository\Infrastructure\PostgresUrlRepository::class,
];
```

**Resultado:**
Quando você injetar `UrlRepositoryInterface` no construtor de um Controller, o Hyperf automaticamente entregará uma instância de `PostgresUrlRepository`.

---
*Este padrão garante que o Shortcut seja robusto, escalável e fácil de manter a longo prazo.*
