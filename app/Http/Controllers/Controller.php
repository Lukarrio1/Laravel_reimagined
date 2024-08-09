<?php

namespace App\Http\Controllers;

use ReflectionClass;
use App\SendEmailTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use SendEmailTrait;

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize');
    }

    public function getCurrentRoute()
    {
        $currentRoute = join('::', explode('@', Route::currentRouteAction()));
        return Cache::get('routes')
        ->where('properties.value.route_function', $currentRoute)
        ->first();
    }

    public function getValidationRules()
    {
        $rules = [ 'required', 'integer', 'min:3', 'min:5', 'min:10', 'sometimes', 'present', 'max:3', 'max:5', 'max:10' ];
        return $rules;
    }

    public function backupDatabase($databaseName, $databaseUser, $databasePassword, $databaseHost, $databasePort = 3306)
    {
        $backupFileName = $databaseName.'-backup-' . date('Y-m-d-h-m-s') . '.sql';
        $backupFilePath = storage_path('app/backups/'.$databaseName."/" . $backupFileName);

        // Ensure the backups directory exists
        if (!file_exists(dirname($backupFilePath))) {
            mkdir(dirname($backupFilePath), 0755, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%d %s > %s',
            escapeshellarg($databaseUser),
            escapeshellarg($databasePassword),
            escapeshellarg($databaseHost),
            ( int )$databasePort,
            escapeshellarg($databaseName),
            escapeshellarg($backupFilePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return $backupFileName;
        } else {
            throw new \Exception('Error creating database backup.');
        }
    }

    public function getHttpData($url)
    {
        return !empty($url) ? Http::get($url)->json() : [];

    }

    public function handleJoins($currentRouteNode)
    {
        $joinTables = collect(json_decode($currentRouteNode->properties['value']->node_join_tables));
        $properties = $currentRouteNode->properties['value'];

        $joinTableQueries = $joinTables->map(function ($item, $idx) use ($properties, $joinTables) {
            return [
                'first_table' => $idx == 0 ? $properties->{'node_table'} : $joinTables[$idx - 1],
                'first_value' => $idx == 0
                    ? $properties->node_join_column
                    : $properties->{'node_previous_'.$item.'_join_column'},
                'condition' => $properties->{'node_'.$item.'_join_by_condition'},
                'second_value' =>  $properties->{'node_'.$item.'_join_by_column'},
                'second_table' => $item,
                'columns' => collect($properties->{'node_'.$item.'_join_columns'})->map(function ($c) use ($item) {
                    return  $c;
                })->toArray(),
            ];
        });

        return $joinTableQueries;
    }
    public function dynamicJoin($query, $mainTable, $joinTable, $firstColumn, $condition, $secondColumn)
    {
        // Define the alias for the join table
        $joinAlias = $joinTable . '_alias';

        // Perform the query with a dynamic join
        $query
           ->leftJoin("$joinTable as $joinAlias", "$mainTable.$firstColumn", $condition, "$joinAlias.$secondColumn")
           ->select("$mainTable.*", "$joinAlias.*");  // Select all columns from both tables
        // ->get()
        // ->map(function ($item) use ($joinAlias) {
        //     // Return results with the join table's data under its key
        //     return [
        //         $item->{$joinAlias . '_id'} => [
        //             'id' => $item->{$joinAlias . '_id'},
        //             'name' => $item->{$joinAlias . '_name'},
        //             // Add other columns from the join table as needed
        //         ],
        //         'main_table' => [
        //             'id' => $item->id,
        //             'name' => $item->name,
        //             // Add other columns from the main table as needed
        //         ]
        //     ];
        // });

        return $query;
    }
}
