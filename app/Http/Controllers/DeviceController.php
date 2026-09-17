<?php

namespace App\Http\Controllers;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use App\Models\Manufacturer;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DeviceController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Device::class);

        $devices = Device::query()
            ->whereHas('patient', fn ($query) => $query->visibleTo($request->user()))
            ->with(['patient', 'manufacturer'])
            ->when($request->query('search'), function ($query, string $term) {
                $query->where(function ($query) use ($term) {
                    $query->whereLike('serial_number', "%{$term}%")
                        ->orWhereLike('model', "%{$term}%")
                        ->orWhereHas('patient', fn ($query) => $query->whereLike('name', "%{$term}%"));
                });
            })
            ->latest('implanted_at')
            ->paginate(15)
            ->withQueryString();

        return view('devices.index', ['devices' => $devices]);
    }

    public function create(Patient $patient): View
    {
        Gate::authorize('create', [Device::class, $patient]);

        return view('devices.create', [
            'device' => new Device,
            'patient' => $patient,
            'manufacturers' => Manufacturer::query()->orderBy('name')->get(),
            'types' => DeviceType::cases(),
            'statuses' => DeviceStatus::cases(),
        ]);
    }

    public function store(StoreDeviceRequest $request, Patient $patient): RedirectResponse
    {
        $device = $patient->devices()->create($request->validated());

        return redirect()
            ->route('devices.show', $device)
            ->with('success', 'Dispositivo cadastrado.');
    }

    public function show(Device $device): View
    {
        Gate::authorize('view', $device);

        $device->load(['patient.doctor', 'manufacturer', 'latestCheckup.doctor']);

        return view('devices.show', ['device' => $device]);
    }

    public function edit(Device $device): View
    {
        Gate::authorize('update', $device);

        return view('devices.edit', [
            'device' => $device,
            'manufacturers' => Manufacturer::query()->orderBy('name')->get(),
            'types' => DeviceType::cases(),
            'statuses' => DeviceStatus::cases(),
        ]);
    }

    public function update(UpdateDeviceRequest $request, Device $device): RedirectResponse
    {
        $device->update($request->validated());

        return redirect()
            ->route('devices.show', $device)
            ->with('success', 'Dispositivo atualizado.');
    }

    public function destroy(Device $device): RedirectResponse
    {
        Gate::authorize('delete', $device);

        $patient = $device->patient;

        $device->delete();

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Dispositivo removido.');
    }
}
