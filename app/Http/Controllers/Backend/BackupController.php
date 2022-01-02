<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interface\Backend\BackupInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

use function Symfony\Component\String\b;

class BackupController extends Controller
{
    public $backupsRepo;

    public function __construct(BackupInterface $backupsRepo)
    {
        $this->backupsRepo = $backupsRepo;
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('admin.backups.access');
        $backups = $this->backupsRepo->getAllBackupFiles();
        return view('backend.backups.index', compact('backups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('admin.backups.create');
        // start the backup process
        Artisan::call('backup:run');
        notify()->success("Backup Created Successfully.","Success","topCenter");
        return back();
    }

    /**
     * For down load project backup
     *
     * @return void
     */
    public function download($file_name)
    {
        Gate::authorize('admin.backups.download');
        $file = config('backup.backup.name') . '/' . $file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);

        if ($disk->exists($file)) {
            $fs = Storage::disk(config('backup.backup.destination.disks')[0])->getDriver();
            $stream = $fs->readStream($file);
            return response()->stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $fs->getMimetype($file),
                "Content-Length" => $fs->getSize($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($file_name)
    {
        Gate::authorize('admin.backups.destroy');
        $this->backupsRepo->backupDestroy($file_name);
        notify()->success("Backup Successfully Deleted.","Deleted","topCenter");
        return back();
    }

     /**
     * clean all backups
     *
     * @return void
     */
    public function clean()
    {
        Gate::authorize('admin.backups.destroy');
        // start the backup process
        Artisan::call('backup:clean');
        notify()->success("All Old Backups Successfully Deleted.","Clear","topCenter");
        return back();
    }
}