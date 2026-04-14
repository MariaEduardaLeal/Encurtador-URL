# Configurações do Docker - Decisões de Projeto

Este documento detalha as decisões tomadas durante a configuração do ambiente Docker para o projeto de encurtador de URL, utilizando o framework Hyperf e PostgreSQL.

## 1. Time Zone: `America/Sao_Paulo`
**Por que:** Sistemas distribuídos dependem de precisão temporal.
**Impacto:** Garante que os registros no PostgreSQL e os logs de cliques no Redis estejam sincronizados com o horário local. Sem isso, cálculos de expiração de URL podem falhar.

## 2. Banco de Dados Primário: PostgreSQL 16
**Decisão:** Substituímos o ScyllaDB/Cassandra pelo **PostgreSQL**.
**Por que:** Embora o ScyllaDB seja imbatível em escala massiva de dados NoSQL, o PostgreSQL oferece uma consistência ACID superior, um ecossistema de ferramentas mais maduro (migrations, backup, ORM) e é mais do que suficiente para lidar com milhões de registros no contexto deste projeto.
**Impacto:** Simplificação da arquitetura. Utilizamos o Eloquent (ORM do Hyperf) com `pdo_pgsql` para gerenciar tanto as URLs quanto os metadados em um único banco relacional robusto.

## 3. Redis Client: `y`
**Por que:** Essencial para a escalabilidade.
**Impacto:** Velocidade bruta para geração de IDs e cache. O redirecionamento consulta o Redis primeiro. Consultar a RAM (Redis) leva microssegundos, o que é vital para o fluxo de um encurtador.

## 4. Async Queue (Redis): `y`
**A Escolha:** Filas baseadas em Redis.
**O Motivo:** Em um encurtador, o redirecionamento deve ser instantâneo. O registro de métricas (analytics) é um processo que pode rodar em background sem bloquear o usuário.
**Impacto:** O usuário é redirecionado em ~10ms, e os dados do clique são processados logo em seguida via Worker.

## 5. Model Cache: `y`
**Por que:** Performance extrema de leitura.
**Impacto:** Em caso de URLs "virais", o Hyperf serve o objeto diretamente do Redis, protegendo o banco de dados de picos repentinos de tráfego.

## 6. Pest PHP: `y`
**Por que:** Garantia de qualidade e Clean Code.
**Impacto:** Permite criar testes unitários para a lógica de conversão Base62 e garantir que o sistema de IDs funcione sem regressões.

---

*Documento gerado para documentar a fundamentação da infraestrutura do encurtador de URL.*

