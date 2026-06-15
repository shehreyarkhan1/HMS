<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog as AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LogActivity
{
    // In routes pe logging skip karo (too frequent / not useful)
    protected array $skipUrls = [
        'api/heartbeat',
        'telescope',
        'horizon',
        '_debugbar',
    ];

    public function handle(Request $request, Closure $next, string $module = 'System'): Response
    {
        $response = $next($request);

        // Only log state-changing requests
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $response;
        }

        // Skip noise URLs
        foreach ($this->skipUrls as $skip) {
            if (str_contains($request->path(), $skip)) {
                return $response;
            }
        }

        // Only log successful responses (2xx, 3xx)
        if ($response->getStatusCode() >= 400) {
            return $response;
        }

        $action = match ($request->method()) {
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'accessed',
        };

        AuditLog::log(
            action: $action,
            module: $module,
            description: "[HTTP] {$request->method()} {$request->path()}",
            severity: $action === 'deleted' ? 'high' : 'medium',
        );

        return $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
}
