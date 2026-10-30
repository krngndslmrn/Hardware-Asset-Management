<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hardware & Asset Management</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-100 text-slate-800">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Operations</p>
                    <h1 class="text-3xl font-bold">Hardware & Asset Management</h1>
                </div>
                <a href="{{ route('assets.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-500">
                    + Register Asset
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Assets</p>
                    <p class="mt-2 text-3xl font-bold">{{ $totalAssets }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Available</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $availableAssets }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Assigned</p>
                    <p class="mt-2 text-3xl font-bold text-sky-600">{{ $assignedAssets }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Maintenance</p>
                    <p class="mt-2 text-3xl font-bold text-amber-600">{{ $maintenanceAssets }}</p>
                </div>
            </div>

            <form method="GET" action="{{ route('assets.index') }}" class="mb-5 flex flex-col sm:flex-row gap-3">
                <label for="search" class="sr-only">Search asset records</label>
                <input id="search" name="search" value="{{ $search }}" type="search" placeholder="Search name, serial number, employee, department..."
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                <button type="submit" class="rounded-lg bg-slate-800 px-5 py-2 font-medium text-white hover:bg-slate-700">Search</button>
                @if ($search !== '')
                    <a href="{{ route('assets.index') }}" class="rounded-lg border border-slate-300 bg-white px-5 py-2 text-center font-medium text-slate-700 hover:bg-slate-50">Clear</a>
                @endif
            </form>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-xl font-semibold">Asset Records <span class="text-sm font-normal text-slate-500">({{ $assets->count() }} shown)</span></h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Asset</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Condition</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Assigned To</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($assets as $asset)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $asset->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $asset->serial_number ?: 'No serial number' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $asset->asset_type }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ ucfirst($asset->condition) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                            @if($asset->status === 'available') bg-emerald-100 text-emerald-700
                                            @elseif($asset->status === 'assigned') bg-sky-100 text-sky-700
                                            @elseif($asset->status === 'maintenance') bg-amber-100 text-amber-700
                                            @else bg-violet-100 text-violet-700
                                            @endif">
                                            {{ ucfirst($asset->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $asset->assigned_to ?: 'Unassigned' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $asset->department ?: 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('assets.edit', $asset) }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Edit</a>
                                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Delete this asset record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-500 font-medium">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                        {{ $search !== '' ? 'No asset records match your search.' : 'No asset records found yet. Register your first hardware asset.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
