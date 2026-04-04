<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogActivityController extends Controller
{
    public function userIndex(Request $request)
    {
        $search = $this->searchTerm($request);

        $logs = $this->buildQuery('user', $search)
            ->paginate(15)
            ->withQueryString();

        return view('admin.log-activity.user', compact('logs', 'search'));
    }

    public function adminIndex(Request $request)
    {
        $search = $this->searchTerm($request);

        $logs = $this->buildQuery('admin', $search)
            ->paginate(15)
            ->withQueryString();

        return view('admin.log-activity.admin', compact('logs', 'search'));
    }

    public function userExport(Request $request): StreamedResponse
    {
        $search = $this->searchTerm($request);
        $logs = $this->buildQuery('user', $search)->get();

        return $this->streamCsv(
            $logs,
            'log-activity-user-' . now()->format('Ymd-His') . '.csv'
        );
    }

    public function adminExport(Request $request): StreamedResponse
    {
        $search = $this->searchTerm($request);
        $logs = $this->buildQuery('admin', $search)->get();

        return $this->streamCsv(
            $logs,
            'log-activity-admin-' . now()->format('Ymd-His') . '.csv'
        );
    }

    private function searchTerm(Request $request): string
    {
        return trim((string) $request->query('q', ''));
    }

    private function buildQuery(string $actorType, string $search)
    {
        return ActivityLog::query()
            ->where('actor_type', $actorType)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('actor_id', 'like', '%' . $search . '%')
                        ->orWhere('actor_name', 'like', '%' . $search . '%')
                        ->orWhere('activity', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->latest();
    }

    private function streamCsv($logs, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->stream(function () use ($logs) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel reads Indonesian characters properly.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Waktu', 'Nama', 'ID', 'Aktivitas', 'Deskripsi', 'IP', 'User Agent']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    optional($log->created_at)->format('Y-m-d H:i:s'),
                    $log->actor_name,
                    $log->actor_id,
                    $log->activity,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
