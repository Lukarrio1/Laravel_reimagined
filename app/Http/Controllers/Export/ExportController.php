<?php

namespace App\Http\Controllers\Export;

use App\Models\Export;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ExportController extends Controller
{
    public function index()
    {
        $export = new Export();
        $table = \request()->get('table', 'users');
        $selected_table_columns = \request()->get('table_columns', []);

        $table_columns = [];
        $table_data = [];
        if (!empty($table)) {
            $table_columns = $export->getAllTableColumns($table);
        }
        if (\count($selected_table_columns) > 0) {
            $table_data = $export->getTableData($table, $selected_table_columns)->get($selected_table_columns);
        } else {
            $table_data = $export->getTableData($table, ['*'])->get();
            $selected_table_columns = $table_columns;
        }

        Cache::set('current_table_data_for_export', ['selected_columns' => $table_columns, 'table' => $table]);

        return \view('Export.View', [
            'tables' => $export->getAllTables(),
            'table_columns' => $table_columns,
            'table_data' => $table_data,
            'selected_table_columns' => $selected_table_columns,
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
