<?php

declare(strict_types=1);

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('has a morphMany relationship with media', function (): void {
    $user = User::factory()->create();

    $media = Media::factory()->create([
        'model_id' => $user->id,
        'model_type' => User::class,
    ]);

    expect($user->media)->toHaveCount(1);
    expect($user->media->first())->toBeInstanceOf(Media::class);
});

it('stores media and creates a media record', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('photo.jpg');
    $media = $user->addMedia($file);

    Storage::disk('public')->assertExists('uploads/'.$media->file_name);

    expect($media)->toBeInstanceOf(Media::class);
    expect($media->collection)->toBe('default');
});

it('clears all media from a given collection', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $file1 = UploadedFile::fake()->image('avatar1.jpg');
    $file2 = UploadedFile::fake()->image('avatar2.jpg');

    $user->addMedia($file1, 'default');
    $user->addMedia($file2, 'default');

    expect($user->media()->count())->toBe(2);

    $user->clearMedia('default');

    expect($user->media()->count())->toBe(0);
});

it('returns all media from a given collection', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $file1 = UploadedFile::fake()->image('photo1.jpg');
    $file2 = UploadedFile::fake()->image('photo2.jpg');
    $file3 = UploadedFile::fake()->image('photo3.jpg');

    $user->addMedia($file1, 'default');
    $user->addMedia($file2, 'default');
    $user->addMedia($file3, 'gallery');

    $mediaCollection = $user->getMedia('default');

    expect($mediaCollection)->toBeInstanceOf(Collection::class);
    expect($mediaCollection)->toHaveCount(2);
    expect($mediaCollection->first())->toBeInstanceOf(Media::class);
});

it('returns the first media from a given collection', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $file1 = UploadedFile::fake()->image('first.jpg');
    $file2 = UploadedFile::fake()->image('second.jpg');

    $user->addMedia($file1, 'default');
    $user->addMedia($file2, 'default');

    $firstMedia = $user->getFirstMedia('default');

    expect($firstMedia)->toBeInstanceOf(Media::class);
    expect($firstMedia->file_name)->toContain('first');
});

it('returns null if collection has no media', function (): void {
    $user = User::factory()->create();

    $firstMedia = $user->getFirstMedia('nonexistent');

    expect($firstMedia)->toBeNull();
});

it('returns all media urls from a given collection', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $file1 = UploadedFile::fake()->image('image1.jpg');
    $file2 = UploadedFile::fake()->image('image2.jpg');

    $user->addMedia($file1, 'default');
    $user->addMedia($file2, 'default');

    $urls = $user->getMediaUrls('default');

    expect($urls)->toBeArray();
    expect($urls)->toHaveCount(2);
    expect($urls[0])->toContain(asset('storage/uploads/'));
    expect($urls[1])->toContain(asset('storage/uploads/'));
});

it('returns the first media url from a given collection', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $file1 = UploadedFile::fake()->image('first.jpg');
    $file2 = UploadedFile::fake()->image('second.jpg');

    $user->addMedia($file1, 'default');
    $user->addMedia($file2, 'default');

    $url = $user->getFirstMediaUrl('default');

    expect($url)->toBeString();
    expect($url)->toContain(asset('storage/uploads/'));
    expect($url)->toContain('first');
});

it('returns null if no media exists in the collection', function (): void {
    $user = User::factory()->create();

    $url = $user->getFirstMediaUrl('default');

    expect($url)->toBeNull();
});
