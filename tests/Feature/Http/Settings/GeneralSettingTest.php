<?php

declare(strict_types=1);

use App\Models\GeneralSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function (): void {
    actingAsAdmin();
});

it('can displayed the general setting edit page', function (): void {
    $setting = GeneralSetting::factory()->create();

    $response = $this->get(route('general-settings.edit'));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('settings/GeneralSetting')
            ->where('setting.site_title', $setting->site_title)
    );
});

it('edit general setting if none exists', function (): void {
    expect(GeneralSetting::query()->count())->toBe(0);

    $response = $this->patch(route('general-settings.edit'), [
        'site_title' => 'Dashboard',
        'date_format' => 'd M Y',
        'developed_by' => 'Me, Shaon',
        'site_logo' => UploadedFile::fake()
            ->image('site_logo.jpg')->size(1000),
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('general_settings', [
        'site_title' => 'Dashboard',
    ]);
});

it('updates general setting if already exists', function (): void {
    $setting = GeneralSetting::factory()->create();

    $response = $this->patch(route('general-settings.edit'), [
        'site_title' => 'MyApp',
        'date_format' => $setting->date_format,
        'developed_by' => $setting->developed_by,
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('general_settings', [
        'id' => $setting->id,
        'site_title' => 'MyApp',
    ]);
});

it('site logo remove when logo remove is false', function (): void {
    GeneralSetting::factory()->create();

    $response = $this->patch(route('general-settings.edit'), [
        'site_title' => 'Dashboard',
        'date_format' => 'd M Y',
        'developed_by' => 'Me, Shaon',
        'logo_removed' => true,
    ]);

    $response->assertStatus(302);
});
