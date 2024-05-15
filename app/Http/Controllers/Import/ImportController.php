<?php

namespace App\Http\Controllers\Import;

use App\Models\Export;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Import\ImportRequest;

class ImportController extends Controller
{

    public $export;
    public function __construct()
    {
        $this->export = new Export();

    }
    public function index()
    {
        return view('Import.View', ['table_names' => $this->export->getAllTables()]);
    }

    public function import(ImportRequest $request)
    {
        $csv_data = \collect($this->export->readCSV($request->file('csv_file')));
        $table_columns = \collect($csv_data->first());
        $table_data = $csv_data->filter(fn($_, $index) => $index > 0)->map(function ($data, $key) use ($table_columns) {
            $temp = collect([]);
            $table_columns->each(function ($column, $c_key) use ($temp, $data) {
                $temp->put($column, $data[$c_key]);
            });
            return $temp->toArray();
        })->toArray();
        DB::table($request->table_name)->insert($table_data);
        Session::flash('message', 'The data was imported successfully.');
        Session::flash('alert-class', 'alert-success');

        return \redirect()->route('importView');
    }
}
