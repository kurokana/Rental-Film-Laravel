<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    // Daftar audit logs
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Filter by action
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        // Filter by model
        if ($request->has('model') && $request->model != '') {
            $query->where('model', $request->model);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('description', 'ilike', "%{$search}%");
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get unique actions and models for filters
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $models = AuditLog::select('model')->distinct()->pluck('model');

        return view('owner.audit-logs.index', compact('logs', 'actions', 'models'));
    }

    // Detail audit log
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        
        return view('owner.audit-logs.show', compact('auditLog'));
    }
}
