<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationDeliveries;
use App\Models\Role;
use App\Models\User;
use App\Notifications\Events\NotificationCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $query = NotificationDeliveries::query()
            ->where('user_id', $userId)
            ->with('notification')
            ->orderByDesc('created_at')
            ->limit(50);

        $notifications = $query->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function countUnread(Request $request)
    {
        $userId = $request->user()->id;

        $count = NotificationDeliveries::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'unread_count' => $count,
        ]);
    }

    public function create(Request $request)
    {
        $input = $request->validate([
            'title'   => 'required|string|max:150',
            'message' => 'required|string|max:1000',

            'users' => 'sometimes|array',
            'users.*' => 'integer|exists:users,id',

            'roles' => 'sometimes|array',
            'roles.*' => 'integer|exists:roles,id',

            'destino' => 'required|string|in:todos,filtros',
            'flag'    => 'nullable|string|max:50',
            'color'   => 'nullable|string|max:100',
        ]);

        $targets = [
            'users' => array_values(array_unique($request->input('users', []))),
            'roles' => array_values(array_unique($request->input('roles', []))),
        ];

        if ($request->input('destino') === 'todos') {
            $targets['users'] = User::query()->where('is_active', true)->pluck('id')->all();
            $targets['roles'] = [];
        }

        if (empty($targets['users']) && empty($targets['roles'])) {
            return back()
                ->withErrors(['targets' => 'Selecione ao menos um usuário ou perfil de acesso.'])
                ->withInput();
        }

        try {
            $notification = null;
            $rows = [];

            DB::transaction(function () use ($request, $input, $targets, &$notification, &$rows) {
                // 1) Cria a notificação DENTRO da transaction
                $notification = Notification::create([
                    'title'      => $input['title'],
                    'message'    => $input['message'],
                    'created_by' => $request->user()->id,
                    'flag'       => $input['flag'] ?? null,
                    'color'      => $input['color'] ?? null,
                ]);

                // 2) Monta os targets e persiste
                $now = now();

                foreach ($targets['users'] as $id) {
                    $rows[] = [
                        'notification_id' => (int) $notification->id,
                        'target_type'     => 'users',
                        'target_id'       => (int) $id,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }

                foreach ($targets['roles'] as $id) {
                    $rows[] = [
                        'notification_id' => (int) $notification->id,
                        'target_type'     => 'roles',
                        'target_id'       => (int) $id,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }

                DB::table('notification_targets')->insert($rows);

                // 3) Dispara APÓS COMMIT (garante consistência)
                DB::afterCommit(function () use ($notification, $targets) {
                    event(new NotificationCreated(
                        notificationId: (int) $notification->id,
                        senderId: (int) $notification->created_by,
                        targets: $targets,
                    ));
                });
            });
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withErrors(['error' => 'Erro ao criar notificação. Tente novamente.'])
                ->withInput();
        }

        return back()->with('success', 'Notificação enviada com sucesso.');
    }

    public function dashboard(Request $request)
    {
        $users = User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        $roles = Role::query()
            ->whereIn('sigla', ['ADMIN', 'SEC', 'EDITOR', 'COORD'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('navigation.message.envio', compact('users', 'roles'));
    }

    public function markAsRead(Request $request, $id)
    {
        $userId = $request->user()->id;

        $delivery = NotificationDeliveries::where('notification_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$delivery) {
            return response()->json(['error' => 'Notificação não encontrada para este usuário.'], 404);
        }

        if ($delivery->read_at) {
            return response()->json(['message' => 'Notificação já marcada como lida.']);
        }

        $delivery->read_at = now();
        $delivery->save();

        return response()->json(['message' => 'Notificação marcada como lida com sucesso.']);
    }
}
