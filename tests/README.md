# Estrutura de testes

Este projeto usa Pest sobre PHPUnit. A estrutura abaixo separa testes unitarios puros de testes que precisam do container do Laravel, HTTP, banco, eventos, filas ou autenticacao.

## Unit

Use `tests/Unit` para funcoes e classes que podem ser exercitadas sem fazer request HTTP e sem depender de banco real.

- `tests/Unit/Class`: value objects e classes auxiliares, como `App\Class\DateRange`.
- `tests/Unit/Helpers`: helpers globais, como `safe_route()`.
- `tests/Unit/Services`: servicos com regra de negocio isolavel, como `TimeRangeService`.
- `tests/Unit/Notifications/Services`: resolucao de destinatarios e regras auxiliares de notificacao.

## Feature

Use `tests/Feature` quando o teste precisar subir a aplicacao Laravel, validar rotas, middleware, requests, models, banco, eventos ou filas.

- `tests/Feature/Auth`: login, logout, primeira senha e fluxos autenticados.
- `tests/Feature/Http/Controllers`: contratos HTTP dos controllers administrativos e APIs internas.
- `tests/Feature/Http/Middleware`: comportamento de middleware.
- `tests/Feature/Http/Requests`: validacao e normalizacao de form requests.
- `tests/Feature/Jobs`: jobs e integracao com filas.
- `tests/Feature/Models`: relacionamentos, casts, scopes e regras que dependem do Eloquent.
- `tests/Feature/Notifications`: criacao, leitura e entrega de notificacoes.
- `tests/Feature/Routes`: smoke tests de rotas web/API.

## Prioridade sugerida

1. Comece pelos unitarios de `TimeRangeService`, `DateRange` e helpers, pois sao rapidos e estaveis.
2. Cubra requests e middleware em `Feature`, pois protegem entrada de dados e acesso.
3. Para notificacoes e web push, use `Notification::fake()`, `Event::fake()` e `Queue::fake()` quando a verificacao nao exigir execucao real.
