# Encurtador de URL (Shortcut)

Este é um projeto de alta performance para encurtamento de URLs, desenvolvido com o framework Hyperf (PHP/Swoole), PostgreSQL e Redis.

## 🚀 Arquitetura e Tecnologias

- **PHP 8.4 (Swoole)**: Motor de execução em memória residente para baixa latência.
- **Hyperf 3.1**: Framework otimizado para microserviços e alta concorrência.
- **PostgreSQL 16**: Banco de dados relacional para persistência definitiva das URLs.
- **Redis (Alpine)**: Utilizado para cache de modelos e controle de contadores atômicos para geração de IDs.
- **Docker/Docker-compose**: Ambiente totalmente conteinerizado para facilitar o setup.

## 📋 Requisitos

- Docker
- Docker Compose

## 🛠️ Instalação e Setup

1. **Clonar o Repositório**:
   ```bash
   git clone <url-do-repositorio>
   cd encurtador_url
   ```

2. **Configurar Ambiente**:
   ```bash
   cp .env.example .env
   ```

3. **Subir os Containers**:
   ```bash
   docker-compose up -d
   ```

4. **Instalar Dependências (se não automático)**:
   ```bash
   docker exec -it hyperf_app composer install
   ```

## 🚦 Como Usar

Assim que os containers estiverem rodando, a aplicação estará disponível em `http://localhost:9501`.

### Endpoints Principais (Exemplo)

- `POST /encode`: Envia uma URL longa e recebe o código encurtado.
- `GET /{short_code}`: Redireciona para a URL original.

## 📖 Documentação Adicional

Para entender mais sobre as decisões técnicas e o funcionamento interno, consulte a pasta `docs/`:
- [Conceitos Técnicos](docs/conceitos_tecnicos.md)
- [Configurações Docker](docs/configuracoes_docker_terminal.md)
- [Guia de Início Rápido](docs/configuracoes_iniciais.md)

---
Developed with ❤️ by Maria Eduarda.

