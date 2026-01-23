<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\DataTable\DataTable;
use App\Services\DataTable\TextColumn;
use Illuminate\Http\Request;
use Inertia\Response;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ActivityLog::class, 'activity_log');
    }

    public function index(ActivityLog $activity_log): Response
    {
        $dataTable = new DataTable(
            $activity_log
                ->append(['data'])
                ->with('causer')
                ->orderByDesc('id'),
            'Activity Log'
        );

        $dataTable->addColumn(new TextColumn('id', 'ID', sortable: true))
            ->addColumn(new TextColumn('event', 'Event'))
            ->addColumn(new TextColumn('subject_type', 'Type'))
            ->addColumn(new TextColumn('subject_id', 'Type ID'))
            ->addColumn(new TextColumn('causer.name', 'Causer'))
            // ->addColumn(new TextColumn('data', 'Filled Data'))
            ->addColumn(new TextColumn('created_at', 'Create Time', sortable: true));

        return $dataTable->make();
    }
}
