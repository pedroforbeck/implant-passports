<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use App\Models\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Manufacturer::class);

        $manufacturers = Manufacturer::query()
            ->orderBy('name')
            ->paginate(15);

        return view('manufacturers.index', ['manufacturers' => $manufacturers]);
    }

    public function create(): View
    {
        Gate::authorize('create', Manufacturer::class);

        return view('manufacturers.create', ['manufacturer' => new Manufacturer]);
    }

    public function store(StoreManufacturerRequest $request): RedirectResponse
    {
        Manufacturer::create($request->validated());

        return redirect()
            ->route('manufacturers.index')
            ->with('success', 'Fabricante cadastrado.');
    }

    public function edit(Manufacturer $manufacturer): View
    {
        Gate::authorize('update', $manufacturer);

        return view('manufacturers.edit', ['manufacturer' => $manufacturer]);
    }

    public function update(UpdateManufacturerRequest $request, Manufacturer $manufacturer): RedirectResponse
    {
        $manufacturer->update($request->validated());

        return redirect()
            ->route('manufacturers.index')
            ->with('success', 'Fabricante atualizado.');
    }

    public function destroy(Manufacturer $manufacturer): RedirectResponse
    {
        Gate::authorize('delete', $manufacturer);

        $manufacturer->delete();

        return redirect()
            ->route('manufacturers.index')
            ->with('success', 'Fabricante removido.');
    }
}
