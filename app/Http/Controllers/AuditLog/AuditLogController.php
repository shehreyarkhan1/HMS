<?php

namespace App\Http\Controllers\AuditLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Facades\AuditLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Http\Middleware\LogActivity;
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

        // Stats for header cards
        $stats = [
            'today_total' => ActivityLog::today()->count(),
            'today_critical' => ActivityLog::today()->critical()->count(),
            'today_users' => ActivityLog::today()->distinct('user_id')->count('user_id'),
            'total_all_time' => ActivityLog::count(),
        ];

        // Dropdown options
        $modules = ActivityLog::distinct('module')->orderBy('module')->pluck('module');
        $actions = ActivityLog::distinct('action')->orderBy('action')->pluck('action');
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('auditlog.auditlog_index', compact('logs', 'stats', 'modules', 'actions', 'users'));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('auditlog.auditlog_show', ['log' => $activityLog]);
    }
}
