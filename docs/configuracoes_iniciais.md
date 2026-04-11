# Configurações Iniciais do Projeto (Shortcut)

Este guia apresenta o passo a passo para configurar e inicializar o ambiente de desenvolvimento do encurtador de URL.

## 1. Criação do Skeleton Hyperf
O projeto foi iniciado utilizando o skeleton oficial do Hyperf via Docker e Composer:

```bash
docker run --rm -v "${PWD}:/data" -it hyperf/hyperf:latest composer create-project hyperf/hyperf-skeleton /data
```

## 2. Inicialização dos Serviços
Para subir a infraestrutura (App, Cassandra, Redis) definida no `docker-compose.yml`, execute:

```bash
docker-compose up -d
```

> [!IMPORTANT]
> O **Cassandra** é um serviço pesado e pode levar até 2 minutos para estar pronto para aceitar conexões pela primeira vez.

## 3. Configuração do Banco de Dados Cassandra
Para que a aplicação consiga persistir os dados, precisamos criar o **Keyspace** (o equivalente ao Database no MySQL).

### Comando de Inicialização
Execute o comando abaixo no seu terminal:

```bash
docker exec -it hyperf_cassandra cqlsh -e "CREATE KEYSPACE IF NOT EXISTS url_shortener WITH replication = {'class': 'SimpleStrategy', 'replication_factor': 1};"
```

### Detalhes do Comando:
- **`docker exec -it hyperf_cassandra`**: Comando do Docker para rodar uma instrução dentro do container do Cassandra.
- **`cqlsh -e "..."`**: Abre o shell do Cassandra e executa a query SQL (CQL) especificada.
- **`CREATE KEYSPACE IF NOT EXISTS url_shortener`**: Cria o container lógico para as tabelas do projeto.
- **`replication_factor: 1`**: Define que haverá apenas uma cópia dos dados (ideal para ambiente de desenvolvimento).

## 4. Verificação
Após rodar os comandos, você pode verificar se a aplicação está acessível:

- **Browser**: [http://localhost:9501](http://localhost:9501)
- **Status dos Containers**: `docker ps`

---
*Documento gerado para auxiliar no setup inicial e onboarding do projeto.*
