<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PruneInactiveUsers extends Command
{
    protected $signature = 'users:prune-inactive {--years=2 : Anos sem login} {--dry-run : Só lista, não exclui}';

    protected $description = 'Exclui usuários (exceto administradores) sem login há mais de 2 anos';

    public function handle(): int
    {
        $cutoff = now()->subYears((int) $this->option('years'));
        $loginTable = config('authentication-log.table_name');

        $users = User::query()
            ->whereDoesntHave('role', fn ($query) => $query->where('sigla', 'ADMIN'))
            ->where('created_at', '<', $cutoff)
            ->whereNotExists(fn ($query) => $query->from($loginTable)
                ->whereColumn('authenticatable_id', 'users.id')
                ->where('authenticatable_type', (new User)->getMorphClass())
                ->where('login_at', '>=', $cutoff))
            ->get();

        $deleted = 0;

        foreach ($users as $user) {
            $this->line("{$user->username} <{$user->email}>");

            if ($this->option('dry-run')) {
                continue;
            }

            try {
                if ($user->profile_photo_url && str_starts_with($user->profile_photo_url, '/storage/')) {
                    Storage::disk('public')->delete(Str::after($user->profile_photo_url, '/storage/'));
                }
                $user->delete();
                $deleted++;
            } catch (Throwable $e) {
                // ex.: usuário que enviou notificações (chave estrangeira); fica para revisão manual
                report($e);
            }
        }

        $this->info($this->option('dry-run') ? "{$users->count()} usuário(s) seriam excluídos." : "{$deleted} de {$users->count()} usuário(s) excluídos.");

        return self::SUCCESS;
    }
}
