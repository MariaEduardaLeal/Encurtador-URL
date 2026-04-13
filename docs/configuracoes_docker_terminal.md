# Configurações do Docker - Decisões de Projeto

Este documento detalha as decisões tomadas durante a configuração do ambiente Docker para o projeto de encurtador de URL, utilizando o framework Hyperf.

## 1. Time Zone: `America/Sao_Paulo`
**Por que:** Sistemas distribuídos dependem de precisão temporal.
**Impacto:** Garante que os registros no ScyllaDB e os logs de cliques no Redis estejam sincronizados com o horário local. Sem isso, cálculos de expiração de URL (ex: expirar em 24h) podem falhar devido ao fuso horário incorreto.

## 2. Database (MySQL Client): `y`
**Por que:** Mesmo focando no ScyllaDB, o Hyperf usa essa base para migrations e estruturas de sistema.
**Impacto:** Permite o uso do Eloquent (ORM do Hyperf) para tabelas auxiliares. É mais eficiente gerenciar metadados simples aqui do que no ScyllaDB, que é otimizado para volumes massivos de dados.

## 3. Database NoSQL: ScyllaDB (Substituindo Cassandra)
**Decisão:** Substituímos a imagem oficial do Cassandra pelo **ScyllaDB**.
**Por que:** O Cassandra é escrito em Java e consome muita RAM, o que estava pesando na máquina de desenvolvimento. O ScyllaDB é escrito em C++, é compatível com o protocolo do Cassandra (CQL), mas é muito mais eficiente em termos de CPU e Memória.
**Configuração de Dev:** Ativado o `--developer-mode 1` e limites de memória para garantir fluidez no PC local.
**Impacto:** Mantemos a arquitetura colunar de alta performance, mas com um ambiente mais leve.

## 3. Redis Client: `y`
**A Decisão Crítica:** O Redis é essencial para a escalabilidade do projeto.
**Por que:** Para gerar IDs sem colisão de forma rápida. O comando `INCR` do Redis é atômico; se 10.000 pessoas pedirem um ID simultaneamente, o Redis garante a unicidade sequencial.
**Impacto:** Velocidade bruta. O redirecionamento (leitura) consulta o Redis primeiro. Consultar a RAM (Redis) leva microssegundos, enquanto o disco (banco de dados) leva milissegundos.

## 4. RPC e Config Center: `n`
**Por que:** Estes são componentes voltados para Microserviços (comunicação entre múltiplos servidores).
**Impacto:** Redução de "bloatware" (código desnecessário). Como o core está em um único projeto, adicionar gRPC ou Consul traria complexidade de rede e latência desnecessária neste momento.

## 5. Hyperf Constants: `y`
**Por que:** Necessidade de padronização nas respostas do sistema.
**Impacto:** Centraliza mensagens de erro (ex: "URL não encontrada") em uma classe `ErrorCode`. Isso facilita manutenção futura, traduções e mantém o código limpo e profissional.

## 6. Async Queue (Redis): `y` vs AMQP: `n`
**A Escolha:** Filas baseadas em Redis, rejeitando AMQP (RabbitMQ).
**O Motivo:** Em um encurtador, o redirecionamento deve ser instantâneo. O registro de métricas (analytics) é um processo mais lento que não deve bloquear o usuário.
**Impacto:** O Redis Queue processa o log em background. O usuário é redirecionado em ~10ms, e os dados do clique são processados logo em seguida. Evitamos a sobrecarga de memória de instalar um software adicional como o RabbitMQ.

## 7. Model Cache: `y`
**Por que:** Performance extrema de leitura.
**Impacto:** Em caso de URLs "virais" (milhares de acessos por segundo), o Hyperf serve o objeto do cache, protegendo o Cassandra de picos repentinos de tráfego.

## 8. Elasticsearch e Tracer: `n`
**A Razão:** Foco e economia de recursos.
**Impacto:** O encurtador busca por chave exata (`short_code`), tarefa na qual o ScyllaDB é imbatível. Elasticsearch seria redundante. O Tracer (Zipkin) seria monitoramento excessivo para a fase inicial, consumindo CPU desnecessariamente.

## 9. Pest PHP: `y`
**Por que:** Aplicação de conceitos de SOLID e Clean Code.
**Impacto:** Permite criar testes unitários para a lógica de conversão Base62.
**Exemplo:** Garante que o ID `123.456` sempre resulte na string `abc12`, evitando bugs críticos em produção que quebrariam milhões de links existentes.

---

*Documento gerado para documentar a fundamentação da infraestrutura do encurtador de URL.*
