<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $mode === 'edit' ? 'Update Asset' : 'Register Asset' }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-100 text-slate-800">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Inventory</p>
                    <h1 class="text-3xl font-bold">{{ $mode === 'edit' ? 'Update Asset Information' : 'Register Hardware Asset' }}</h1>
                </div>
                <a href="{{ route('assets.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Back to records</a>
            </div>

            <form method="POST" action="{{ $mode === 'edit' ? route('assets.update', $asset) : route('assets.store') }}" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                @csrf
                @if ($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Asset name</label>
                        <input id="name" name="name" value="{{ old('name', $asset->name) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                        @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="asset_type" class="block text-sm font-medium text-slate-700 mb-1">Asset type</label>
                        <input id="asset_type" name="asset_type" value="{{ old('asset_type', $asset->asset_type) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                        @error('asset_type') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="serial_number" class="block text-sm font-medium text-slate-700 mb-1">Serial number</label>
                        <input id="serial_number" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-slate-700 mb-1">Model</label>
                        <input id="model" name="model" value="{{ old('model', $asset->model) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>

                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-slate-700 mb-1">Manufacturer</label>
                        <input id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $asset->manufacturer) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>

                    <div>
                        <label for="purchase_date" class="block text-sm font-medium text-slate-700 mb-1">Purchase date</label>
                        <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', optional($asset->purchase_date)->format('Y-m-d')) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>

                    <div>
                        <label for="condition" class="block text-sm font-medium text-slate-700 mb-1">Condition</label>
                        <select id="condition" name="condition" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                            @foreach(['excellent','good','fair','poor'] as $option)
                                <option value="{{ $option }}" {{ old('condition', $asset->condition) === $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Availability status</label>
                        <select id="status" name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                            @foreach(['available','assigned','maintenance','reserved'] as $option)
                                <option value="{{ $option }}" {{ old('status', $asset->status) === $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-slate-700 mb-1">Assigned to</label>
                        <input id="assigned_to" name="assigned_to" value="{{ old('assigned_to', $asset->assigned_to) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="Employee or team name">
                        @error('assigned_to') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                        <input id="department" name="department" value="{{ old('department', $asset->department) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="IT, Finance, Sales...">
                    </div>

                    <div class="md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                        <input id="location" name="location" value="{{ old('location', $asset->location) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="Office, warehouse, branch">
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="Condition notes, warranty, assignment details...">{{ old('notes', $asset->notes) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('assets.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">
                        {{ $mode === 'edit' ? 'Update Asset' : 'Save Asset' }}
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>
