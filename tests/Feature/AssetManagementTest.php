<?php

namespace Tests\Feature;

use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_asset_records_can_be_searched(): void
    {
        Asset::create([
            'name' => 'Office Laptop',
            'asset_type' => 'Laptop',
            'serial_number' => 'LAP-1001',
            'condition' => 'good',
            'status' => 'assigned',
            'assigned_to' => 'Alex Rivera',
            'department' => 'Finance',
        ]);
        Asset::create([
            'name' => 'Meeting Room Display',
            'asset_type' => 'Monitor',
            'serial_number' => 'MON-2001',
            'condition' => 'excellent',
            'status' => 'available',
        ]);

        $this->get(route('assets.index', ['search' => 'Alex Rivera']))
            ->assertOk()
            ->assertSee('Office Laptop')
            ->assertDontSee('Meeting Room Display');
    }

    public function test_assigned_asset_requires_an_employee_or_department(): void
    {
        $this->from(route('assets.create'))
            ->post(route('assets.store'), [
                'name' => 'Office Laptop',
                'asset_type' => 'Laptop',
                'condition' => 'good',
                'status' => 'assigned',
            ])
            ->assertRedirect(route('assets.create'))
            ->assertSessionHasErrors('assigned_to');

        $this->assertDatabaseCount('assets', 0);
    }

    public function test_releasing_an_asset_clears_its_assignment(): void
    {
        $asset = Asset::create([
            'name' => 'Office Laptop',
            'asset_type' => 'Laptop',
            'condition' => 'good',
            'status' => 'assigned',
            'assigned_to' => 'Alex Rivera',
            'department' => 'Finance',
        ]);

        $this->put(route('assets.update', $asset), [
            'name' => $asset->name,
            'asset_type' => $asset->asset_type,
            'condition' => $asset->condition,
            'status' => 'available',
        ])->assertRedirect(route('assets.index'));

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'status' => 'available',
            'assigned_to' => null,
            'department' => null,
        ]);
    }
}
