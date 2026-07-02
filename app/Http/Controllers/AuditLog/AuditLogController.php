<?php

namespace App\Http\Controllers\AuditLog;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // ── Filters ──
        if ($request->filled('user_id')) {
            $query->forUser($request->user_id);
        }

        if ($request->filled('module')) {
            $query->forModule($request->module);
        }

        if ($request->filled('action')) {
            $query->forAction($request->action);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(50)->withQueryString();

        // Stats for header cards — cache 5 min (frequently changing data)
        $stats = Cache::remember('audit_stats_today', 300, function () {
            return [
                'today_total' => ActivityLog::today()->count(),
                'today_critical' => ActivityLog::today()->critical()->count(),
                'today_users' => ActivityLog::today()->distinct()->count('user_id'),
                'total_all_time' => ActivityLog::count(),
            ];
        });

        // Dropdown options — cache 1 hour (rarely changes)
        $modules = Cache::remember('audit_modules_list', 3600, function () {
            return ActivityLog::whereNotNull('module')
                ->select('module')
                ->distinct()
                ->orderBy('module')
                ->pluck('module');
        });

        $actions = Cache::remember('audit_actions_list', 3600, function () {
            return ActivityLog::whereNotNull('action')
                ->select('action')
                ->distinct()
                ->orderBy('action')
                ->pluck('action');
        });

        $users = Cache::remember('audit_users_list', 3600, function () {
            return User::orderBy('name')->get(['id', 'name']);
        });

        return view('auditlog.auditlog_index', compact('logs', 'stats', 'modules', 'actions', 'users'));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('auditlog.auditlog_show', ['log' => $activityLog]);
    }
}
