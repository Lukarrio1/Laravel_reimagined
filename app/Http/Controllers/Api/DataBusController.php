<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class DataBusController extends Controller
{

    public $orderByTypes = [
        "asc",
        'desc'
    ];



    public function oneRecord()
    {

        $currentRoute = join('::', explode('@', Route::currentRouteAction()));
        $currentRouteNode = Cache::get('routes')
            ->where('properties.value.route_function', $currentRoute)
            ->first();
        $route_parameters = \collect(Route::current()->parameters());
        $database = $currentRouteNode->properties['value']->node_database;
        $table = $currentRouteNode->properties['value']->node_table;
        $node_table_columns =
            $currentRouteNode->properties['value']->node_table_columns;
        if ($database != null && $table != null) {
            $item =  DB::connection($database)
                ->table($table)
                ->select($node_table_columns);
            if (isset($currentRouteNode->properties['value']->node_item)) {
                $item->where('id', $currentRouteNode->properties['value']->node_item);
            } else
                $route_parameters->each(fn ($value, $key) => $item->where($key, $value));
            // \dd($item->toSql());
            $item = $item->first();
        } else $item = [];

        return ["item" => $item];
    }

    public function manyRecords()
    {
        $currentRoute = join('::', explode('@', Route::currentRouteAction()));
        $currentRouteNode = Cache::get('routes')
            ->where('properties.value.route_function', $currentRoute)
            ->first();
        $route_parameters = \collect(Route::current()->parameters());
        if (!$currentRouteNode) {
            return ["items" => []];
        }

        $properties = $currentRouteNode->properties['value'];
        $database = optional($properties)->node_database;
        $table = optional($properties)->node_table;
        $columns = optional($properties)->node_table_columns ?? ['*'];
        $limit = (int) optional($properties)->node_data_limit;
        $orderByField = optional($properties)->node_order_by_field;
        $orderByType = optional($properties)->node_order_by_type;

        if (!$database || !$table) {
            return ["items" => []];
        }

        $query = DB::connection($database)->table($table)->select($columns);

        if ($limit > 0) {
            $query->limit($limit);
        }

        if ($orderByField && $orderByType) {
            $query->orderBy($orderByField, $orderByType);
        }
        if ($route_parameters->count() > 0) {
            $route_parameters->each(fn ($value, $key) => $query->where($key, "LIKE", "%" . $value . "%"));
        }

        $items = $query->get();

        return ["items" => $items];
    }
}
