<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;

/**
 * @template TModel of Model
 */
trait HasMedia
{
    /**
     * Get all media related to the model.
     *
     * @return MorphMany<Media, TModel>
     */
    public function media(): MorphMany
    {
        /** @var MorphMany<Media, TModel> */
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Store media to a database.
     */
    public function addMedia(UploadedFile $file, string $collection = 'default'): Media
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = uniqid().'_'.$file->getClientOriginalName();
        $file->storeAs('uploads', $fileName, 'public');

        return $this->media()->create([
            'collection' => $collection,
            'name' => $name,
            'file_name' => $fileName,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    /**
     * Delete media file.
     */
    public function clearMedia(string $collection = 'default'): void
    {
        $this->media()->where('collection', $collection)->delete();
    }

    /**
     * Get all media for model.
     *
     * @return Collection<int, Media>
     */
    public function getMedia(string $collection = 'default'): Collection
    {
        return $this->media()
            ->where('collection', $collection)
            ->get();
    }

    /**
     * Get single first medias
     */
    public function getFirstMedia(string $collection = 'default'): ?Media
    {
        return $this->media()
            ->where('collection', $collection)
            ->first();
    }

    /**
     * Get files with full url.
     *
     * @return array<mixed>
     */
    public function getMediaUrls(string $collection = 'default'): array
    {
        /** @var string[] $fileNames */
        $fileNames = $this->getMedia($collection)->pluck('file_name')->all();

        return array_map(fn (string $name): string => asset('storage/uploads/'.$name), $fileNames);
    }

    /**
     * Get a single file with full url.
     */
    public function getFirstMediaUrl(string $collection = 'default'): ?string
    {
        $media = $this->getFirstMedia($collection);

        return $media ? asset('storage/uploads/'.$media->file_name) : null;
    }
}
