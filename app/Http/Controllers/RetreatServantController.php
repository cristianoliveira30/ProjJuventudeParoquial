<?php

namespace App\Http\Controllers;

use App\Models\Retreat;
use App\Models\RetreatServant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RetreatServantController extends Controller
{
    public function storePublic(Request $request, string $token)
    {
        $retreat = Retreat::query()->where('servant_registration_token', $token)->firstOrFail();
        abort_unless($retreat->servantRegistrationStatus() === Retreat::SERVANT_LINK_OPEN, 404);

        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $isMinor = $retreat->isMinorOn($request->input('birth_date'));
        $lastPreviousYear = $retreat->starts_at->year - 1;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'birth_date' => ['required', 'date', 'before:today'],
            'email' => ['required', 'email', 'max:180', Rule::unique('retreat_servants', 'email')->where('retreat_id', $retreat->id)],
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
            'previous_retreat_year' => ['required', 'integer', 'min:1900', 'max:'.$lastPreviousYear],
            'preferred_ministry_id' => [
                'required',
                Rule::exists('retreat_ministries', 'id')->where(fn ($query) => $query
                    ->where('retreat_id', $retreat->id)
                    ->where('is_active', true)),
            ],
            'consent' => ['accepted'],
            'health_data_consent' => ['accepted'],
        ], [
            'email.unique' => 'Já existe uma inscrição com este e-mail para servir neste retiro.',
            'preferred_ministry_id.exists' => 'Selecione um ministério disponível neste retiro.',
            'previous_retreat_year.max' => 'Informe o ano de um retiro realizado antes deste.',
            'consent.accepted' => 'Você precisa autorizar o uso dos dados para concluir a inscrição.',
            'health_data_consent.accepted' => 'Você precisa autorizar o uso restrito das informações de saúde.',
            'guardian_name.required' => 'Informe o nome do responsável legal para menores de 18 anos.',
            'guardian_phone.required' => 'Informe o telefone do responsável legal para menores de 18 anos.',
        ]);

        $retreat->servants()->create([
            ...collect($validated)->except(['consent', 'health_data_consent'])->all(),
            'status' => 'pending',
            'consented_at' => now(),
            'health_data_consented_at' => now(),
        ]);

        return back()->with('servant_registration_success', 'Inscrição recebida. A equipe responsável analisará seus dados e entrará em contato.');
    }

    public function index(Request $request, Retreat $retreat)
    {
        abort_unless($retreat->canBeManagedBy($request->user()), 403, 'Você só pode acessar servos de retiros sob sua responsabilidade.');

        $query = trim((string) $request->input('q'));
        $status = (string) $request->input('status', 'all');
        $ministryId = $request->integer('ministry');

        $servants = $retreat->servants()
            ->with(['preferredMinistry', 'assignedMinistry'])
            ->when($query !== '', fn ($builder) => $builder->where(function ($nested) use ($query) {
                $nested->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('community', 'like', "%{$query}%")
                    ->orWhere('emergency_contact_name', 'like', "%{$query}%");
            }))
            ->when(array_key_exists($status, RetreatServant::STATUSES), fn ($builder) => $builder->where('status', $status))
            ->when($ministryId > 0, fn ($builder) => $builder->where(function ($nested) use ($ministryId) {
                $nested->where('assigned_ministry_id', $ministryId)
                    ->orWhere(fn ($preferred) => $preferred->whereNull('assigned_ministry_id')->where('preferred_ministry_id', $ministryId));
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $ministries = $retreat->ministries()->get();

        return view('retreats.servants', compact('retreat', 'servants', 'ministries', 'query', 'status', 'ministryId'));
    }

    public function update(Request $request, Retreat $retreat, RetreatServant $servant)
    {
        abort_unless($retreat->canBeManagedBy($request->user()), 403, 'Você só pode acessar servos de retiros sob sua responsabilidade.');

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(RetreatServant::STATUSES))],
            'assigned_ministry_id' => [
                Rule::requiredIf($request->input('status') === 'approved'),
                'nullable',
                Rule::exists('retreat_ministries', 'id')->where('retreat_id', $retreat->id),
            ],
            'internal_notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'assigned_ministry_id.required' => 'Defina o ministério antes de aprovar este servo.',
        ]);

        if ($validated['status'] === 'approved') {
            $validated['approved_at'] = $servant->approved_at ?? now();
            $validated['approved_by'] = $servant->approved_by ?? $request->user()->id;
        } else {
            $validated['approved_at'] = null;
            $validated['approved_by'] = null;
        }

        $servant->update($validated);

        return back()->with('success', 'Dados do servo atualizados.');
    }
}
