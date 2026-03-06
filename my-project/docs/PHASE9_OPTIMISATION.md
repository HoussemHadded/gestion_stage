# Phase 9 – Optimisation – Documentation

## Tableaux récapitulatifs

### Controllers et cache

| Controller | Cache | Clés | Invalidation |
|------------|-------|------|--------------|
| OffreController | index, create, edit | offres_list_page_{n}, entreprises_list | store, update, destroy |
| UserController | index | users_list_page_{n} | store, update, destroy |
| CandidatureController | index, create, edit | candidatures_list_page_{n}_statut_{x}, offres_all_list, students_list | store, update, destroy, updateStatut |
| AdminDashboardController | index | admin_dashboard_stats | via autres controllers |

### Notifications et queue

| Notification | ShouldQueue | Envoi |
|--------------|-------------|-------|
| NouvelleCandidatureNotification | ✅ | notify() → queue |
| CandidatureAcceptéeNotification | ✅ | notify() → queue |
| CandidatureRefuséeNotification | ✅ | notify() → queue |

---

## 1. Cache ciblé

### Service de cache

Le fichier `app/Services/CacheService.php` gère l'invalidation ciblée du cache (remplacement de `Cache::flush()`).

| Méthode | Clés invalidées |
|---------|-----------------|
| `CacheService::forgetOffres()` | `offres_list_page_{1..50}`, `offres_all_list`, `admin_dashboard_stats` |
| `CacheService::forgetUsers()` | `users_list_page_{1..50}`, `entreprises_list`, `students_list`, `admin_dashboard_stats` |
| `CacheService::forgetCandidatures()` | `candidatures_list_page_{1..50}_statut_{all,en_attente,accepte,refuse}`, `admin_dashboard_stats` |
| `CacheService::forgetFormLists()` | `entreprises_list`, `offres_all_list`, `students_list` |
| `CacheService::forgetAdminDashboard()` | `admin_dashboard_stats` |

### Controllers et cache

| Controller | Méthode | Clé(s) cache | Invalidation |
|------------|---------|--------------|--------------|
| OffreController | index | `offres_list_page_{n}` | store, update, destroy → forgetOffres |
| OffreController | create, edit | `entreprises_list` | store, update, destroy → forgetOffres |
| UserController | index | `users_list_page_{n}` | store, update, destroy → forgetUsers |
| CandidatureController | index | `candidatures_list_page_{n}_statut_{x}` | store, update, destroy, updateStatut → forgetCandidatures |
| CandidatureController | create, edit | `offres_all_list`, `students_list` | idem |
| AdminDashboardController | index | `admin_dashboard_stats` | via forgetOffres/forgetUsers/forgetCandidatures |

---

## 2. Queue pour les notifications

### Notifications avec ShouldQueue

| Notification | ShouldQueue | Envoi |
|--------------|-------------|-------|
| NouvelleCandidatureNotification | ✅ | `$notifiable->notify()` → mise en file automatique |
| CandidatureAcceptéeNotification | ✅ | `$notifiable->notify()` → mise en file automatique |
| CandidatureRefuséeNotification | ✅ | `$notifiable->notify()` → mise en file automatique |

### Configuration

- `.env` : `QUEUE_CONNECTION=database`
- Table `jobs` : stockage des jobs
- Table `failed_jobs` : jobs échoués

---

## 3. Commandes Artisan

### Migrations

| Commande | Description |
|----------|-------------|
| `php artisan db:check` | Statut des migrations (par défaut) |
| `php artisan db:check status` | Statut des migrations |
| `php artisan db:check rollback` | Rollback du dernier batch (avec confirmation) |
| `php artisan db:check fresh` | Recréer toutes les tables (avec confirmation) |
| `php artisan db:check fresh --seed` | Recréer + seed (avec confirmation) |
| `php artisan migrate:status` | Statut des migrations |
| `php artisan migrate:rollback` | Rollback |
| `php artisan migrate:fresh` | Recréer toutes les tables |
| `php artisan migrate:fresh --seed` | Recréer + seed |

### Queue

| Commande | Description |
|----------|-------------|
| `php artisan queue:work` | Traiter les jobs (une fois par exécution) |
| `php artisan queue:listen` | Traiter les jobs en continu (recharge le code) |
| `php artisan queue:failed` | Lister les jobs échoués |
| `php artisan queue:retry all` | Réessayer tous les jobs échoués |
| `php artisan queue:flush` | Supprimer tous les jobs échoués |

### Cache

| Commande | Description |
|----------|-------------|
| `php artisan cache:clear` | Vider tout le cache |

---

## 4. Instructions pour la production

### 1. Variables d'environnement

```env
QUEUE_CONNECTION=database
CACHE_STORE=database
```

### 2. Worker de queue

Lancer le worker en arrière-plan :

```bash
php artisan queue:work
```

Ou en continu (avec rechargement du code) :

```bash
php artisan queue:listen
```

### 3. Supervisord (exemple)

```ini
[program:gestion-stage-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /chemin/vers/votre/projet/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/chemin/vers/votre/projet/storage/logs/worker.log
```

### 4. Cron (optionnel)

```cron
* * * * * cd /chemin/vers/votre/projet && php artisan schedule:run >> /dev/null 2>&1
```

### 5. Vérifications

- `php artisan migrate:status` : toutes les migrations exécutées
- `php artisan queue:work` : worker lancé pour traiter les emails
- `php artisan cache:clear` : si besoin de vider le cache manuellement
