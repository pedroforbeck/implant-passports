<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckupRequest;
use App\Http\Requests\UpdateCheckupRequest;
use App\Models\Checkup;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CheckupController extends Controller
{
    public function index(Device $device): View
    {
        Gate::authorize('view', $device);

        $checkups = $device->checkups()
            ->with('doctor')
            ->latest('checked_at')
            ->paginate(15);

        return view('checkups.index', [
            'device' => $device,
            'checkups' => $checkups,
        ]);
    }

    public function create(Device $device): View
    {
        Gate::authorize('create', [Checkup::class, $device]);

        return view('checkups.create', [
            'checkup' => new Checkup,
            'device' => $device,
        ]);
    }

    public function store(StoreCheckupRequest $request, Device $device): RedirectResponse
    {
        $device->checkups()->create([
            ...$request->validated(),
            'doctor_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('devices.checkups.index', $device)
            ->with('success', 'Avaliação registrada.');
    }

    public function edit(Device $device, Checkup $checkup): View
    {
        Gate::authorize('update', $checkup);

        return view('checkups.edit', [
            'checkup' => $checkup,
            'device' => $device,
        ]);
    }

    public function update(UpdateCheckupRequest $request, Device $device, Checkup $checkup): RedirectResponse
    {
        $checkup->update($request->validated());

        return redirect()
            ->route('devices.checkups.index', $device)
            ->with('success', 'Avaliação atualizada.');
    }

    public function destroy(Device $device, Checkup $checkup): RedirectResponse
    {
        Gate::authorize('delete', $checkup);

        $checkup->delete();

        return redirect()
            ->route('devices.checkups.index', $device)
            ->with('success', 'Avaliação removida.');
    }
}
