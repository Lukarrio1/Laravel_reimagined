<?php

namespace App\Models;

use League\Csv\Writer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Export extends Model
{
    use HasFactory;

    public function getAllTables()
    {
        return collect(DB::select('SHOW TABLES'))
            ->map(fn($value) => \array_values((array) $value))
            ->flatten();

    }

    public function getAllTableColumns($table)
    {
        // getColumnListing
        return Schema::getColumnListing($table);
    }

    public function getTableData($table, $table_columns)
    {
        return DB::table($table)->select($table_columns);
    }

    public function export($table,$selected_columns)
    {

        $data = DB::table($table)->get($selected_columns);


        $csv = Writer::createFromString('');
        $csv->insertOne(array_keys((array) $data->first())); // Insert column headers
        foreach ($data as $row) {
            $csv->insertOne((array) $row);
        }

        return response()->streamDownload(function () use ($csv, $table) {
            echo $csv->getContent();
        }, $table . '.csv');

    }
}
