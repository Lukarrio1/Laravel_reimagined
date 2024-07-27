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
        $database = $currentRouteNode->properties['value']->node_database;
        $table = $currentRouteNode->properties['value']->node_table;
        $item_id = $currentRouteNode->properties['value']->node_item;
        $node_table_columns =
            $currentRouteNode->properties['value']->node_table_columns;
        $item = $database != null && $table != null  ? DB::connection($database)
            ->table($table)
            ->select($node_table_columns)
            ->where('id', $item_id)
            ->first() : [];

        return ["item" => $item];
    }

    public function manyRecords()
    {
        $currentRoute = join('::', explode('@', Route::currentRouteAction()));
        $currentRouteNode = Cache::get('routes')
            ->where('properties.value.route_function', $currentRoute)
            ->first();

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

        $items = $query->get();

        return ["items" => $items];
    }
}
