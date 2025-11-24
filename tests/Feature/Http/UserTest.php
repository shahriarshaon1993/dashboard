<?php

declare(strict_types=1);

use App\Enums\ActiveStatus;
use App\Exports\UserExport;
use App\Imports\UserImport;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;
use Maatwebsite\Excel\Facades\Excel;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    actingAsAdmin();
});

it('can displayed the users with paginate currently', function (): void {
    $users = User::factory()->count(3)->create();

    $response = $this->get(route('users.index'));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('users/Index')
            ->has('users', 3)
            ->has('users.meta', fn ($meta) => $meta
                ->where('per_page', 15)
                ->where('current_page', 1)
                ->where('last_page', 1)
                ->etc()
            )
    );
});

it('can displayed the users with search filter currently', function (): void {
    User::factory()->create(['name' => 'Admin']);

    $response = $this->get(route('users.index', ['search' => 'adm']));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('users/Index')
            ->has('users.data', 1)
            ->where('users.data.0.name', 'Admin')
            ->etc()
    );
});

it('can displayed the user create page', function (): void {
    $response = $this->get(route('users.create'));

    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('users/Create')
    );
});

it('create a new user with valid data', function (): void {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@gmail.com',
        'phone' => '01723771208',
        'password' => 'password',
        'is_active' => ActiveStatus::Active->value,
        'roles' => [],
        'permissions' => [],
        'avatar_removed' => false,
        'avatar' => UploadedFile::fake()->image('avatar.jpg')->size(1000),
    ];

    $response = $this->post(route('users.store'), $data);

    $response->assertStatus(302);
    $response->assertRedirect(route('users.index'));
});

it('create a new user with roles and permissions', function (): void {
    $role = Role::factory()->create();
    $permission = Permission::factory()->create();

    $data = [
        'name' => 'John Doe',
        'email' => 'john@gmail.com',
        'phone' => '01723771208',
        'password' => 'password',
        'is_active' => ActiveStatus::Active->value,
        'roles' => [$role->id],
        'permissions' => [$permission->id],
        'avatar_removed' => false,
    ];

    $response = $this->post(route('users.store'), $data);

    $response->assertStatus(302);
    $response->assertRedirect(route('users.index'));
});

it('edit page renders with user, roles and modules', function (): void {
    $user = User::factory()->create();

    $response = $this->get(route('users.edit', $user->id));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('users/Edit')
            ->has('user')
    );
});

it('updates an existing user with full data', function (): void {
    $user = User::factory()->create();

    $data = [
        'name' => 'John Deo',
        'email' => $user->email,
        'phone' => $user->phone,
        'is_active' => ActiveStatus::Active->value,
        'avatar' => UploadedFile::fake()->image('avatar.jpg')->size(1000),
        'avatar_removed' => false,
    ];

    $response = $this->patch(route('users.update', $user->id), $data);

    $response->assertStatus(302);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'John Deo',
    ]);
});

it('uploads user avatar even if avatar removed is true', function (): void {
    $user = User::factory()->create();
    $user->addMedia(UploadedFile::fake()->image('avatar.jpg'), 'avatar');

    $data = [
        'name' => 'Up name',
        'email' => $user->email,
        'phone' => $user->phone,
        'is_active' => ActiveStatus::Active->value,
        'avatar_removed' => true,
        'avatar' => UploadedFile::fake()->image('avatar.jpg')->size(1000),
    ];

    $response = $this->patch(route('users.update', $user->id), $data);

    $response->assertStatus(302);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Up name',
    ]);
});

it('removes user avatar when avatar removed is true', function (): void {
    $user = User::factory()->create();
    $user->addMedia(UploadedFile::fake()->image('avatar.jpg'), 'avatar');

    $data = [
        'name' => 'Up name',
        'email' => $user->email,
        'phone' => $user->phone,
        'is_active' => ActiveStatus::Active->value,
        'avatar_removed' => true,
    ];

    $response = $this->patch(route('users.update', $user->id), $data);

    $response->assertStatus(302);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Up name',
    ]);
});

it('updates user with roles & permissions', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();
    $permission = Permission::factory()->create();

    $data = [
        'name' => 'Up name',
        'email' => $user->email,
        'phone' => $user->phone,
        'is_active' => ActiveStatus::Active->value,
        'roles' => [$role->id],
        'permissions' => [$permission->id],
        'avatar_removed' => false,
    ];

    $response = $this->put(route('users.update', $user->id), $data);

    $response->assertStatus(302);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Up name',
    ]);
});

it('updates user with password', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();
    $permission = Permission::factory()->create();

    $data = [
        'name' => 'Up name',
        'email' => $user->email,
        'phone' => $user->phone,
        'is_active' => ActiveStatus::Active->value,
        'password' => 'password',
        'avatar_removed' => false,
    ];

    $response = $this->put(route('users.update', $user->id), $data);

    $response->assertStatus(302);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Up name',
    ]);
});

it('deletes sessions if user is not active', function (): void {
    $user = User::factory()->create();

    DB::table('sessions')->insert([
        'id' => 'session_1',
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Pest',
        'payload' => 'data',
        'last_activity' => now()->timestamp,
    ]);

    $data = [
        'name' => 'Up name',
        'email' => $user->email,
        'phone' => $user->phone,
        'is_active' => ActiveStatus::Inactive->value,
        'avatar_removed' => false,
    ];

    $response = $this->put(route('users.update', $user->id), $data);

    $response->assertStatus(302);
    expect(DB::table('sessions')
        ->where('user_id', $user->id)->count())
        ->toBe(0);
});

it('can delete user', function (): void {
    $user = User::factory()->create();

    $response = $this->delete(route('users.destroy', $user->id));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('can bulk delete multiple users', function (): void {
    $users = User::factory()->count(5)->create();

    $payload = ['ids' => $users->pluck('id')->toArray()];

    $response = $this->delete(route('users.bulk-destroy'), $payload);

    $response->assertRedirect();
    foreach ($users as $user) {
        expect(User::query()->find($user->id))->toBeNull();
    }
});

it('can import users', function (): void {
    Excel::fake();

    $file = UploadedFile::fake()->create('users.xlsx');

    $response = $this->post(route('users.import'), [
        'file' => $file,
    ]);

    Excel::assertImported('users.xlsx', function (UserImport $import): true {
        expect($import)->toBeInstanceOf(UserImport::class);

        return true;
    });
});

it('can exports all users when no ids are provide', function (): void {
    Excel::fake();

    $users = User::factory()->count(3)->create();

    $response = $this->post(route('users.export'));

    $response->assertOk();

    Excel::assertDownloaded('users.xlsx', function (UserExport $export): true {
        $exported = $export->collection();
        expect($exported->count())->toBe(User::count());

        return true;
    });
});

it('can exports selected users when ids are provided', function (): void {
    Excel::fake();

    $users = User::factory()->count(3)->create();

    $ids = [$users[0]->id, $users[1]->id];

    $response = $this->post(route('users.export'), ['ids' => $ids]);

    $response->assertOk();

    Excel::assertDownloaded('users.xlsx', function (UserExport $export) use ($users): true {
        $exported = $export->collection();

        expect($exported->pluck('name')[0])
            ->toEqual($users->pluck('name')[0]);

        return true;
    });
});
