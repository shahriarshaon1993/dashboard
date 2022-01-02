<?php

namespace App\Repositories\Backend;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Interface\Backend\BackupInterface;

class BackupRepository implements BackupInterface
{
    public function getAllBackupFiles()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];

        foreach($files as $key=> $file) {
            $file_name = str_replace(config('backup.backup.name') . '/', '', $file);
            $backups[] = [
                'file_path' => $file,
                'file_name' => $file_name,
                'file_size' => $this->bytesToHuman($disk->size($file)),
                'created_at' => Carbon::parse($disk->lastModified($file))->diffForHumans(),
                'download_link' => [$file_name],
            ];
        }

        // reverse the backups, so the newest one would be on top
        return array_reverse($backups);
    }

    /**
     * Convert byte to human readable
     *
     * @param  mixed $bytes
     * @return void
     */
    private function bytesToHuman($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Destroy the request file from storage
     *
     * @param  mixed $file_name
     * @return void
     */
    public function backupDestroy($file_name)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);

        if ($disk->exists(config('backup.backup.name') . '/' . $file_name)) {
            $disk->delete(config('backup.backup.name') . '/' . $file_name);
        }
    }
}