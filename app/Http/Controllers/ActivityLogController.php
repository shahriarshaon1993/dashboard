<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\BulkDeleteActivity;
use App\Actions\ClearHistoryActivityLog;
use App\Actions\DeleteActivityLog;
use App\Actions\GetActivityLogs;
use App\DTOs\BulkDestroyDto;
use App\DTOs\ClearHistoryLogDto;
use App\DTOs\FilterDto;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\FilterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

final class ActivityLogController
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request, GetActivityLogs $action): Response
    {
        $filters = FilterDto::from($request->validatedFilters());

        $data = $action->handle($filters);

        return Inertia::render('logs/Index', [
            'logs' => $data['logs'],
            'filters' => $data['filters'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity, DeleteActivityLog $action): RedirectResponse
    {
        $action->handle($activity);

        return back()->with('success', 'Activity log deleted successfully.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(DeleteRequest $request, BulkDeleteActivity $action): RedirectResponse
    {
        /** @var array{ids: int[]} $data */
        $data = $request->validated();

        $userIds = BulkDestroyDto::from($data);

        $action->handle($userIds->ids);

        return back()->with('success', 'Selected activity logs deleted successfully.');
    }

    public function clearHistory(Request $request, ClearHistoryActivityLog $action): RedirectResponse
    {
        $request->validate([
            'range' => ['required', 'string', 'min:1', 'max:10'],
        ]);

        $rangeDto = new ClearHistoryLogDto($request->string('range')->value());

        $action->handle($rangeDto->range);

        return back()->with('success', 'Activity log history cleared successfully.');
    }
}
