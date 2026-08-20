<?php

namespace App\Http\Controllers;

use App\Models\Retreat;
use App\Models\RetreatRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RetreatRegistrationController extends Controller
{
    public function storePublic(Request $request, Retreat $retreat)
    {
        abort_unless($retreat->isRegistrationOpen(), 404);
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $isMinor = $this->isMinor($request->input('birth_date'), $retreat);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'birth_date' => ['required', 'date', 'before:today'],
            'email' => ['required', 'email', 'max:180', Rule::unique('retreat_registrations', 'email')->where('retreat_id', $retreat->id)],
            'phone' => ['required', 'string', 'max:40'],
            'community' => ['nullable', 'string', 'max:180'],
            'mother_name' => ['nullable', 'string', 'max:180'],
            'father_name' => ['nullable', 'string', 'max:180'],
            'guardian_name' => [Rule::requiredIf($isMinor), 'nullable', 'string', 'max:180'],
            'guardian_phone' => [Rule::requiredIf($isMinor), 'nullable', 'string', 'max:40'],
            'emergency_contact_name' => ['required', 'string', 'max:180'],
            'emergency_contact_phone' => ['required', 'string', 'max:40'],
            'allergies' => ['nullable', 'string', 'max:2000'],
            'medical_conditions' => ['nullable', 'string', 'max:2000'],
            'medications' => ['nullable', 'string', 'max:2000'],
            'dietary_restrictions' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
            'health_data_consent' => ['accepted'],
        ], [
            'email.unique' => 'Já existe uma inscrição com este e-mail para o retiro.',
            'consent.accepted' => 'Você precisa autorizar o uso dos dados para concluir a inscrição.',
            'health_data_consent.accepted' => 'Você precisa autorizar o uso restrito das informações de saúde.',
            'guardian_name.required' => 'Informe o nome do responsável legal para participantes menores de 18 anos.',
            'guardian_phone.required' => 'Informe o telefone do responsável legal para participantes menores de 18 anos.',
        ]);

        $status = DB::transaction(function () use ($retreat, $validated) {
            $lockedRetreat = Retreat::query()->lockForUpdate()->findOrFail($retreat->id);
            $occupied = $lockedRetreat->registrations()->whereIn('status', ['pending', 'confirmed'])->count();
            $status = $lockedRetreat->capacity && $occupied >= $lockedRetreat->capacity ? 'waitlist' : 'pending';

            $lockedRetreat->registrations()->create([
                ...collect($validated)->except(['consent', 'health_data_consent'])->all(),
                'email' => strtolower($validated['email']),
                'status' => $status,
                'consented_at' => now(),
                'health_data_consented_at' => now(),
            ]);

            return $status;
        });

        return back()->with('registration_success', $status === 'waitlist'
            ? 'Inscrição recebida. No momento você está na lista de espera.'
            : 'Inscrição recebida com sucesso. A equipe entrará em contato com as próximas orientações.');
    }

    public function index(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);
        $query = trim((string) $request->input('q'));
        $status = $request->input('status', 'all');

        $registrations = $retreat->registrations()
            ->when($query !== '', fn ($builder) => $builder->where(function ($nested) use ($query) {
                $nested->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('guardian_name', 'like', "%{$query}%")
                    ->orWhere('guardian_phone', 'like', "%{$query}%")
                    ->orWhere('mother_name', 'like', "%{$query}%")
                    ->orWhere('father_name', 'like', "%{$query}%");
            }))
            ->when(array_key_exists($status, RetreatRegistration::STATUSES), fn ($builder) => $builder->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('retreats.registrations', compact('retreat', 'registrations', 'query', 'status'));
    }

    public function update(Request $request, Retreat $retreat, RetreatRegistration $registration)
    {
        $this->ensureCanManage($request, $retreat);
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(RetreatRegistration::STATUSES))],
            'internal_notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $registration->update($validated);

        return back()->with('success', 'Situação da inscrição atualizada.');
    }

    private function ensureCanManage(Request $request, Retreat $retreat): void
    {
        abort_if(
            $request->user()->role?->sigla === 'COORD' && $retreat->coordinator_id !== $request->user()->id,
            403,
            'Você só pode acessar inscrições de retiros sob sua responsabilidade.'
        );
    }

    private function isMinor(mixed $birthDate, Retreat $retreat): bool
    {
        try {
            return Carbon::createFromFormat('Y-m-d', (string) $birthDate)
                ->diffInYears($retreat->starts_at) < 18;
        } catch (\Throwable) {
            return false;
        }
    }
}
