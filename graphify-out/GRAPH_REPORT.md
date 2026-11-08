# Graph Report - ProjJuventudeParoquial  (2026-11-07)

## Corpus Check
- 266 files · ~52,698 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 64 file(s) not represented in the graph (top: (none) 38, .conf 8, .ini 7)

## Summary
- 1300 nodes · 2100 edges · 183 communities (49 shown, 134 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 57 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Retreat Ministries
- Docker Sail Config
- Time Range Helpers
- DateRange & Periods
- Roles & Permissions
- Portal Public Site
- Xdebug WSL Docs
- Pastoral Groups
- Auth Log Migration
- Auth Controller
- JS Dependencies
- Parish Events
- User Model
- Community 14
- Community 15
- Community 16
- Community 17
- Community 18
- Community 19
- Community 20
- Community 21
- Community 22
- Community 23
- Community 24
- Community 25
- Community 26
- Community 27
- Community 28
- Community 29
- Community 30
- Community 31
- Community 32
- Community 33
- Community 34
- Community 35
- Community 37
- Community 38
- Community 39
- Community 40
- Community 41
- Community 42
- Community 43
- Community 44
- Community 45
- Community 46
- Community 47
- Community 48
- Community 49
- Community 50
- Community 51
- Community 52
- Community 53
- Community 54
- Community 55
- Community 56
- Community 57
- Community 58
- Community 59
- Community 60
- Community 61
- Community 62
- Community 63
- Community 64
- Community 65
- Community 66
- Community 67
- Community 68
- Community 69
- Community 71
- Community 72
- Community 73
- Community 74
- Community 75
- Community 76
- Community 77
- Community 78
- Community 79
- Community 80
- Community 81
- Community 82
- Community 83
- Community 84
- Community 85
- Community 86
- Community 87
- Community 88
- Community 90
- Community 92
- Community 101
- Community 102
- Community 103
- Community 104
- Community 105
- Community 106
- Community 107
- Community 108
- Community 109
- Community 110

## God Nodes (most connected - your core abstractions)
1. `Retreat` - 67 edges
2. `User` - 59 edges
3. `GalleryAlbum` - 26 edges
4. `PastoralGroup` - 23 edges
5. `UserController` - 20 edges
6. `ParishEvent` - 20 edges
7. `Post` - 19 edges
8. `Role` - 19 edges
9. `PastoralGroupController` - 18 edges
10. `RetreatMinistry` - 18 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --references--> `NotificationSent`  [EXTRACTED]
  tests/Feature/Notifications/CreateNotificationDeliveriesTest.php → app/Events/NotificationSent.php
- `{closure#4}()` --calls--> `User`  [EXTRACTED]
  tests/Feature/Auth/FirstAccessPasswordTest.php → app/Models/User.php
- `{closure#5}()` --calls--> `User`  [EXTRACTED]
  tests/Feature/Auth/FirstAccessPasswordTest.php → app/Models/User.php
- `createFirstAccessUser()` --references--> `User`  [EXTRACTED]
  tests/Feature/Auth/FirstAccessPasswordTest.php → app/Models/User.php
- `{closure#1}()` --calls--> `User`  [EXTRACTED]
  tests/Feature/Notifications/NotificationControllerTest.php → app/Models/User.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Hostinger deploy flow** — docs_hostinger_deploy_branch_deploy, docs_hostinger_deploy_deploy_key, docs_hostinger_deploy_setup_inicial, docs_hostinger_deploy_deploy_cada_push [EXTRACTED 0.95]
- **Legacy Docker CI/CD pipeline** — docs_github_actions_workflow_ci_cd_yml, docs_github_actions_workflow_test_gate, docs_github_actions_workflow_trivy, docs_github_actions_workflow_ssh_deploy_tags [EXTRACTED 0.95]
- **Xdebug WSL setup** — docs_debug_vscode_listen, docs_debug_tcp_test, docs_debug_extra_hosts_fix, docs_debug_xdebug_config [EXTRACTED 0.95]

## Communities (183 total, 134 thin omitted)

### Community 0 - "Retreat Ministries"
Cohesion: 0.08
Nodes (19): RetreatMinistryController, RetreatRegistrationController, RetreatServantController, RetreatServantLinkController, Retreat, RetreatMinistry, {closure#1}(), {closure#1}() (+11 more)

### Community 1 - "Docker Sail Config"
Cohesion: 0.04
Nodes (47): WWWGROUP, args, context, dockerfile, IGNITION_LOCAL_SITES_PATH, LARAVEL_SAIL, MYSQL_ALLOW_EMPTY_PASSWORD, MYSQL_DATABASE (+39 more)

### Community 2 - "Time Range Helpers"
Cohesion: 0.05
Nodes (7): safe_route(), TimeRangeRequest, LiturgiaService, GET /liturgia-diaria(), GET /testing/time-range-request(), {closure#1}(), {closure#2}()

### Community 3 - "DateRange & Periods"
Cohesion: 0.08
Nodes (22): DateRange, PeriodKey, HOJE, MES_ATUAL, MES_PASSADO, ONTEM, PERIODO, SEMANA_ATUAL (+14 more)

### Community 4 - "Roles & Permissions"
Cohesion: 0.08
Nodes (21): {closure#1}(), RoleController, Role, DatabaseSeeder, ParishAccessSeeder, {closure#1}(), {closure#10}(), {closure#14}() (+13 more)

### Community 5 - "Portal Public Site"
Cohesion: 0.07
Nodes (3): PortalController, PostController, Post

### Community 7 - "Xdebug WSL Docs"
Cohesion: 0.07
Nodes (33): entrypoint.sh script, Fix extra_hosts host.docker.internal para IPv4 do WSL, Teste TCP container para VSCode, VSCode PHP Debug listener launch.json, Configuracao Xdebug (SAIL_XDEBUG_MODE/CONFIG), Xdebug com Sail no WSL (porta 9003), Deploy rapido na Hostinger (scripts), scripts/deploy-hostinger.sh e Deploy-Hostinger.ps1 (+25 more)

### Community 8 - "Pastoral Groups"
Cohesion: 0.13
Nodes (5): {closure#1}(), {closure#4}(), {closure#5}(), PastoralGroupController, PastoralGroup

### Community 9 - "Auth Log Migration"
Cohesion: 0.11
Nodes (18): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#1}() (+10 more)

### Community 10 - "Auth Controller"
Cohesion: 0.12
Nodes (5): AuthController, Controller, NotificationController, WebPushController, GET /user()

### Community 11 - "JS Dependencies"
Cohesion: 0.11
Nodes (26): dependencies, ag-grid-community@^35.1.0, apexcharts@^5.3.6, bootstrap-icons@^1.13.1, dexie@^4.2.1, flowbite@^4.0.1, jspdf@^4.0.0, jspdf-autotable@^5.0.2 (+18 more)

### Community 13 - "User Model"
Cohesion: 0.13
Nodes (3): User, {closure#1}(), {closure#2}()

### Community 15 - "Community 15"
Cohesion: 0.18
Nodes (4): EnsureFirstAccessPassword, EnsureUserIsActive, NoStoreHtml, TrackPageAccess

### Community 16 - "Community 16"
Cohesion: 0.11
Nodes (17): runs-on, steps, jobs, build, name, on, push, permissions (+9 more)

### Community 18 - "Community 18"
Cohesion: 0.21
Nodes (3): NotificationSent, QueueWebPushOnNotificationSent, {closure#1}()

### Community 19 - "Community 19"
Cohesion: 0.19
Nodes (6): {closure#2}(), Notification, NotificationDeliveries, {closure#1}(), {closure#2}(), {closure#3}()

### Community 22 - "Community 22"
Cohesion: 0.14
Nodes (13): networks, app-network, name, command, image, networks, restart, volumes (+5 more)

### Community 23 - "Community 23"
Cohesion: 0.26
Nodes (11): initHeaderInteractions(), initNotifications(), initPasswordToggles(), initProfilePreview(), initSettingsModal(), initSettingsSubmit(), setButtonLoading(), setupToggle() (+3 more)

### Community 24 - "Community 24"
Cohesion: 0.24
Nodes (11): isBlack(), isDark(), normalizeError(), themeOptions(), useAlerts(), confirm(), fireToast(), loading() (+3 more)

### Community 25 - "Community 25"
Cohesion: 0.15
Nodes (13): require, davidhsianturi/blade-bootstrap-icons, laravel/framework, laravel-notification-channels/webpush, laravel/reverb, laravel/sanctum, laravel/tinker, minishlink/web-push (+5 more)

### Community 28 - "Community 28"
Cohesion: 0.18
Nodes (4): AppServiceProvider, {closure#1}(), {closure#2}(), PermissionServiceProvider

### Community 30 - "Community 30"
Cohesion: 0.17
Nodes (11): description, extra, laravel, keywords, dont-discover, license, minimum-stability, name (+3 more)

### Community 31 - "Community 31"
Cohesion: 0.17
Nodes (12): build, depends_on, expose, image, networks, restart, volumes, context (+4 more)

### Community 32 - "Community 32"
Cohesion: 0.21
Nodes (4): {closure#1}(), {closure#2}(), servantLinkAdmin(), servantLinkRetreat()

### Community 33 - "Community 33"
Cohesion: 0.20
Nodes (11): devDependencies, concurrently@^9.0.1, laravel-vite-plugin@^2.0.0, tailwindcss@^4.1.18, @tailwindcss/vite@^4.1.18, vite@^7.0.7, concurrently, laravel-vite-plugin (+3 more)

### Community 37 - "Community 37"
Cohesion: 0.18
Nodes (11): condition, app, depends_on, image, networks, ports, restart, volumes (+3 more)

### Community 43 - "Community 43"
Cohesion: 0.27
Nodes (4): NotificationCreated, {closure#1}(), {closure#2}(), {closure#1}()

### Community 44 - "Community 44"
Cohesion: 0.20
Nodes (10): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision (+2 more)

### Community 45 - "Community 45"
Cohesion: 0.27
Nodes (4): axios@^1.13.2, axios, api, ensureFlowbite()

### Community 47 - "Community 47"
Cohesion: 0.28
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 48 - "Community 48"
Cohesion: 0.22
Nodes (9): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, platform, preferred-install, sort-packages (+1 more)

### Community 49 - "Community 49"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 50 - "Community 50"
Cohesion: 0.28
Nodes (8): environment, DB_HOST, environment, environment, services, app, queue, scheduler

### Community 51 - "Community 51"
Cohesion: 0.28
Nodes (7): laravel-echo@^2.3.0, pusher-js@^8.4.0, laravel-echo, pusher-js, ensureEmptyStateRemoved(), openNotificationsModal(), prependNotificationToList()

### Community 54 - "Community 54"
Cohesion: 0.46
Nodes (8): healthcheck, interval, retries, start_period, test, timeout, healthcheck, healthcheck

### Community 55 - "Community 55"
Cohesion: 0.43
Nodes (6): content, date, escapeHtml(), reading(), render(), skeleton

### Community 56 - "Community 56"
Cohesion: 0.29
Nodes (4): {closure#2}(), {closure#4}(), {closure#5}(), createFirstAccessUser()

### Community 59 - "Community 59"
Cohesion: 0.33
Nodes (6): autoload, files, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 60 - "Community 60"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 61 - "Community 61"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 62 - "Community 62"
Cohesion: 0.53
Nodes (6): env_file, path, required, env_file, env_file, env_file

### Community 63 - "Community 63"
Cohesion: 0.33
Nodes (6): entrypoint, image, networks, restart, volumes, mysql

### Community 64 - "Community 64"
Cohesion: 0.33
Nodes (6): command, image, networks, restart, volumes, queue

### Community 65 - "Community 65"
Cohesion: 0.33
Nodes (5): MYSQL_DATABASE, MYSQL_PASSWORD, MYSQL_ROOT_PASSWORD, MYSQL_USER, production-entrypoint.sh script

### Community 66 - "Community 66"
Cohesion: 0.33
Nodes (6): scripts, build, clear-vite-hot, dev, prebuild, predev

### Community 68 - "Community 68"
Cohesion: 0.60
Nodes (5): print_error(), print_header(), print_info(), print_success(), testar-push.sh script

### Community 69 - "Community 69"
Cohesion: 0.47
Nodes (3): {closure#1}(), {closure#2}(), createLoginUser()

### Community 78 - "Community 78"
Cohesion: 0.50
Nodes (3): components.layouts.app.footer, components.layouts.app.header, components.layouts.app.sidebar

### Community 92 - "Community 92"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

## Knowledge Gaps
- **35 isolated node(s):** `HOJE`, `ONTEM`, `SEMANA_ATUAL`, `SEMANA_PASSADA`, `MES_ATUAL` (+30 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 558 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **134 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User Model` to `Community 32`, `Retreat Ministries`, `Roles & Permissions`, `Community 69`, `Auth & Retreat Registration`, `Community 39`, `Pastoral Groups`, `Community 40`, `Community 43`, `Community 77`, `Community 46`, `Community 17`, `Community 19`, `Community 53`, `Community 56`, `Community 28`?**
  _High betweenness centrality (0.076) - this node is a cross-community bridge._
- **What connects `HOJE`, `ONTEM`, `SEMANA_ATUAL` to the rest of the system?**
  _35 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Retreat Ministries` be split into smaller, more focused modules?**
  _Cohesion score 0.08158508158508158 - nodes in this community are weakly interconnected._
- **Why does `Retreat` connect `Retreat Ministries` to `Community 32`, `Community 34`, `Portal Public Site`, `Auth & Retreat Registration`, `Community 38`, `Auth Log Migration`, `Community 41`, `Community 46`, `Community 20`, `Community 21`?**
  _High betweenness centrality (0.049) - this node is a cross-community bridge._
- **Should `Docker Sail Config` be split into smaller, more focused modules?**
  _Cohesion score 0.041666666666666664 - nodes in this community are weakly interconnected._
- **Why does `ParishEvent` connect `Parish Events` to `Auth Log Migration`, `Community 20`, `Portal Public Site`, `Community 38`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Should `Time Range Helpers` be split into smaller, more focused modules?**
  _Cohesion score 0.05410628019323672 - nodes in this community are weakly interconnected._