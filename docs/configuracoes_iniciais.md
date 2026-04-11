# Configurações Iniciais do Projeto (Shortcut)

Este guia apresenta o passo a passo para configurar e inicializar o ambiente de desenvolvimento do encurtador de URL.

## 1. Criação do Skeleton Hyperf
O projeto foi iniciado utilizando o skeleton oficial do Hyperf via Docker e Composer:

```bash
docker run --rm -v "${PWD}:/data" -it hyperf/hyperf:latest composer create-project hyperf/hyperf-skeleton /data
```

## 2. Inicialização dos Serviços
Para subir a infraestrutura (App, ScyllaDB, Redis) definida no `docker-compose.yml`, execute:

```bash
docker-compose up -d
```

> [!IMPORTANT]
> O **ScyllaDB** é muito mais rápido que o Cassandra, mas ainda assim pode levar alguns segundos para estar pronto para aceitar conexões pela primeira vez.

## 3. Configuração do Banco de Dados ScyllaDB
Para que a aplicação consiga persistir os dados, precisamos criar o **Keyspace** e a **Tabela** inicial.

### Comando de Inicialização
Execute o comando abaixo no seu terminal para criar a base e a tabela de URLs:

```bash
docker exec -it hyperf_scylla cqlsh -e "
CREATE KEYSPACE IF NOT EXISTS url_shortener 
WITH replication = {'class': 'SimpleStrategy', 'replication_factor': 1};

USE url_shortener;

CREATE TABLE IF NOT EXISTS urls (
    short_code text PRIMARY KEY,
    long_url text,
    created_at timestamp
);"
```

### Detalhes do Comando:
- **`docker exec -it hyperf_scylla`**: Comando do Docker para rodar uma instrução dentro do container do Scylla.
- **`cqlsh -e "..."`**: Abre o shell do ScyllaDB e executa as queries SQL (CQL).
- **`CREATE KEYSPACE`**: Cria o banco de dados principal.
- **`CREATE TABLE urls`**: Cria a tabela onde os links encurtados serão armazenados.

## 4. Verificação
Após rodar os comandos, você pode verificar se a aplicação está acessível:

- **Browser**: [http://localhost:9501](http://localhost:9501)
- **Status dos Containers**: `docker ps`

---
*Documento gerado para auxiliar no setup inicial e onboarding do projeto.*
