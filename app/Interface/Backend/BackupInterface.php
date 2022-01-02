<?php

namespace App\Interface\Backend;

interface BackupInterface
{
    public function getAllBackupFiles();
    public function backupDestroy($file_name);
}