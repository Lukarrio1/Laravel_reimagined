<?php

namespace App\Http\Controllers;

use ReflectionClass;
use App\SendEmailTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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

    public $cache_ttl = 0;

    public function __construct()
    {
        $this->cache_ttl = optional(collect(Cache::get('settings'))->where('key', 'cache_ttl')->first())->properties ?? 0;
    }

    public $exception_property_value_keys = [
       'route_function',
       'node_audit_message',
       'node_endpoint_to_consume',
       'node_item_display_aid',
       'node_order_by_field',
       'node_order_by_type',
       'node_table_columns',
       'node_database'
    ];
    public function removeKeys($properties)
    {
        $keys = collect($properties)->keys();
        $object = collect([]);
        $keys->each(fn ($key) => !\in_array($key, $this->exception_property_value_keys) ? $object->put($key, $properties->$key) : null);
        return $object->toArray();
    }
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
        if(!isset($currentRouteNode->properties['value']->node_join_tables)) {
            return [];
        }
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


    public function addNestedRelationship2($items, $currentRouteNode, $database)
    {
        $relationShips = $this->handleJoins($currentRouteNode);
        if (count($relationShips) > 0) {
            $items = $items->map(function ($item) use ($relationShips, $database) {
                $database = DB::connection($database);
                $item_to_change = null;
                $tracker = 0;
                $relationShips->each(
                    function ($rel, $idx) use ($item, $database, $relationShips, &$item_to_change) {
                        if($item_to_change == null) {
                            $item->{$rel['second_table']} = $database->table($rel['second_table'])
                        ->select($rel['columns'])
                        ->where($rel['second_value'], $rel['condition'], $item->{$rel['first_value']})
                        ->get();
                            $item_to_change = $item->{$rel['second_table']};
                        } else {
                            collect($item_to_change)->each(function ($s_item) use ($rel, $database, $relationShips, &$item_to_change) {
                                $s_item->{$rel['second_table']} = $database->table($rel['second_table'])
                                        ->select($rel['columns'])
                                        ->where($rel['second_value'], $rel['condition'], $s_item->{$rel['first_value']})
                                        ->get();
                                if($relationShips->count() != $idx + 1) {
                                    collect($s_item->{$rel['second_table']});

                                }
                                return $s_item;
                            });


                        }
                    }
                );
                return $item;
            });
        }
        return $items;
    }


    public function addNestedRelationship($items, $currentRouteNode, $database)
    {
        $relationShips = $this->handleJoins($currentRouteNode);
        if (count($relationShips) > 0) {
            $items = $items->map(function ($item) use ($relationShips, $database) {
                $database = DB::connection($database);
                $this->processRelationships($item, $relationShips, $database);
                return $item;
            });
        }
        return $items;
    }

    private function processRelationships(&$item, $relationShips, $database, $level = 0)
    {
        if ($level < $relationShips->count()) {
            $rel = $relationShips[$level];
            $relatedItems = $database->table($rel['second_table'])
                ->select($rel['columns'])
                ->where($rel['second_value'], $rel['condition'], $item->{$rel['first_value']})
                ->get();
            $item->{$rel['second_table']} = $relatedItems;
            $relatedItems->each(function ($relatedItem) use ($relationShips, $database, $level) {
                $this->processRelationships($relatedItem, $relationShips, $database, $level + 1);
            });
        }
    }




}
