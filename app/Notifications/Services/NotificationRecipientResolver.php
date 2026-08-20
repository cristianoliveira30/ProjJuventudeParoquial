<?php

namespace App\Notifications\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class NotificationRecipientResolver
{
    /**
     * @param array{users?:array,roles?:array} $targets
     * @return Collection<int,int>  // user ids únicos
     */
    public function resolve(array $targets): Collection
    {
        $targets = [
            'users' => array_values(array_unique($targets['users'] ?? [])),
            'roles' => array_values(array_unique($targets['roles'] ?? [])),
        ];

        $userIds = collect();

        if (!empty($targets['users'])) {
            $directUserIds = User::query()
                ->where('is_active', true)
                ->whereIn('id', $targets['users'])
                ->pluck('id');

            $userIds = $userIds->merge($directUserIds);
        }

        if (!empty($targets['roles'])) {
            $roleUserIds = User::query()
                ->where('is_active', true)
                ->whereIn('role_id', $targets['roles'])
                ->pluck('id');

            $userIds = $userIds->merge($roleUserIds);
        }

        return $userIds
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();
    }
}
