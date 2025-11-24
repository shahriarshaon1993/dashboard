<?php

declare(strict_types=1);

use App\Exports\RoleExport;
use App\Imports\RoleImport;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function (): void {
    actingAsAdmin();
});

it('can displayed the roles with paginate currently', function (): void {
    $roles = Role::factory()->count(3)->create();

    $response = $this->get(route('roles.index'));

    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('settings/roles/Index')
            ->has('roles', 3)
            ->has(
                'roles.meta',
                fn ($meta) => $meta
                    ->where('per_page', 15)
                    ->where('current_page', 1)
                    ->where('last_page', 1)
                    ->etc()
            )
    );
});

it('can displayed the roles with search filter currently', function (): void {
    Role::factory()->create(['name' => 'Admin']);
    Role::factory()->create(['name' => 'Employee']);
    Role::factory()->create(['name' => 'Manager']);

    $response = $this->get(route('roles.index', ['search' => 'emp']));

    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('settings/roles/Index')
            ->has('roles.data', 1)
            ->where('roles.data.0.name', 'Employee')
            ->etc()
    );
});

it('can displayed the role create page', function (): void {
    $response = $this->get(route('roles.create'));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('settings/roles/Create')
    );
});

it('can create a new role with valid data', function (): void {
    $permissions = Permission::factory()->count(2)->create();

    $response = $this->post(route('roles.store'), [
        'name' => 'Role Test',
        'permissions' => $permissions->pluck('id')->toArray(),
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('roles', [
        'name' => 'Role Test',
    ]);
});

it('can edit page renders with modules', function (): void {
    $role = Role::factory()->create();

    $response = $this->get(route('roles.edit', $role->id));

    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('settings/roles/Edit')
            ->has('role')
    );
});

it('can updates an existing role with valid data', function (): void {
    $role = Role::create(['name' => 'Role 1']);
    $permissions = Permission::factory()->count(2)->create();

    $response = $this->put(route('roles.update', $role->id), [
        'name' => 'Role A',
        'permissions' => $permissions->pluck('id')->toArray(),
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'Role A',
    ]);
});

it('can delete role', function (): void {
    $role = Role::factory()->create();

    $response = $this->delete(route('roles.destroy', $role->id));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});

it('can bulk delete multiple roles', function (): void {
    $roles = Role::factory()->count(5)->create();

    $payload = [
        'ids' => $roles->pluck('id')->toArray(),
    ];

    $response = $this->delete(route('roles.bulk-destroy'), $payload);

    $response->assertRedirect();
    foreach ($roles as $role) {
        expect(Role::query()->find($role->id))->toBeNull();
    }
});

it('can import roles', function (): void {
    Excel::fake();

    $file = UploadedFile::fake()->create('roles.xlsx');

    $response = $this->post(route('roles.import'), [
        'file' => $file,
    ]);

    Excel::assertImported('roles.xlsx', function (RoleImport $import): true {
        expect($import)->toBeInstanceOf(RoleImport::class);

        return true;
    });
});

it('can exports all roles when no ids are provide', function (): void {
    Excel::fake();

    Role::factory()->count(3)->create();

    $response = $this->post(route('roles.export'));

    $response->assertOk();

    Excel::assertDownloaded('roles.xlsx', function (RoleExport $export): true {
        $exported = $export->collection();
        expect($exported->count())->toBe(Role::query()->count());

        return true;
    });
});

it('can exports selected roles when ids are provided', function (): void {
    Excel::fake();

    $roles = Role::factory()->count(3)->create();

    $ids = [$roles[0]->id, $roles[1]->id];

    $response = $this->post(route('roles.export'), ['ids' => $ids]);

    $response->assertOk();
    Excel::assertDownloaded('roles.xlsx', function (RoleExport $export) use ($ids): true {
        $exported = $export->collection();

        expect($exported->pluck('id')->sort()->values())
            ->toEqual(collect($ids)->sort()->values());

        return true;
    });
});
