# Plano de Evolução para Aderência ao Relatório do Projeto

Este plano transforma o relatório funcional em entregas incrementais sobre a base atual do projeto (PHP monolítico), com foco em reduzir risco e gerar valor contínuo.

## 1) Diagnóstico de aderência atual

### Já atendido (ou bem encaminhado)
- Processos: CRUD, vínculo com cliente, status e timeline de eventos.
- Documentos: upload, categorização, vínculo com cliente e visibilidade no portal.
- Portal do cliente: visualização de processos, timeline e documentos compartilhados.
- Prazos (base): CRUD, associação a processo, filtros por status/prioridade.
- Segurança base web: autenticação por sessão, CSRF para POST e ACL por perfil.

### Parcial
- Prazos jurídicos avançados: não há cálculo de dias úteis nem alertas automáticos.
- Segurança/Conformidade: não há log de ações por módulo de forma consistente.
- Dashboard: há métricas básicas, mas faltam indicadores gerenciais completos.

### Não implementado
- Petições com versionamento.
- Checklists e workflows jurídicos.
- Comunicação com cliente (mensagens com histórico).
- Honorários e contratos (financeiro simplificado).
- Classificação automática/indexação de documentos.

---

## 2) Princípios de execução

1. **Entregar por fatias verticais**: banco + regra + tela + rota + validação em cada fase.
2. **Backwards compatible**: preservar fluxos existentes enquanto novos módulos entram.
3. **Segurança por padrão**: toda ação mutável via POST + CSRF + validação de escopo por usuário.
4. **Observabilidade mínima**: registrar eventos críticos para auditoria.

---

## 3) Roadmap por fases (priorizado)

## Fase A — Fechar MVP operacional (curto prazo)

### A1. Prazos com regra jurídica mínima
**Objetivo:** reduzir risco de perda de prazo.

**Entregas**
- Campo de “tipo de contagem” (corridos/úteis).
- Cálculo automático de data limite por dias úteis (ignorando fins de semana na primeira versão).
- Histórico de alteração de prazo (quem alterou, quando, antes/depois).
- Estado de alerta no dashboard (D-7, D-3, D-1, vencido).

**Critério de aceite**
- Ao cadastrar prazo com N dias úteis, sistema calcula e grava data limite coerente.
- Toda alteração de data limite cria histórico consultável.

### A2. Auditoria mínima transversal
**Objetivo:** rastreabilidade e segurança operacional.

**Entregas**
- Helper central para gravar `logs_auditoria` em ações críticas:
  - criar/editar/excluir processo
  - upload/exclusão de documento
  - criar/editar/excluir prazo
- Exibição inicial de logs com filtros por ação e período no admin.

**Critério de aceite**
- Cada ação crítica gera um registro com usuário, ação, tabela e timestamp.

---

## Fase B — Comunicação e produtividade (médio prazo)

### B1. Mensageria cliente ↔ escritório
**Objetivo:** cumprir módulo de comunicação do relatório.

**Entregas**
- Tabela `mensagens_cliente` (cliente_id, usuario_id, autor_tipo, mensagem, lida, criado_em).
- Tela no portal para cliente enviar mensagens.
- Tela do advogado para responder por cliente.
- Histórico completo por conversa.

**Critério de aceite**
- Cliente e advogado conseguem trocar mensagens e visualizar histórico ordenado.

### B2. Dashboard gerencial
**Objetivo:** visão de produtividade e operação.

**Entregas**
- Cards: prazos no prazo x atrasados, processos por status, carga por advogado.
- Filtros por período.
- Ranking simples de volume por usuário (quando houver multi-advogado).

**Critério de aceite**
- Dashboard exibe indicadores consolidados com filtros funcionais.

---

## Fase C — Padronização jurídica (médio/longo prazo)

### C1. Checklists e workflows
**Objetivo:** padronizar execução jurídica.

**Entregas**
- Modelos de checklist por tipo de ação.
- Instância de checklist por processo.
- Marcação de etapa concluída + pendências.

**Critério de aceite**
- Processo pode ter checklist ativo com progresso visível.

### C2. Petições com versionamento
**Objetivo:** controle de histórico documental interno.

**Entregas**
- Entidade `peticoes` + `peticao_versoes`.
- Upload de nova versão mantendo histórico.
- Exibição de autor/data/observação por versão.

**Critério de aceite**
- Uma petição possui múltiplas versões, com trilha de autoria.

---

## Fase D — Financeiro simplificado (longo prazo)

### D1. Honorários e contratos
**Objetivo:** fechar escopo administrativo-financeiro básico.

**Entregas**
- Contrato vinculado ao cliente/processo.
- Tipo de honorário (fixo/êxito), valor e status de pagamento.
- Histórico financeiro básico por contrato.

**Critério de aceite**
- É possível registrar e consultar situação financeira por cliente/contrato.

---

## 4) Backlog técnico não funcional

1. **Segurança**
- Política de senha para cliente.
- Timeout de sessão e renovação de token CSRF.
- Hardening de upload (MIME real, varredura extensões duplas, nomes seguros).

2. **Performance**
- Índices adicionais conforme consultas reais.
- Paginação em listagens longas.
- Caching leve para dashboards.

3. **Arquitetura**
- Manter monólito modular agora e preparar extração futura por bounded contexts.
- Se houver migração de stack no futuro (ex: ASP.NET), manter contratos de domínio/documentação de regras.

---

## 5) Sugestão de ordem prática de implementação

1. A1 (dias úteis + alertas)  
2. A2 (auditoria transversal)  
3. B1 (mensageria cliente)  
4. C1 (checklists/workflows)  
5. C2 (petições/versionamento)  
6. D1 (honorários/contratos)  
7. Backlog não funcional contínuo

---

## 6) Definição de pronto (DoD) por módulo

Para cada módulo novo:
- Migrations SQL aplicáveis.
- Controller + rotas com ACL e CSRF.
- Views com validações e mensagens de erro amigáveis.
- Logs de auditoria em ações críticas.
- Checklist de smoke test manual documentado.

