<?php

declare(strict_types=1);

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    actingAsAdmin();
});

it('can displayed the activity with paginate currently', function (): void {
    $logs = Activity::factory()->count(3)->create();

    $response = $this->get(route('activities.index'));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('logs/Index')
            ->has('logs', 3)
            ->has('logs.meta', fn ($meta) => $meta
                ->where('per_page', 15)
                ->where('current_page', 1)
                ->where('last_page', 1)
                ->etc()
            )
    );
});

it('can displayed the activity with search filter currently', function (): void {
    Activity::factory()->create(['log_name' => 'default']);

    $response = $this->get(route('activities.index', ['search' => 'def']));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('logs/Index')
            ->has('logs.data', 1)
            ->where('logs.data.0.log_name', 'default')
            ->etc()
    );
});

it('activity search by causer name', function (): void {
    $user = User::factory()->create(['name' => 'Meri Doe']);
    Activity::factory()->for($user, 'causer')
        ->create(['log_name' => 'default']);

    $response = $this->get(route('activities.index', ['search' => 'Meri']));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('logs/Index')
            ->has('logs.data', 1)
            ->where('logs.data.0.log_name', 'default')
            ->where('logs.data.0.causer.name', 'Meri Doe')
            ->etc()
    );
});

it('can delete activity', function (): void {
    $activity = Activity::factory()->create();

    $response = $this->delete(route('activities.destroy', $activity->id));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('activity_log', [
        'id' => $activity->id,
    ]);
});

it('can bulk delete multiple activity', function (): void {
    $activities = Activity::factory()->count(5)->create();

    $payload = ['ids' => $activities->pluck('id')->toArray()];

    $response = $this->delete(route('activities.bulk-destroy'), $payload);

    $response->assertRedirect();
    foreach ($activities as $activity) {
        expect(Activity::query()->find($activity->id))->toBeNull();
    }
});

it('deletes activities created within last 15 minutes', function (): void {
    $recent = Activity::factory()->create(['created_at' => now()->subMinutes(10)]);
    $older = Activity::factory()->create(['created_at' => now()->subMinutes(20)]);

    $response = $this->delete(route('activities.clear-history'), ['range' => '15m']);

    $response->assertStatus(302);

    expect(Activity::query()->where('id', $recent->id)->exists())->toBeFalse()
        ->and(Activity::query()->where('id', $older->id)->exists())->toBeTrue();
});

it('deletes activities created within last 1 hour', function (): void {
    $recent = Activity::factory()->create(['created_at' => now()->subMinutes(30)]);
    $older = Activity::factory()->create(['created_at' => now()->subHours(2)]);

    $response = $this->delete(route('activities.clear-history'), ['range' => '1h']);

    $response->assertStatus(302);

    expect(Activity::query()->where('id', $recent->id)->exists())->toBeFalse()
        ->and(Activity::query()->where('id', $older->id)->exists())->toBeTrue();
});

it('deletes activities created within last 24 hours', function (): void {
    $recent = Activity::factory()->create(['created_at' => now()->subHours(5)]);
    $older = Activity::factory()->create(['created_at' => now()->subDays(2)]);

    $response = $this->delete(route('activities.clear-history'), ['range' => '24h']);

    $response->assertStatus(302);

    expect(Activity::query()->where('id', $recent->id)->exists())->toBeFalse()
        ->and(Activity::query()->where('id', $older->id)->exists())->toBeTrue();
});

it('deletes activities created within last 7 days', function (): void {
    $recent = Activity::factory()->create(['created_at' => now()->subDays(3)]);
    $older = Activity::factory()->create(['created_at' => now()->subDays(10)]);

    $response = $this->delete(route('activities.clear-history'), ['range' => '7d']);

    $response->assertStatus(302);

    expect(Activity::query()->where('id', $recent->id)->exists())->toBeFalse()
        ->and(Activity::query()->where('id', $older->id)->exists())->toBeTrue();
});

it('deletes activities created within last 4 weeks', function (): void {
    $recent = Activity::factory()->create(['created_at' => now()->subWeeks(2)]);
    $older = Activity::factory()->create(['created_at' => now()->subWeeks(6)]);

    $response = $this->delete(route('activities.clear-history'), ['range' => '4w']);

    $response->assertStatus(302);

    expect(Activity::query()->where('id', $recent->id)->exists())->toBeFalse()
        ->and(Activity::query()->where('id', $older->id)->exists())->toBeTrue();
});

it('deletes all activities when range is all', function (): void {
    $activities = Activity::factory()->count(3)->create();

    $response = $this->delete(route('activities.clear-history'), ['range' => 'all']);

    $response->assertStatus(302);

    foreach ($activities as $activity) {
        expect(Activity::query()->where('id', $activity->id)->exists())->toBeFalse();
    }
});

it('throw validation exception activities when range is default', function (): void {
    $activities = Activity::factory()->count(3)->create();

    $response = $this->delete(route('activities.clear-history'), ['range' => 'invalid']);

    $response->assertStatus(302);

    $response->assertSessionHasErrors([
        'range' => 'Invalid range value: invalid',
    ]);
});
