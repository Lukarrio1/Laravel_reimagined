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
            ->map(fn ($value) => \array_values((array) $value))
            ->flatten();
    }

    public function getAllTableColumns($table)
    {
        return Schema::getColumnListing($table);
    }

    public function getTableData($table, $table_columns)
    {
        return DB::table($table)->select($table_columns)->when(\in_array("created_at", $table_columns), fn ($q) => $q->latest());
    }

    public function export($table, $selected_columns)
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

    public function readCSV($file)
    {
        if (!empty($file)) {
            return $this->parseCSV($file);
        } else {
            return false;
        }
    }

    private function parseCSV($file)
    {
        $csvData = [];

        $handle = fopen($file->getPathname(), 'r');

        if ($handle !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        }

        return $csvData;
    }
}
