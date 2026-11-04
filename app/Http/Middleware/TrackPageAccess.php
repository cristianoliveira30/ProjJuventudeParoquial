<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrackPageAccess
{
    /**
     * Rotas/caminhos que nao valem a pena auditar para acesso de pagina.
     *
     * @var array<int, string>
     */
    private array $excludedPaths = [
        'up',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = microtime(true);

        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            $this->storeLog($request, $response, $startedAt);
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (!$request->user()) {
            return false;
        }

        if (!$request->isMethod('GET')) {
            return false;
        }

        if ($request->ajax() || $request->expectsJson()) {
            return false;
        }

        if ($request->is($this->excludedPaths)) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');

        return str_contains($contentType, 'text/html');
    }

    private function storeLog(Request $request, Response $response, float $startedAt): void
    {
        try {
            DB::table('page_access_logs')->insert([
                'user_id' => $request->user()?->id,
                'session_id' => $request->session()->getId(),
                'method' => $request->method(),
                'path' => '/' . ltrim($request->path(), '/'),
                'route_name' => $request->route()?->getName(),
                'full_url' => $request->url(),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'referer' => (string) $request->headers->get('referer'),
                'status_code' => $response->getStatusCode(),
                'response_time_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable) {
            // O log nao deve impactar a navegacao do usuario.
        }
    }
}
