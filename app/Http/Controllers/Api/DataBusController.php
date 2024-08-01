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
    public $methods = ["manyRecords", "oneRecord", "checkRecord", "deleteRecord"];
    public function __call($method, $parameters)
    {
        $method_to_call = \in_array(collect(explode('_', $method))->first(), $this->methods)
            ? collect(explode('_', $method))->first()
            : $method;
        if (\in_array(collect(explode('_', $method))->first(), $this->methods)) {
            return $this->$method_to_call($method, $parameters);
        }
        return response()->json(['error' => 'Method not found.'], 404);
    }

    public function getCurrentRoute()
    {
        $currentRoute = join('::', explode('@', Route::currentRouteAction()));
        return Cache::get('routes')
            ->where('properties.value.route_function', $currentRoute)
            ->first();
    }


    public function oneRecord()
    {
        $currentRouteNode = $this->getCurrentRoute();
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
            } else {
                $route_parameters->each(fn ($value, $key) => $item->where($key, $value));
            }
            $item = $item->first();
        } else {
            $item = [];
        }
        return \response()->json(["item" => $item], 200);
    }

    public function manyRecords()
    {
        $currentRouteNode = $this->getCurrentRoute();
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

        $query = DB::connection($database)
            ->table($table)
            ->select($columns);

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

        return \response()->json(["items" => $items], 200);
    }

    public function checkRecord()
    {
        $currentRouteNode = $this->getCurrentRoute();
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
            } else {
                $route_parameters->each(fn ($value, $key) => $item->where($key, $value));
            }
            $item = $item->first();
        } else {
            $item = [];
        }

        return \response()->json(["exist" => !empty($item)], 200);
    }

    public function deleteRecord()
    {
        $currentRouteNode = $this->getCurrentRoute();
        $route_parameters = \collect(Route::current()->parameters());
        $database = $currentRouteNode->properties['value']->node_database;
        $table = $currentRouteNode->properties['value']->node_table;
        if ($database != null && $table != null) {
            $item =  DB::connection($database)
                ->table($table);
            $route_parameters->each(fn ($value, $key) => $item->where($key, $value));
            $item->delete();
        } else {
            $item = null;
        }

        return \response()->json(["item" => true], 204);
    }
}
