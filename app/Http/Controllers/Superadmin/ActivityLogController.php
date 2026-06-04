<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $roleAdmin = Role::where('nama_role', 'admin')->first();

        $query = ActivityLog::with('user')
            ->whereHas('user', function($q) use ($roleAdmin) {
                $q->where('role_id', $roleAdmin->id);
            })
            ->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('aktivitas', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($uq) use ($searchTerm) {
                      $uq->where('nama', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('superadmin.activity-log', compact('logs', 'request'));
    }
}
