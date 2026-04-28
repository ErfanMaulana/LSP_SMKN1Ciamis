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

        $module = trim((string) $request->query('module', '')) ?: null;
        $action = trim((string) $request->query('action', '')) ?: null;

        $logs = $this->buildQuery('user', $search, $module, $action)
            ->paginate(15)
            ->withQueryString();

        // derive modules for filter dropdown
        $routes = ActivityLog::where('actor_type', 'user')->get()->map(function ($l) {
            $meta = is_array($l->meta) ? $l->meta : (is_string($l->meta) ? json_decode($l->meta, true) : []);
            return $meta['route'] ?? null;
        })->filter()->unique()->values();

        $modules = $routes->map(function ($r) {
            $parts = explode('.', $r);
            return $parts[1] ?? $r;
        })->unique()->values();

        return view('admin.log-activity.user', compact('logs', 'search', 'modules', 'module', 'action'));
    }

    public function adminIndex(Request $request)
    {
        $search = $this->searchTerm($request);

        $module = trim((string) $request->query('module', '')) ?: null;
        $action = trim((string) $request->query('action', '')) ?: null;

        $logs = $this->buildQuery('admin', $search, $module, $action)
            ->paginate(15)
            ->withQueryString();

        // derive modules for filter dropdown
        $routes = ActivityLog::where('actor_type', 'admin')->get()->map(function ($l) {
            $meta = is_array($l->meta) ? $l->meta : (is_string($l->meta) ? json_decode($l->meta, true) : []);
            return $meta['route'] ?? null;
        })->filter()->unique()->values();

        $modules = $routes->map(function ($r) {
            $parts = explode('.', $r);
            return $parts[1] ?? $r;
        })->unique()->values();

        return view('admin.log-activity.admin', compact('logs', 'search', 'modules', 'module', 'action'));
    }

    public function userExport(Request $request): StreamedResponse
    {
        $search = $this->searchTerm($request);
        $module = trim((string) $request->query('module', '')) ?: null;
        $action = trim((string) $request->query('action', '')) ?: null;
        $logs = $this->buildQuery('user', $search, $module, $action)->get();

        return $this->streamCsv(
            $logs,
            'log-activity-user-' . now()->format('Ymd-His') . '.csv'
        );
    }

    public function adminExport(Request $request): StreamedResponse
    {
        $search = $this->searchTerm($request);
        $module = trim((string) $request->query('module', '')) ?: null;
        $action = trim((string) $request->query('action', '')) ?: null;
        $logs = $this->buildQuery('admin', $search, $module, $action)->get();

        return $this->streamCsv(
            $logs,
            'log-activity-admin-' . now()->format('Ymd-His') . '.csv'
        );
    }

    private function searchTerm(Request $request): string
    {
        return trim((string) $request->query('q', ''));
    }

    private function buildQuery(string $actorType, string $search, ?string $module = null, ?string $action = null)
    {
        $query = ActivityLog::query()
            ->where('actor_type', $actorType)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('actor_id', 'like', '%' . $search . '%')
                        ->orWhere('actor_name', 'like', '%' . $search . '%')
                        ->orWhere('activity', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            });

        if ($module) {
            $query->where(function ($q) use ($module) {
                $q->where('meta->route', 'like', "%.$module.%")
                  ->orWhere('meta->route', 'like', "%.$module");
            });
        }

        if ($action) {
            $map = [
                'create' => 'Menambah',
                'update' => 'Memperbarui',
                'delete' => 'Menghapus',
                'verify' => 'Verifikasi',
                'login' => 'Login',
                'logout' => 'Logout',
            ];

            $keyword = $map[$action] ?? $action;
            $query->where('activity', 'like', "%{$keyword}%");
        }

        return $query->latest();
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

            fputcsv($handle, ['Waktu', 'Nama', 'ID', 'Aktivitas', 'Deskripsi', 'Route', 'Method', 'Path', 'Status', 'IP', 'User Agent']);

            foreach ($logs as $log) {
                $meta = is_array($log->meta) ? $log->meta : [];
                fputcsv($handle, [
                    optional($log->created_at)->format('Y-m-d H:i:s'),
                    $log->actor_name,
                    $log->actor_id,
                    $log->activity,
                    $log->description,
                    $meta['route'] ?? '-',
                    $meta['method'] ?? '-',
                    $meta['path'] ?? '-',
                    $meta['status_code'] ?? '-',
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
