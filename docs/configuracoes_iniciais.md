# Configurações Iniciais do Projeto (Shortcut)

Este guia apresenta o passo a passo para configurar e inicializar o ambiente de desenvolvimento do encurtador de URL.

## 1. Criação do Skeleton Hyperf
O projeto foi iniciado utilizando o skeleton oficial do Hyperf via Docker e Composer.

## 2. Inicialização dos Serviços
Para subir a infraestrutura (App, PostgreSQL, Redis) definida no `docker-compose.yml`, execute:

```bash
docker-compose up -d
```

## 3. Configuração do Banco de Dados PostgreSQL
Para que a aplicação consiga persistir os dados, precisamos criar a tabela inicial. Embora o Hyperf suporte migrations, você pode criar a tabela manualmente para testes rápidos.

### Comando de Inicialização (SQL)
Acesse o container do Postgres e execute o SQL abaixo:

```bash
docker exec -it hyperf_postgres psql -U hyperf -d url_shortener -c "
CREATE TABLE IF NOT EXISTS urls (
    id SERIAL PRIMARY KEY,
    short_code VARCHAR(10) UNIQUE NOT NULL,
    long_url TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);"
```

### Detalhes do Comando:
- **`docker exec -it hyperf_postgres`**: Comando do Docker para rodar uma instrução dentro do container do Postgres.
- **`psql -U hyperf -d url_shortener`**: Abre o utilitário de terminal do Postgres com o usuário e banco definidos no `.env`.
- **`CREATE TABLE urls`**: Cria a estrutura relacional para armazenar os links. Note o uso de `SERIAL` para o ID e `VARCHAR` para o código encurtado.

## 4. Verificação
Após rodar os comandos, você pode verificar se a aplicação está acessível:

- **Browser**: [http://localhost:9501](http://localhost:9501)
- **Status dos Containers**: `docker ps`

---
*Documento gerado para auxiliar no setup inicial e onboarding do projeto.*

