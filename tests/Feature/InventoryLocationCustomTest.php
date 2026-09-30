<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryLocationCustomTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_item_with_new_location_creates_record(): void
    {
        Location::query()->forceDelete();

        $initialCount = Location::count();

        Livewire::test('inventory-manager')
            ->set('name', 'Test Item')
            ->set('category_id', null)
            ->set('location_name', 'Gedung B Lt.3 - R-301')
            ->set('supplier_id', null)
            ->set('condition', 'Baik')
            ->set('status', 'Tersedia')
            ->set('stock', 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals($initialCount + 1, Location::count());

        $location = Location::where('building', 'Gedung B')->where('floor', '3')->where('room', 'R-301')->first();
        $this->assertNotNull($location);

        $item = Item::where('name', 'Test Item')->first();
        $this->assertEquals($location->id, $item->location_id);
    }

    public function test_save_item_with_existing_location_reuses_record(): void
    {
        Location::query()->forceDelete();

        $existingLocation = Location::create([
            'building' => 'Gedung A',
            'floor' => '2',
            'room' => 'R-201',
        ]);

        $initialCount = Location::count();

        Livewire::test('inventory-manager')
            ->set('name', 'Test Item')
            ->set('category_id', null)
            ->set('location_name', 'Gedung A Lt.2 - R-201')
            ->set('supplier_id', null)
            ->set('condition', 'Baik')
            ->set('status', 'Tersedia')
            ->set('stock', 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals($initialCount, Location::count());

        $item = Item::where('name', 'Test Item')->first();
        $this->assertEquals($existingLocation->id, $item->location_id);
    }

    public function test_save_item_with_case_insensitive_location_reuses_record(): void
    {
        // Clear any existing locations to avoid conflicts
        Location::query()->forceDelete();

        $existingLocation = Location::create([
            'building' => 'Gedung A',
            'floor' => '2',
            'room' => 'R-201',
        ]);

        $initialCount = Location::count();

        Livewire::test('inventory-manager')
            ->set('name', 'Test Item')
            ->set('category_id', null)
            ->set('location_name', 'gedung a lt.2 - r-201')
            ->set('supplier_id', null)
            ->set('condition', 'Baik')
            ->set('status', 'Tersedia')
            ->set('stock', 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals($initialCount, Location::count());

        $item = Item::where('name', 'Test Item')->first();
        $this->assertEquals($existingLocation->id, $item->location_id);
    }

    public function test_save_item_with_dash_variations_reuses_record(): void
    {
        Location::query()->forceDelete();

        $existingLocation = Location::create([
            'building' => 'Gedung A',
            'floor' => '2',
            'room' => 'R-201',
        ]);

        $initialCount = Location::count();

        // Test with en-dash
        Livewire::test('inventory-manager')
            ->set('name', 'Test Item 1')
            ->set('category_id', null)
            ->set('location_name', 'Gedung A Lt.2 – R-201')
            ->set('supplier_id', null)
            ->set('condition', 'Baik')
            ->set('status', 'Tersedia')
            ->set('stock', 1)
            ->call('save')
            ->assertHasNoErrors();

        // Test with em-dash
        Livewire::test('inventory-manager')
            ->set('name', 'Test Item 2')
            ->set('category_id', null)
            ->set('location_name', 'Gedung A Lt.2 — R-201')
            ->set('supplier_id', null)
            ->set('condition', 'Baik')
            ->set('status', 'Tersedia')
            ->set('stock', 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals($initialCount, Location::count());
    }

    public function test_save_item_with_text_without_pattern_saves_to_building(): void
    {
        Location::query()->forceDelete();

        $initialCount = Location::count();

        Livewire::test('inventory-manager')
            ->set('name', 'Test Item')
            ->set('category_id', null)
            ->set('location_name', 'Gudang Belakang')
            ->set('supplier_id', null)
            ->set('condition', 'Baik')
            ->set('status', 'Tersedia')
            ->set('stock', 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals($initialCount + 1, Location::count());

        $location = Location::where('building', 'Gudang Belakang')->whereNull('floor')->whereNull('room')->first();
        $this->assertNotNull($location);

        $item = Item::where('name', 'Test Item')->first();
        $this->assertEquals($location->id, $item->location_id);
    }

    public function test_edit_item_without_location_change_does_not_create_new_record(): void
    {
        Location::query()->forceDelete();

        $location = Location::create([
            'building' => 'Gedung A',
            'floor' => '2',
            'room' => 'R-201',
        ]);

        $item = Item::create([
            'code' => 'BRG-TEST001',
            'inventory_number' => 'INV-0001',
            'name' => 'Test Item',
            'location_id' => $location->id,
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 1,
        ]);

        $initialCount = Location::count();

        Livewire::test('inventory-manager')
            ->call('edit', $item->id)
            ->set('name', 'Updated Item')
            ->set('location_name', 'Gedung A Lt.2 - R-201')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals($initialCount, Location::count());

        $item->refresh();
        $this->assertEquals('Updated Item', $item->name);
        $this->assertEquals($location->id, $item->location_id);
    }

    public function test_save_item_with_empty_location(): void
    {
        $initialCount = Location::count();

        Livewire::test('inventory-manager')
            ->set('name', 'Test Item')
            ->set('category_id', null)
            ->set('location_name', '')
            ->set('supplier_id', null)
            ->set('condition', 'Baik')
            ->set('status', 'Tersedia')
            ->set('stock', 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals($initialCount, Location::count());

        $item = Item::where('name', 'Test Item')->first();
        $this->assertNull($item->location_id);
    }

    public function test_location_name_validation_max_length(): void
    {
        Livewire::test('inventory-manager')
            ->set('name', 'Test Item')
            ->set('category_id', null)
            ->set('location_name', str_repeat('A', 256))
            ->set('supplier_id', null)
            ->set('condition', 'Baik')
            ->set('status', 'Tersedia')
            ->set('stock', 1)
            ->call('save')
            ->assertHasErrors(['location_name']);
    }

    public function test_location_accessor_with_null_floor_room(): void
    {
        $location = Location::create([
            'building' => 'Gudang',
            'floor' => null,
            'room' => null,
        ]);

        $this->assertEquals('Gudang', $location->name);
    }

    public function test_location_accessor_with_all_fields(): void
    {
        $location = Location::create([
            'building' => 'Gedung A',
            'floor' => '2',
            'room' => 'R-201',
        ]);

        $this->assertEquals('Gedung A Lt.2 - R-201', $location->name);
    }
}
