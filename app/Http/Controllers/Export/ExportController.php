<?php

namespace App\Http\Controllers\Export;

use App\Models\Export;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ExportController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:can export');
    }

    public function index()
    {
        $export = new Export();
        $table = \request()->get('table');
        $selected_table_columns = \request()->get('table_columns', []);
        $table_columns = [];
        $table_data = [];

        $search = \request()->get('search', '||');
        $searchParams = collect(explode('|', $search))
            ->filter(fn ($section) => !empty($section)) // Filter out empty sections
            ->map(function ($section) {
                return explode(':', $section);
            });

        if (!empty($table)) {
            $table_columns = $export->getAllTableColumns($table);
        }
        if (\count($selected_table_columns) > 0) {
            $table_data = $export->getTableData($table, $selected_table_columns, $searchParams)->get($selected_table_columns);
        } else {
            $table_data = empty($table) ?: $export->getTableData($table, ['*'], $searchParams)->get();
            $selected_table_columns = $table_columns;
        }

        $searchPlaceholder = \collect($selected_table_columns)
            ->map(function ($key, $idx) use ($selected_table_columns) {
                if ($idx == 0) {
                    return '|' . $key . ":search here";
                }
                if ($idx + 1 == count($selected_table_columns)) {
                    return $key . ":search here|";
                }
                return $key . ":search here";
            })->join('|');

        Cache::set('current_table_data_for_export', ['selected_columns' => $selected_table_columns, 'table' => $table]);

        return \view('Export.View', [
            'tables' => $export->getAllTables(),
            'table_columns' => $table_columns,
            'table_data' => $table_data,
            'selected_table_columns' => $selected_table_columns,
            'table_error' => !empty($table) ?: "Please select a valid table .",
            'searchPlaceholder' => $searchPlaceholder
        ]);
    }

    public function export()
    {
        $export = new Export();
        $current_export_data = Cache::get('current_table_data_for_export');
        Cache::forget('current_table_data_for_export');
        Session::flash('message', 'The data was export successfully.');
        Session::flash('alert-class', 'alert-success');

        return $export->export($current_export_data['table'], $current_export_data['selected_columns']);
    }
}
