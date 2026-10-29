# Sistema de estatísticas

Estatística própria do site público. O script `public/js/t.js` envia eventos para `POST /t`. O painel em `/gestao` lê os agregados em `GET /gestao/api/dashboard`. A saúde do site vem da API PageSpeed Insights (Lighthouse), em cache, e não das visitas.

Não usa Google Analytics nem outro coletor externo para tráfego. Scripts de terceiros ainda podem ser colados em `tag_header`, `tag_body` e `tag_footer` nas configurações do site; isso é independente deste sistema.

## Visão geral

```
Página pública
  └─ js/t.js
       ├─ view   (page load)
       ├─ beat   (a cada 20s, aba visível; também ao ocultar a aba)
       └─ leave  (pagehide)
            │
            ▼
POST /t  (throttle 60/min)
  SiteAnalyticsController
       ├─ ignora DNT, bot, origem cruzada, path do CMS
       ├─ cria ou atualiza page_sessions
       └─ em view, grava page_views
            │
            ▼
GET /gestao/api/dashboard?range=
  AnalyticsService::dashboard()
            │
            ▼
Dashboard (React)
  cards, gráfico, páginas, origens, mapa, saúde Lighthouse
```

Tabelas: `page_sessions` e `page_views` (`database/migrations/2026_09_17_200000_create_page_analytics_tables.php`).

## Coleta

### Onde o script entra

`resources/views/layouts/site.blade.php` inclui `js/t.js` só na página pública. Preview do CMS (`$cmsPreview` ou `?cms_preview=1`) não carrega o script, então edição e preview não geram visita.

### Identidade

| Chave | Onde fica | O que representa |
| --- | --- | --- |
| `vid` | `localStorage` (`rba_vid`) | Visitante. Persiste no navegador. |
| `sid` | `sessionStorage` (`rba_sid`) | Sessão. Nova sessão se não existir ou se a última atividade tiver mais de 30 minutos. |

UUID via `crypto.randomUUID`, com fallback. O servidor não decide a sessão: usa o `sid` que o cliente manda. `vid` é gravado em `visitor_key` e não entra em nenhum agregado do dashboard.

Cada envio manda `t`, `sid`, `vid`, `path` (`location.pathname`) e `ref` (`document.referrer`).

### Eventos

| `t` | Quando | Efeito |
| --- | --- | --- |
| `view` | `window` `load` (ou imediato se a página já carregou) | Incrementa `page_count` e cria um `page_views`. |
| `beat` | A cada 20 segundos com a aba visível, e no `visibilitychange` para `hidden` | Atualiza `last_seen_at` e `duration_seconds`. Não cria page view. |
| `leave` | `pagehide` | Mesmo efeito do `beat`. |

`view` vai por `fetch` (`keepalive`). `beat` e `leave` usam `navigator.sendBeacon` quando existe, senão o mesmo `fetch`. Falha de rede é engolida no cliente.

`beat` não dispara com a aba oculta no intervalo. O `visibilitychange` para `hidden` manda um beat extra para fechar a duração antes de a aba ir para segundo plano.

### Tempo de carregamento

No `view`, o campo `lt` é a duração da navegação (`PerformanceNavigationTiming`): `duration`, ou `loadEventEnd` se a duração vier zerada. O servidor aceita inteiro de 0 a 120000 ms e grava em `page_views.load_time_ms`.

### Duração da sessão

`duration_seconds` é a diferença entre `started_at` e o `now()` do último evento aceito (`view`, `beat` ou `leave`). Não é tempo ativo contínuo: se o último beat chegou e a pessoa fechou sem `leave`/`visibilitychange`, a duração para no último evento recebido. O intervalo máximo sem beat, com a aba visível, é cerca de 20 segundos.

### O que o servidor descarta

`SiteAnalyticsController` responde `204` e não grava quando:

- Header `DNT: 1`. O script também aborta se `navigator.doNotTrack === '1'` ou `window.doNotTrack === '1'`.
- User-Agent vazio ou de bot. O match inclui `bot`, `crawl`, `spider`, `slurp`, `facebookexternalhit`, `preview`, `lighthouse`, `headless`, `pingdom`, `gtmetrix`, `curl`, `wget`, `python-requests`, `httpclient`.
- `Origin` ou `Referer` com host diferente de `APP_URL`. Sem esses headers, a requisição passa.
- Payload inválido (`t` fora de `view|beat|leave`, `sid`/`vid` que não sejam UUID).
- Path do CMS. O prefixo vem de `ADMIN_PATH` (padrão `gestao`). `/gestao` e qualquer subpath são ignorados. A sessão nem é criada.

Throttle: 60 requisições por minuto na rota `analytics.collect`.

### Normalização de path

Path vazio ou `/` vira `/`. Query string e fragmento caem fora (`parse_url` só usa o path). Barras finais são removidas. O `page_id` é o id da página cujo `slug` bate com o path; `/` resolve para o slug `home`. Path sem página correspondente grava `page_id` nulo e o path mesmo assim.

### Origem

`origin` é o host do `ref`. Sem referrer, host inválido, ou host igual ao de `APP_URL`, grava `direct`. No dashboard, `direct` e string vazia aparecem como **Direto**. Referrer interno (navegação entre páginas do próprio site) não conta como origem externa.

O host é gravado na criação da sessão e não é atualizado nos eventos seguintes.

### Geolocalização

Só na criação da sessão (`GeoLocationService`):

1. Headers da Cloudflare, se existirem: `CF-IPCountry`, `CF-Region`, `CF-IPCity`, `CF-IPLatitude`, `CF-IPLongitude`. País `XX` ou `T1` vira nulo.
2. Se não houver coordenada, cidade nem país, e o IP for público, consulta `http://ip-api.com/json/{ip}` com timeout de 2 segundos. Resultado em cache por 7 dias na chave `geo:{ip}`.
3. IP privado, reservado ou vazio não é consultado. Falha da API deixa cidade/país nulos.

Cidade nula não entra no ranking de locais.

### Dispositivo

`App\Support\UserAgent` classifica na criação da sessão:

- **device:** `tablet`, `mobile` ou `desktop`
- **browser:** Edge, Opera, Chrome, Firefox, Safari ou `Outro`
- **os:** Windows, macOS, Android, iOS, Linux ou `Outro`

User-Agent é truncado em 512 caracteres. IP vai em `page_sessions.ip` (até 45 caracteres, cabe IPv6).

Device, browser, OS, IP e `visitor_key` ficam no banco. O dashboard não agrega nem exibe esses campos.

## Modelo de dados

### `page_sessions`

Uma linha por `session_key`.

| Campo | Uso |
| --- | --- |
| `session_key` | UUID da sessão, único |
| `visitor_key` | UUID do visitante, indexado, sem agregado |
| `ip` | IP da requisição que criou a sessão |
| `country`, `region`, `city`, `latitude`, `longitude` | Geo, imutável depois da criação |
| `device`, `browser`, `os`, `user_agent` | Classificação do UA |
| `origin`, `referrer` | Host de origem e URL de referência |
| `started_at` | Primeiro evento. Base dos gráficos, visitas do mês, duração, profundidade, origens e locais |
| `last_seen_at` | Último evento. Visitante online = visto nos últimos 3 minutos |
| `duration_seconds` | Tempo entre início e último evento |
| `page_count` | Quantidade de eventos `view` da sessão |

Sem `created_at` / `updated_at` do Eloquent (`$timestamps = false`).

### `page_views`

Uma linha por `view`.

| Campo | Uso |
| --- | --- |
| `page_session_id` | FK, cascade ao apagar a sessão |
| `page_id` | FK opcional para `pages`, `nullOnDelete` |
| `path` | Path normalizado |
| `load_time_ms` | Tempo de carregamento daquele view |
| `created_at` | Momento do view. Base do ranking de páginas e da média de carregamento |

`beat` e `leave` não criam linha aqui.

Não há job de retenção nem purge dessas tabelas.

## Dashboard

Rota autenticada pelo middleware `cms`: `GET /gestao/api/dashboard`. Convidado recebe 401. O React (`DashboardScreen`) busca ao montar, ao trocar o período, e de novo a cada 15 segundos.

Query `range`. Valor fora da lista cai em `month`.

### Períodos

| `range` | Janela atual | Comparativo | Balde do gráfico |
| --- | --- | --- | --- |
| `today` | Hoje, 00:00–23:59 | Ontem | Hora (`00h` … `23h`) |
| `7d` | Hoje e os 6 dias anteriores | Os 7 dias antes disso | Dia (`d/m`) |
| `month` | Mês calendário corrente (padrão) | Mês calendário anterior | Dia |
| `30d` | Hoje e os 29 dias anteriores | Os 30 dias antes disso | Dia |
| `last_month` | Mês calendário anterior inteiro | O mês antes desse | Dia |

O comparativo entra só no gráfico. Cards de páginas, origens, locais, duração, profundidade e tempo de carga usam a janela atual.

`month_visits` ignora o filtro: conta sessões com `started_at` dentro do mês calendário corrente.

### O que aparece na tela

**Online agora.** Sessões com `last_seen_at` nos últimos 3 minutos. Não depende do período. Com beat de 20 segundos, alguém com a aba aberta e visível permanece online. Aba oculta para de bater no intervalo; o beat do `visibilitychange` ainda atualiza `last_seen_at`, então a pessoa some do card cerca de 3 minutos depois de ocultar ou sair.

**Tempo médio para carregamento da página.** Média de `load_time_ms` dos views da janela, ignorando nulo e zero. Exibição: `ms`, ou segundos com uma casa e vírgula a partir de 1000 ms.

**Visitas do mês.** Contagem de sessões iniciadas no mês corrente. É sessão, não page view e não visitante único.

**Saúde do site.** Snapshot Lighthouse. Detalhe na seção seguinte.

**Acessos por dia.** Barras de sessões por hora (hoje) ou por dia (demais períodos). Série coral = período atual, roxa = período anterior. Se os comprimentos divergirem, a série anterior é preenchida com zero ou cortada para o tamanho da atual. Rótulos de dia são omitidos quando há mais de 14 pontos, mantendo cerca de 10 marcas mais o último.

**Páginas mais acessadas.** Top 8 paths por contagem de `page_views` na janela. Título: `seo_title` da página, cortado no primeiro `|`; sem página, deriva do path (`/` vira `Home`, hífens viram espaço, title case).

**Tempo médio de visita.** Média de `duration_seconds` das sessões iniciadas na janela, arredondada para inteiro. Formato: `s`, `min`, `h`.

**Profundidade da visita.** Média de `page_count` das sessões iniciadas na janela, uma casa decimal. Unidade na UI: páginas por sessão.

**Principais origens.** Top 8 hosts por sessões iniciadas na janela. Percentual é sobre a soma desses até 8 grupos, não sobre todas as sessões do período. `direct` vira **Direto**.

**Principais locais.** Até 20 cidades com `city` preenchida, agrupadas por cidade, região, país e coordenada. A lista mostra as 6 primeiras. O mapa (Leaflet + tiles OpenStreetMap) plota só pontos com latitude e longitude. Raio do marcador escala com o volume. Sem coordenadas, o mapa abre no centro do Brasil (zoom 4). Tooltip: cidade, região, país e quantidade.

## Saúde do site (Lighthouse)

Não mede o tráfego. Mede a URL pública via PageSpeed Insights v5 (`LighthouseService`).

Config:

- `LIGHTHOUSE_URL`, fallback `APP_URL` (`config/cms.php` → `lighthouse_url`)
- `PAGESPEED_API_KEY` opcional (`pagespeed_key`). Sem chave a API pública ainda responde, com cota menor.

Duas estratégias por medição: `mobile` e `desktop`. Locale `pt_BR`. Categorias: performance, acessibilidade, boas práticas, SEO. Scores de 0 a 100.

Métricas gravadas:

| Campo | Audit |
| --- | --- |
| `fcp_ms` | first-contentful-paint |
| `lcp_ms` | largest-contentful-paint |
| `tbt_ms` | total-blocking-time |
| `si_ms` | speed-index |
| `cls` | cumulative-layout-shift (3 casas) |

A UI mostra LCP, FCP, TBT e CLS. `si_ms` fica no payload e não é renderizado. O anel **Saúde** usa o score de performance. Cor: verde a partir de 90, dourado a partir de 50, coral abaixo disso.

### Cache e atualização

Chave `lighthouse:snapshot`, TTL de 1 dia.

- Snapshot com mais de 12 horas, ou sem `fetched_at`, dispara refresh em background depois da resposta (`Cache::lock` `lighthouse:refresh`, 120 segundos).
- Sem cache e URL local (`localhost`, `127.0.0.1`, `::1`, `*.local`, `*.test`): status `pending`, sem chamar a API.
- Sem cache e URL pública: devolve `pending` e enfileira o refresh.
- `POST /gestao/api/dashboard/lighthouse` força `refresh()` na hora (botão **Atualizar**). Pode levar até o timeout de 90 segundos por estratégia.
- Agendamento: `lighthouse:refresh` todo dia às 06:15 (`routes/console.php`). O comando também pode ser rodado na mão.
- Em `testing`, o refresh automático não é disparado.
- Exceção na API grava status `error` e reporta. A UI mostra "Falha ao consultar a API do PageSpeed."

Status possíveis: `pending`, `ready`, `error`.

## Rotas

| Método | Rota | Auth | Função |
| --- | --- | --- | --- |
| `POST` | `/t` | Não | Coleta. Nome `analytics.collect`. Throttle 60/min. |
| `GET` | `/gestao/api/dashboard` | `cms` | Agregados. Query `range`. |
| `POST` | `/gestao/api/dashboard/lighthouse` | `cms` | Medição Lighthouse síncrona. |

O prefixo `/gestao` é `ADMIN_PATH`.

## Arquivos

| Arquivo | Papel |
| --- | --- |
| `public/js/t.js` | Coletor no browser |
| `app/Http/Controllers/SiteAnalyticsController.php` | Ingestão |
| `app/Services/GeoLocationService.php` | Cloudflare e ip-api |
| `app/Support/UserAgent.php` | Bot, device, browser, OS |
| `app/Models/PageSession.php` | Sessão |
| `app/Models/PageView.php` | Page view |
| `app/Services/AnalyticsService.php` | Agregados do dashboard |
| `app/Services/LighthouseService.php` | PageSpeed |
| `app/Http/Controllers/Admin/DashboardController.php` | API do painel |
| `resources/js/admin/screens/DashboardScreen.tsx` | Tela |
| `resources/js/admin/screens/analytics.ts` | Tipos, períodos, formatadores |
| `resources/js/admin/components/VisitsChart.tsx` | Gráfico |
| `resources/js/admin/components/LocationsMap.tsx` | Mapa |
| `resources/js/admin/components/SiteHealth.tsx` | Lighthouse |
| `resources/js/admin/components/DateRangeFilter.tsx` | Filtro de período |
| `tests/Feature/SiteAnalyticsTest.php` | Coleta |
| `tests/Feature/CmsDashboardAnalyticsTest.php` | Agregados |
| `tests/Feature/CmsLighthouseTest.php` | Refresh Lighthouse |

## Limites que importam na leitura dos números

- Visita no card do mês e no gráfico é **sessão** (`page_sessions`), não page view e não `visitor_key`.
- Page view só nasce no evento `view`. Recarregar a página gera outro view e soma em `page_count` se o `sid` for o mesmo.
- Sessão nova no cliente após 30 minutos sem atividade (`rba_sid_at`). Aba fechada zera `sessionStorage`, então a próxima abertura é outra sessão mesmo antes dos 30 minutos.
- Online agora usa 3 minutos de `last_seen_at`, não o filtro de período.
- Duração e profundidade são médias das sessões **iniciadas** na janela, inclusive sessões ainda abertas com duração parcial.
- Tempo de carga é média dos views com `load_time_ms` > 0 na janela.
- Percentual de origem é relativo ao top 8, não ao total de sessões.
- Locais sem cidade não entram na lista. Locais sem coordenada entram na lista e ficam fora do mapa.
- Bot, DNT, preview, path do CMS e POST de outro host não entram na base.
- Não há métrica de visitante único, funil, dispositivo, browser ou OS na interface, embora parte disso esteja persistida.
- Não há exclusão automática de dados antigos.
