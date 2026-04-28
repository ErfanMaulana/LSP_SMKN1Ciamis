<?php

namespace App\Http\Middleware;

use App\Support\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $admin = auth('admin')->user();
        if (!$admin) {
            return $response;
        }

        if (!$this->shouldLog($request, $response)) {
            return $response;
        }

        $routeName = (string) $request->route()?->getName();
        [$activity, $description] = $this->buildActivityPayload($request, $routeName);

        ActivityLogger::logAdmin(
            (string) ($admin->username ?? $admin->id ?? 'admin'),
            $admin->name ?? $admin->username ?? 'Admin',
            $activity,
            $description,
            $request,
            [
                'route' => $routeName,
                'method' => strtoupper($request->method()),
                'path' => $request->path(),
                'status_code' => $response->getStatusCode(),
                'params' => $request->route()?->parameters() ?? [],
            ]
        );

        return $response;
    }

    private function shouldLog(Request $request, Response $response): bool
    {
        if (!in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return false;
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode < 200 || $statusCode >= 400) {
            return false;
        }

        $routeName = (string) $request->route()?->getName();
        if (!str_starts_with($routeName, 'admin.')) {
            return false;
        }

        // Avoid duplicate logs because this action is logged explicitly in controller.
        if ($routeName === 'admin.logout') {
            return false;
        }

        return true;
    }

    private function buildActivityPayload(Request $request, string $routeName): array
    {
        $segments = explode('.', $routeName);
        $resourceKey = $segments[1] ?? 'sistem';
        $actionKey = end($segments) ?: strtolower($request->method());

        $resourceLabel = $this->humanizeResource($resourceKey);
        $actionLabel = $this->humanizeAction((string) $actionKey, $request);
        $subjectLabel = $this->extractSubjectLabel($request);

        $activity = $actionLabel . ' ' . $resourceLabel;
        if ($subjectLabel !== null) {
            $activity .= ': ' . $subjectLabel;
        }

        $description = sprintf(
            'Admin melakukan aksi %s pada modul %s%s (%s %s).',
            strtolower($actionLabel),
            $resourceLabel,
            $subjectLabel !== null ? ' untuk ' . $subjectLabel : '',
            strtoupper($request->method()),
            $request->path()
        );

        return [$activity, $description];
    }

    private function extractSubjectLabel(Request $request): ?string
    {
        $parameters = $request->route()?->parameters() ?? [];
        foreach (['nama', 'nama_skema', 'nama_jurusan', 'nama_lembaga', 'judul_unit', 'username', 'no_reg', 'NIK', 'id', 'ID_jurusan', 'ID_asesor'] as $key) {
            $value = $parameters[$key] ?? $request->input($key);
            if (is_string($value)) {
                $value = trim($value);
            }

            if (is_numeric($value)) {
                $value = (string) $value;
            }

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        $routeParams = array_values(array_filter($parameters, function ($value) {
            return is_string($value) || is_numeric($value);
        }));

        if (!empty($routeParams)) {
            $first = (string) $routeParams[0];
            return trim($first) !== '' ? trim($first) : null;
        }

        return null;
    }

    private function humanizeResource(string $resource): string
    {
        $map = [
            'asesi' => 'Asesi',
            'asesor' => 'Asesor',
            'admin-management' => 'Manajemen Admin',
            'akun-asesi' => 'Akun Asesi',
            'skema' => 'Skema',
            'jurusan' => 'Jurusan',
            'kelompok' => 'Kelompok',
            'jadwal-ujikom' => 'Jadwal Ujikom',
            'mitra' => 'Mitra',
            'berita' => 'Berita',
            'kontak' => 'Kontak',
            'carousel' => 'Carousel',
            'roles' => 'Role & Permission',
            'social-media' => 'Media Sosial',
            'profile-content' => 'Konten Profil',
            'tuk' => 'TUK',
            'panduan' => 'Panduan',
        ];

        if (isset($map[$resource])) {
            return $map[$resource];
        }

        $resource = str_replace(['-', '_'], ' ', $resource);
        return ucwords($resource);
    }

    private function humanizeAction(string $action, Request $request): string
    {
        $action = strtolower($action);

        if (str_contains($action, 'store') || str_contains($action, 'create')) {
            return 'Menambah';
        }

        if (str_contains($action, 'update') || str_contains($action, 'edit') || str_contains($action, 'toggle') || str_contains($action, 'assign')) {
            return 'Memperbarui';
        }

        if (str_contains($action, 'destroy') || str_contains($action, 'delete') || str_contains($action, 'remove')) {
            return 'Menghapus';
        }

        if (str_contains($action, 'approve') || str_contains($action, 'reject') || str_contains($action, 'verifikasi')) {
            return 'Memverifikasi';
        }

        if (str_contains($action, 'login')) {
            return 'Login';
        }

        if (str_contains($action, 'logout')) {
            return 'Logout';
        }

        return match (strtoupper($request->method())) {
            'POST' => 'Memproses',
            'PUT', 'PATCH' => 'Memperbarui',
            'DELETE' => 'Menghapus',
            default => 'Mengakses',
        };
    }
}
