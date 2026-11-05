# Graph Report - ProjJuventudeParoquial  (2026-11-05)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 1268 nodes · 2064 edges · 176 communities (47 shown, 129 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 53 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3086622a`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Community 0
- Community 1
- Community 3
- Community 4
- Community 5
- Community 6
- Community 7
- Community 8
- Community 9
- Community 10
- Community 11
- Community 12
- Community 13
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
- Community 36
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
- Community 64
- Community 65
- Community 66
- Community 67
- Community 68
- Community 69
- Community 70
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
- Community 82
- Community 84
- Community 93
- Community 94
- Community 95
- Community 96
- Community 97
- Community 98
- Community 99
- Community 100
- Community 101
- Community 102
- Community 103

## God Nodes (most connected - your core abstractions)
1. `Retreat` - 67 edges
2. `User` - 59 edges
3. `GalleryAlbum` - 26 edges
4. `PastoralGroup` - 23 edges
5. `UserController` - 20 edges
6. `ParishEvent` - 20 edges
7. `Post` - 19 edges
8. `Role` - 19 edges
9. `RetreatMinistry` - 18 edges
10. `PastoralGroupController` - 18 edges

## Surprising Connections (you probably didn't know these)
- `{closure#4}()` --calls--> `User`  [EXTRACTED]
  tests/Feature/Auth/FirstAccessPasswordTest.php → app/Models/User.php
- `{closure#5}()` --calls--> `User`  [EXTRACTED]
  tests/Feature/Auth/FirstAccessPasswordTest.php → app/Models/User.php
- `createFirstAccessUser()` --references--> `User`  [EXTRACTED]
  tests/Feature/Auth/FirstAccessPasswordTest.php → app/Models/User.php
- `servantLinkRetreat()` --references--> `Retreat`  [EXTRACTED]
  tests/Feature/RetreatServantLinkTest.php → app/Models/Retreat.php
- `{closure#2}()` --references--> `NotificationSent`  [EXTRACTED]
  tests/Feature/Notifications/CreateNotificationDeliveriesTest.php → app/Events/NotificationSent.php

## Import Cycles
- None detected.

## Communities (176 total, 129 thin omitted)

### Community 0 - "Community 0"
Cohesion: 0.08
Nodes (19): RetreatMinistryController, RetreatRegistrationController, RetreatServantController, RetreatServantLinkController, Retreat, RetreatMinistry, {closure#1}(), {closure#1}() (+11 more)

### Community 1 - "Community 1"
Cohesion: 0.07
Nodes (15): NotificationSent, {closure#2}(), CreateNotificationDeliveries, QueueWebPushOnNotificationSent, Notification, NotificationDeliveries, NotificationCreated, NotificationRecipientResolver (+7 more)

### Community 3 - "Community 3"
Cohesion: 0.04
Nodes (47): WWWGROUP, args, context, dockerfile, IGNITION_LOCAL_SITES_PATH, LARAVEL_SAIL, MYSQL_ALLOW_EMPTY_PASSWORD, MYSQL_DATABASE (+39 more)

### Community 4 - "Community 4"
Cohesion: 0.07
Nodes (22): {closure#1}(), RoleController, Permission, Role, DatabaseSeeder, ParishAccessSeeder, {closure#1}(), {closure#10}() (+14 more)

### Community 5 - "Community 5"
Cohesion: 0.08
Nodes (22): DateRange, PeriodKey, HOJE, MES_ATUAL, MES_PASSADO, ONTEM, PERIODO, SEMANA_ATUAL (+14 more)

### Community 6 - "Community 6"
Cohesion: 0.08
Nodes (3): ParishEventController, PortalController, ParishEvent

### Community 7 - "Community 7"
Cohesion: 0.09
Nodes (4): SendWebPushForUser, EmailVerificationCode, LiturgiaService, GET /liturgia-diaria()

### Community 8 - "Community 8"
Cohesion: 0.13
Nodes (5): {closure#1}(), {closure#4}(), {closure#5}(), PastoralGroupController, PastoralGroup

### Community 9 - "Community 9"
Cohesion: 0.07
Nodes (5): safe_route(), TimeRangeRequest, GET /testing/time-range-request(), {closure#1}(), {closure#2}()

### Community 10 - "Community 10"
Cohesion: 0.11
Nodes (18): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#1}() (+10 more)

### Community 11 - "Community 11"
Cohesion: 0.10
Nodes (5): User, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}()

### Community 12 - "Community 12"
Cohesion: 0.12
Nodes (5): AuthController, Controller, NotificationController, WebPushController, GET /user()

### Community 13 - "Community 13"
Cohesion: 0.11
Nodes (26): dependencies, ag-grid-community@^35.1.0, apexcharts@^5.3.6, bootstrap-icons@^1.13.1, dexie@^4.2.1, flowbite@^4.0.1, jspdf@^4.0.0, jspdf-autotable@^5.0.2 (+18 more)

### Community 16 - "Community 16"
Cohesion: 0.18
Nodes (4): EnsureFirstAccessPassword, EnsureUserIsActive, NoStoreHtml, TrackPageAccess

### Community 17 - "Community 17"
Cohesion: 0.11
Nodes (17): runs-on, steps, jobs, build, name, on, push, permissions (+9 more)

### Community 19 - "Community 19"
Cohesion: 0.15
Nodes (5): UserFactory, {closure#2}(), {closure#4}(), {closure#5}(), createFirstAccessUser()

### Community 21 - "Community 21"
Cohesion: 0.14
Nodes (13): networks, app-network, name, command, image, networks, restart, volumes (+5 more)

### Community 22 - "Community 22"
Cohesion: 0.26
Nodes (11): initHeaderInteractions(), initNotifications(), initPasswordToggles(), initProfilePreview(), initSettingsModal(), initSettingsSubmit(), setButtonLoading(), setupToggle() (+3 more)

### Community 23 - "Community 23"
Cohesion: 0.24
Nodes (11): isBlack(), isDark(), normalizeError(), themeOptions(), useAlerts(), confirm(), fireToast(), loading() (+3 more)

### Community 24 - "Community 24"
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

### Community 36 - "Community 36"
Cohesion: 0.18
Nodes (11): condition, app, depends_on, image, networks, ports, restart, volumes (+3 more)

### Community 40 - "Community 40"
Cohesion: 0.20
Nodes (10): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision (+2 more)

### Community 41 - "Community 41"
Cohesion: 0.27
Nodes (4): axios@^1.13.2, axios, api, ensureFlowbite()

### Community 43 - "Community 43"
Cohesion: 0.28
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "Community 44"
Cohesion: 0.22
Nodes (9): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, platform, preferred-install, sort-packages (+1 more)

### Community 45 - "Community 45"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 46 - "Community 46"
Cohesion: 0.28
Nodes (8): environment, DB_HOST, environment, environment, services, app, queue, scheduler

### Community 47 - "Community 47"
Cohesion: 0.28
Nodes (7): laravel-echo@^2.3.0, pusher-js@^8.4.0, laravel-echo, pusher-js, ensureEmptyStateRemoved(), openNotificationsModal(), prependNotificationToList()

### Community 48 - "Community 48"
Cohesion: 0.46
Nodes (8): healthcheck, interval, retries, start_period, test, timeout, healthcheck, healthcheck

### Community 49 - "Community 49"
Cohesion: 0.43
Nodes (6): content, date, escapeHtml(), reading(), render(), skeleton

### Community 52 - "Community 52"
Cohesion: 0.33
Nodes (6): autoload, files, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 53 - "Community 53"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 54 - "Community 54"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 55 - "Community 55"
Cohesion: 0.53
Nodes (6): env_file, path, required, env_file, env_file, env_file

### Community 56 - "Community 56"
Cohesion: 0.33
Nodes (6): entrypoint, image, networks, restart, volumes, mysql

### Community 57 - "Community 57"
Cohesion: 0.33
Nodes (6): command, image, networks, restart, volumes, queue

### Community 58 - "Community 58"
Cohesion: 0.33
Nodes (5): MYSQL_DATABASE, MYSQL_PASSWORD, MYSQL_ROOT_PASSWORD, MYSQL_USER, production-entrypoint.sh script

### Community 59 - "Community 59"
Cohesion: 0.33
Nodes (6): scripts, build, clear-vite-hot, dev, prebuild, predev

### Community 61 - "Community 61"
Cohesion: 0.60
Nodes (5): print_error(), print_header(), print_info(), print_success(), testar-push.sh script

### Community 62 - "Community 62"
Cohesion: 0.47
Nodes (3): {closure#1}(), {closure#2}(), createLoginUser()

### Community 70 - "Community 70"
Cohesion: 0.50
Nodes (3): components.layouts.app.footer, components.layouts.app.header, components.layouts.app.sidebar

### Community 84 - "Community 84"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

## Knowledge Gaps
- **22 isolated node(s):** `errors::minimal`, `errors::minimal`, `errors::minimal`, `errors::minimal`, `HOJE` (+17 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 546 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **129 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `Community 11` to `Community 32`, `Community 1`, `Community 2`, `Community 0`, `Community 4`, `Community 7`, `Community 8`, `Community 42`, `Community 18`, `Community 19`, `Community 28`, `Community 62`?**
  _High betweenness centrality (0.081) - this node is a cross-community bridge._
- **What connects `errors::minimal`, `errors::minimal`, `errors::minimal` to the rest of the system?**
  _22 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Community 0` be split into smaller, more focused modules?**
  _Cohesion score 0.07960199004975124 - nodes in this community are weakly interconnected._
- **Why does `Retreat` connect `Community 0` to `Community 32`, `Community 2`, `Community 34`, `Community 37`, `Community 6`, `Community 38`, `Community 42`, `Community 10`, `Community 20`, `Community 26`?**
  _High betweenness centrality (0.066) - this node is a cross-community bridge._
- **Should `Community 1` be split into smaller, more focused modules?**
  _Cohesion score 0.06693877551020408 - nodes in this community are weakly interconnected._
- **Why does `Post` connect `Community 14` to `Community 10`, `Community 34`, `Community 11`, `Community 6`?**
  _High betweenness centrality (0.015) - this node is a cross-community bridge._
- **Should `Community 2` be split into smaller, more focused modules?**
  _Cohesion score 0.044326241134751775 - nodes in this community are weakly interconnected._