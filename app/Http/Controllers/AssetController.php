<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $assetsQuery = Asset::query();

        if ($search !== '') {
            $assetsQuery->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_type', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('manufacturer', 'like', "%{$search}%")
                    ->orWhere('assigned_to', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $assets = $assetsQuery->orderBy('name')->get();

        return view('assets.index', [
            'assets' => $assets,
            'search' => $search,
            'totalAssets' => Asset::count(),
            'availableAssets' => Asset::where('status', 'available')->count(),
            'assignedAssets' => Asset::where('status', 'assigned')->count(),
            'maintenanceAssets' => Asset::where('status', 'maintenance')->count(),
        ]);
    }

    public function create(): View
    {
        return view('assets.form', [
            'asset' => new Asset(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'asset_type' => ['required', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:150'],
            'manufacturer' => ['nullable', 'string', 'max:150'],
            'purchase_date' => ['nullable', 'date'],
            'condition' => ['required', 'in:excellent,good,fair,poor'],
            'status' => ['required', 'in:available,assigned,maintenance,reserved'],
            'assigned_to' => ['nullable', 'string', 'max:150'],
            'department' => ['nullable', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validated['status'] === 'assigned' && blank($validated['assigned_to'] ?? null) && blank($validated['department'] ?? null)) {
            return back()
                ->withErrors(['assigned_to' => 'Provide an employee or department for an assigned asset.'])
                ->withInput();
        }

        if ($validated['status'] !== 'assigned') {
            $validated['assigned_to'] = null;
            $validated['department'] = null;
        }

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Hardware asset added successfully.');
    }

    public function edit(Asset $asset): View
    {
        return view('assets.form', [
            'asset' => $asset,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'asset_type' => ['required', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:150'],
            'manufacturer' => ['nullable', 'string', 'max:150'],
            'purchase_date' => ['nullable', 'date'],
            'condition' => ['required', 'in:excellent,good,fair,poor'],
            'status' => ['required', 'in:available,assigned,maintenance,reserved'],
            'assigned_to' => ['nullable', 'string', 'max:150'],
            'department' => ['nullable', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validated['status'] === 'assigned' && blank($validated['assigned_to'] ?? null) && blank($validated['department'] ?? null)) {
            return back()
                ->withErrors(['assigned_to' => 'Provide an employee or department for an assigned asset.'])
                ->withInput();
        }

        if ($validated['status'] !== 'assigned') {
            $validated['assigned_to'] = null;
            $validated['department'] = null;
        }

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset information updated successfully.');
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Asset record removed successfully.');
    }
}
