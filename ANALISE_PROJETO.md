# Análise técnica do projeto SJ

## 1) Visão geral

O projeto é uma aplicação PHP em estilo MVC simples (sem framework), com roteamento manual em `routes/web.php`, controllers em `app/controllers`, views em `views` e conexão PDO em `config/database.php`.

**Domínio funcional identificado:**
- Autenticação (login/cadastro).
- Gestão de clientes, processos, compromissos e documentos.
- Área administrativa com auditoria e gestão de usuários.

## 2) Arquitetura atual (pontos observados)

- **Front controller mínimo:** `public/index.php` inicia sessão e inclui conexão + rotas.
- **Roteamento procedural:** grande cadeia de `if/elseif` com `preg_match` em `routes/web.php`.
- **Controle de acesso:** via `AuthMiddleware` com separação entre perfil admin (`perfil_id=1`) e advogado (`perfil_id=2`).
- **Persistência:** MySQL via PDO e schema SQL em `banco.sql`, com constraints e índices básicos.

## 3) Pontos fortes

1. **Uso consistente de prepared statements (PDO)** para reduzir risco de SQL injection no fluxo principal.
2. **Modelagem relacional razoável** com chaves estrangeiras e `ON DELETE` adequados para várias relações.
3. **Separação de áreas por perfil** (admin x advogado) em rotas e middleware.
4. **Controle de upload com whitelist de extensão e limite de tamanho** em documentos.

## 4) Riscos e problemas priorizados

### Alta prioridade

1. **Credenciais de banco hardcoded no repositório** (`root`/`1234`) e exibição direta de erro de conexão.
   - Risco: vazamento de segredo e exposição de detalhes sensíveis em produção.

2. **Endpoint de bootstrap administrativo ativo por rota pública** (`/criar-admin`) sem trava de ambiente.
   - Risco: criação/reset de admin se arquivo permanecer em produção.

3. **Rotas destrutivas sem restrição explícita de método HTTP em alguns casos**.
   - Ex.: toggle de usuário admin, exclusão de compromisso e exclusão/download de documentos aceitam match sem checar `$method`.
   - Risco: maior superfície para CSRF e acionamento indevido por GET.

4. **Ausência de proteção CSRF observável nos formulários**.
   - Risco: operações sensíveis podem ser disparadas por terceiros no contexto de sessão autenticada.

### Média prioridade

5. **Uso frequente de `die()` para erros de negócio/validação** em controllers.
   - Impacto: UX inconsistente, ausência de padronização de resposta e dificuldade de auditoria.

6. **`session_start()` duplicado no DashboardController** apesar de já existir no front controller.
   - Impacto: warnings/eventual comportamento inconsistente dependendo de configuração.

7. **Scripts de teste desatualizados em `public/`** com resultados divergentes da implementação atual.
   - Impacto: baixa confiança nas validações locais.

### Baixa prioridade

8. **Roteamento muito extenso e manual**, dificultando manutenção e evolução.
9. **README praticamente vazio**, sem instruções de setup, execução e deploy seguro.

## 5) Recomendações práticas (roadmap curto)

### Sprint 1 (segurança e hardening)

- Migrar credenciais para variáveis de ambiente (`.env`) e remover valores reais do versionamento.
- Bloquear `/criar-admin` por ambiente (`APP_ENV=local`) e remover o script em produção.
- Exigir `POST` em todas as ações destrutivas/estado-mutável.
- Implementar token CSRF global em todos os formulários com validação server-side.
- Substituir mensagens cruas de exceção por erros amigáveis + log interno.

### Sprint 2 (qualidade e manutenção)

- Padronizar tratamento de erro (flash message + redirect).
- Extrair roteador para tabela de rotas com método/handler.
- Criar camada de serviços/repositórios para diminuir lógica nos controllers.
- Atualizar/remover scripts de teste legados; adotar smoke tests minimamente confiáveis.

### Sprint 3 (observabilidade e DX)

- Enriquecer logs de auditoria com resultado/metadata da ação.
- Adicionar README completo (setup, variáveis, migração, segurança, troubleshooting).
- Definir checklist de release (incluindo validação de endpoint de bootstrap e segredos).

## 6) Conclusão objetiva

O projeto já possui uma base funcional clara para um sistema jurídico de pequeno/médio porte, mas precisa de **hardening de segurança** e **padronização operacional** para produção. O ganho mais rápido virá de quatro ações: segredos via ambiente, bloqueio do bootstrap admin, CSRF e padronização de métodos HTTP nas rotas mutáveis.
