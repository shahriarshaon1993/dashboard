<?php

declare(strict_types=1);

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphTo;

it('to array media', function (): void {
    $media = Media::factory()->create()->fresh();

    expect(array_keys($media->toArray()))
        ->toEqual([
            'id',
            'model_type',
            'model_id',
            'collection',
            'name',
            'file_name',
            'mime_type',
            'size',
            'custom_properties',
            'created_at',
            'updated_at',
        ]);
});

it('media morph to module', function (): void {
    $media = new Media();

    $relation = $media->model();

    expect($relation)->toBeInstanceOf(MorphTo::class);
});
