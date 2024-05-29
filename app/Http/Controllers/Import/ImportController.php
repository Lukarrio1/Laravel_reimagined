<?php

namespace App\Http\Controllers\Import;

use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{

    public $export;
    public function __construct()
    {
        $this->export = new Export();
        $this->middleware('can:can crud roles');
    }

    public function index()
    {
        return view('Import.View', ['table_names' => $this->export->getAllTables()]);
    }

    public function import(Request $request)
    {
        $export = new Export();
        $first_rules = [
            'csv_file' => [
                'required',
                // 'mimes:csv'
            ],
            'table_name' => ['required'],
        ];
        $validator = Validator::make($request->all(), $first_rules);
        if ($validator->fails()) {
            return \redirect()->back()->withErrors($validator);
        }
        $file_columns = \collect($export->readCSV($request->file('csv_file')))->first();
        $table_columns = $export->getAllTableColumns($request->table_name);
        $table_validation = \collect(get_object_vars((object)$file_columns))
            ->filter(fn ($key) => \in_array($key, \get_object_vars((object)$table_columns)))->count() <=
            \count(\get_object_vars((object)$table_columns));
        if (!$table_validation) {
            return \redirect()->back()->withErrors(['csv_file' => 'The database table columns does not match the fields presented in the csv file.']);
        }
        $csv_data = \collect($this->export->readCSV($request->file('csv_file')));
        $table_columns = \collect($csv_data->first());
        $table_data = $csv_data->filter(fn ($_, $index) => $index > 0)->map(function ($data, $key) use ($file_columns) {
            $temp = collect([]);
            collect(\get_object_vars((object)$file_columns))->each(function ($column, $c_key) use ($temp, $data) {
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
