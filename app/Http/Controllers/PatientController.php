<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Patient::class);

        $patients = Patient::query()
            ->visibleTo($request->user())
            ->with('doctor')
            ->search($request->query('search'))
            ->withCount('devices')
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('patients.index', ['patients' => $patients]);
    }

    public function create(): View
    {
        Gate::authorize('create', Patient::class);

        return view('patients.create', [
            'patient' => new Patient,
            'doctors' => User::query()->withRole(Role::Doctor)->orderBy('name')->get(),
            'patientAccounts' => $this->availablePatientAccounts(new Patient),
        ]);
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        $patient = Patient::create($request->validated());

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Paciente cadastrado.');
    }

    public function show(Patient $patient): View
    {
        Gate::authorize('view', $patient);

        $patient->load(['doctor', 'devices.manufacturer', 'devices.latestCheckup']);

        return view('patients.show', ['patient' => $patient]);
    }

    public function edit(Patient $patient): View
    {
        Gate::authorize('update', $patient);

        return view('patients.edit', [
            'patient' => $patient,
            'doctors' => User::query()->withRole(Role::Doctor)->orderBy('name')->get(),
            'patientAccounts' => $this->availablePatientAccounts($patient),
        ]);
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Paciente atualizado.');
    }

    private function availablePatientAccounts(Patient $patient): Collection
    {
        return User::query()
            ->withRole(Role::Patient)
            ->where(function (Builder $query) use ($patient) {
                $query->whereDoesntHave('patientProfile')
                    ->orWhere('id', $patient->user_id);
            })
            ->orderBy('name')
            ->get();
    }
}
