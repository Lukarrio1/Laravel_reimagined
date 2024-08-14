<?php

namespace App\Http\Controllers\Node;

use App\Models\User;
use Mockery\Undefined;
use App\Models\Node\Node;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use App\Models\Node\Node_Type;
use Illuminate\Support\Facades\DB;
use App\Models\Reference\Reference;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\DataBusController;
use App\Http\Controllers\Cache\CacheController;

class NodeController extends Controller
{
    public $cache;
    public $tenancy;

    public function __construct()
    {
        $this->middleware('can:can crud nodes');
        $this->cache = new CacheController();
        $this->tenancy = new Tenant();
    }
    public function index($node = null)
    {


        $translate = [
            'name' => 'name',
            'description' => 'small_description',
            'type' => 'node_type',
            'uuid' => 'uuid'
        ];

        $translateExamples = [
            'name' => 'Link 1',
            'description' => 'link to the home page',
            'type' => 'link',
            "uuid" => 'asdalsdlada'
        ];

        // Build the search placeholder
        $searchPlaceholder = \collect($translate)->keys()->map(function ($key, $idx) use ($translate, $translateExamples) {
            if ($idx == 0) {
                return '|' . $key . ":$translateExamples[$key]";
            }
            if ($idx + 1 == count($translate)) {
                return $key . ":$translateExamples[$key]|";
            }
            return $key . ":$translateExamples[$key]";
        })->join('|');

        // Parse the search parameter from the request and create key-value pairs
        $searchParams = empty(request()->get('search')) ? \collect([]) : collect(explode('|', request()->get('search')))
            ->filter(fn ($section) => !empty($section)) // Filter out empty sections
            ->map(function ($section) {
                return explode(':', $section);
            });

        // Query for Nodes and apply filters based on search parameters
        $nodes = Node::query();
        $nodes_count_overall = $nodes->count();

        $searchParams->when(
            $searchParams->filter(fn ($val) => \count($val) > 1)->count() > 0,
            fn ($collection) => $collection->each(function ($section) use ($nodes, $translate) {
                list($key, $value) = $section;
                // Check if the key is valid in the translation map
                if (!isset($translate[$key])) {
                    return; // Skip invalid keys
                }
                // Convert 'type' value to its corresponding node type ID
                if ($translate[$key] === 'node_type') {
                    $convertedValue = array_search(\strtoupper($value), Node::NODE_TYPE);
                } else {
                    $convertedValue = $value;
                }

                $nodes->where($translate[$key], 'LIKE', '%' . $convertedValue . '%'); // Apply the condition to the query
            })
        );


        $nodes  = $nodes->with(['permission']);
        $node_count = $nodes->count();
        $max_amount_of_pages
            = $node_count / 8;

        \request()->merge([
            'page' => \request('page') == null || (int) \request('page') < 1 ? 1 : ((int)\request('page') > \floor($max_amount_of_pages) ? \floor($max_amount_of_pages + 1) : \request('page')),
            'search' =>  request()->get('search')
        ]);

        return \view('Nodes.View', [
            'types' => (new Node_Type())->NODE_TYPES($node),
            'authentication_levels' => Node::Authentication_Levels,
            'node_statuses' => Node::NODE_STATUS,
            'nodes_count' => $node_count,
            'nodes' => $nodes->latest("updated_at")->with('permission')->customPaginate(8, (int)\request()->get('page'))->get()
                ->when($node, fn ($collection) => [$node, ...$collection->filter(fn ($item) => \optional($item)->id != $node->id)]),
            'node' => $node,
            'extra_scripts' => (new Node_Type())->extraScripts()->join(''),
            'permissions' => Permission::all(),
            'search_placeholder' => $searchPlaceholder,
            'page_count' => \ceil($max_amount_of_pages),
            'search' => request()->get('search'),
            'nodes_count_overall' => $nodes_count_overall
        ]);
    }

    public function save(Request $request)
    {
        $extra_rules = [];
        $extra_handler = [];
        $node_join_tables = !empty($request->get('node_join_tables')) ?
         json_decode($request->get('node_join_tables')) : [];

        $node_endpoint_length = (int)$request->node_endpoint_length;
        if(count($node_join_tables) > 0) {
            for($i = 0;$i < count($node_join_tables);$i++) {
                $current_table = $node_join_tables[$i];
                // $node_categories_join_by_condition = $request->get("node_".$current_table."_join_by_condition");
                // $node_categories_join_by_column = $request->get("node_".$current_table."_join_by_column");
                // $node_categories_join_columns = $request->get("node_".$current_table."_join_columns");
                $extra_rules["node_previous_".$current_table."_join_column"] = "";
                $extra_handler["node_previous_".$current_table."_join_column"] = "";

                $extra_rules["node_".$current_table."_join_by_condition"] = '';
                $extra_handler["node_".$current_table."_join_by_condition"] = ['location' => 'properties'];

                $extra_rules["node_".$current_table."_join_by_column"] = '';
                $extra_handler["node_".$current_table."_join_by_column"] = ['location' => 'properties'];

                $extra_rules["node_".$current_table."_one_or_many"] = '';
                $extra_handler["node_".$current_table."_one_or_many"] = ['location' => 'properties'];

                $extra_rules["node_".$current_table."_join_columns"] = '';
                $extra_handler["node_".$current_table."_join_columns"] = ['location' => 'properties'];


            }
        }
        if (0 < $node_endpoint_length) {
            $columns = \json_decode($request->node_endpoint_columns);
            $request->merge(["node_table_columns" => $columns]);
            for ($i = 0; $i < $node_endpoint_length; $i++) {
                $extra_rules["node_endpoint_field_" . $columns[$i]] = '';
                $extra_handler["node_endpoint_field_" . $columns[$i]] = ['location' => 'properties'];
                $request->merge(["node_endpoint_field_" . $columns[$i] => $request->get("node_endpoint_field_" . $i)]);
            }
        } else {
            $request->merge(["node_table_columns" => $request->node_table_columns]);
        }

        $main_rules = [
            'name' => 'required',
            'small_description' => 'required',
            'authentication_level' => 'required',
            'node_type' => 'required',
            'node_status' => 'required',
        ];
        $current_node_type = (new Node_Type())->NODE_TYPES()->firstWhere('id', $request->node_type);
        $current_node = !empty($request->id) ? Node::find((int) $request->id) : null;
        $validator = Validator::make(
            $request->all(),
            isset($current_node_type['rules']) ? $current_node_type['rules'] + $main_rules :
            $main_rules
        );

        if ($validator->fails()) {
            dd($request->all(), $validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->merge([
            'permission_id' => empty($request->permission_id) ? 0 : $request->permission_id,
        ] + $this->tenancy->addTenantIdToCurrentItem(\optional(\auth()->user()->land)->id));

        Node::updateOrCreate(['id' => $request->id], $request->except(
            isset($current_node_type['rules']) ? \collect($current_node_type['rules'])
                ->keys()->toArray() + $extra_rules : []
        ) + [
            'properties' => (new Node_Type())->handler($current_node_type['handle'] + $extra_handler, $request->all()),
            'uuid' => !empty($current_node->uuid) ? $current_node->uuid : Str::random(50),
        ])
            ->updatePageLayoutName()
            ->updatePageLink();


        return \redirect()->route('viewNodes');
    }

    public function node(Node $node)
    {
        return $this->index($node);
    }

    public function databusData()
    {
        $database = \request('database');
        $table = \request('table');
        $display_aid = \request('display_aid');
        // getColumnListing
        $tables = $database != "null" ? collect(DB::connection($database)->select('SHOW TABLES'))
            ->map(fn ($value) => \array_values((array) $value))
            ->flatten() : [];
        $columns = !isset($table) || $table != "null" ? DB::connection($database)->getSchemaBuilder()->getColumnListing($table) : [];
        $node = Node::find(\request('node_id'));
        $table_items = $database != "null" && $table != "null" ? DB::connection($database)->table($table)->get() : [];
        $data_to_consume = empty(request('node_url_to_consume')) || request('node_url_to_consume') == "null" ? null : $this->getHttpData(request('node_url_to_consume'));
        if(!empty($data_to_consume) && $display_aid != "null") {
            $data = isset($data_to_consume[$display_aid]) && gettype($data_to_consume[$display_aid]) != "int" ? collect($data_to_consume[$display_aid])->toArray() : [];
            $columns = gettype($data) == "object" ? array_keys($data) : array_keys($data[0]);
        }
        return [
            "validation_rules" => $this->getValidationRules(),
            'node' => $node,
            "tables" => $tables,
            "data_to_consume" => $data_to_consume,
            "columns" => $columns,
            'display_aid_columns' => !empty($data_to_consume) ? collect($data_to_consume)->keys() : $columns,
            "table_items" => $table_items,
            "orderByTypes" => (new DataBusController())->orderByTypes,
            "databases" => collect(Cache::get('settings'))
                ->where('key', 'database_configuration')->first()
                ->getSettingValue()->keys()
        ];
    }


    public function databusTableData()
    {
        $query_conditions = ['=','!=','>','<'];
        $selectedTables = explode(',', request()->get('tables', []));
        $database = request()->get('database');
        $tables_with_columns = collect([]);
        for($i = 0;$i < count($selectedTables);$i++) {
            $tables_with_columns->put(
                $selectedTables[$i],
                DB::connection($database)->getSchemaBuilder()->getColumnListing($selectedTables[$i])
            );
        }


        return ["tables_with_columns" => $tables_with_columns,'query_conditions' => $query_conditions];
    }



    public function delete(Node $node)
    {
        $node->delete();
        Session::flash('message', 'The node was deleted successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->route('viewNodes');
    }
}
