<?php

namespace App\Http\Controllers\Node;

use App\Models\Node\Node;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use App\Models\Node\Node_Type;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
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
        $searchParams = collect(explode('|', request()->get('search')))
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
                    $convertedValue = array_search(ucfirst($value), Node::NODE_TYPE);
                } else {
                    $convertedValue = $value;
                }

                $nodes->where($translate[$key], 'LIKE', '%' . $convertedValue . '%'); // Apply the condition to the query
            })
        );
        $node_count = $nodes->count();
        $max_amount_of_pages
            = $nodes->get()->count() / 8;
        \request()->merge([
            'page' => \request('page') == null || (int) \request('page') < 1 ? 1 : ((int)\request('page') > \floor($max_amount_of_pages) ? \floor($max_amount_of_pages + 1) : \request('page')),
            'search' =>  request()->get('search')
        ]);

        return \view('Nodes.View', [
            'types' => (new Node_Type())->NODE_TYPES($node),
            'authentication_levels' => Node::Authentication_Levels,
            'node_statuses' => Node::NODE_STATUS,
            'nodes_count' =>$node_count,
            'nodes' => $nodes->latest("updated_at")->customPaginate(8, (int)\request()->get('page'))->get()
                ->when($node, fn ($collection) => [$node, ...$collection->filter(fn ($item) => \optional($item)->id != $node->id)]),
            'node' => $node,
            'extra_scripts' => (new Node_Type())->extraScripts()->join(''),
            'permissions' => Permission::all(),
            'search_placeholder' => $searchPlaceholder,
            'page_count' => \ceil($max_amount_of_pages),
            'search' => request()->get('search'),
            'nodes_count_overall'=> $nodes_count_overall
        ]);
    }

    public function save(Request $request)
    {
        $main_rules = [
            'name' => 'required',
            'small_description' => 'required',
            'authentication_level' => 'required',
            'node_type' => 'required',
            'node_status' => 'required',
        ];
        $current_node_type = (new Node_Type())->NODE_TYPES()->firstWhere('id', $request->node_type);
        $current_node = !empty($request->id) ? Node::find((int) $request->id) : null;
        $validator = Validator::make($request->all(), isset($current_node_type['rules']) ? $current_node_type['rules'] + $main_rules : $main_rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request->merge([
            'permission_id' => empty($request->permission_id) ? 0 : $request->permission_id,
        ] + $this->tenancy->addTenantIdToCurrentItem(\optional(\auth()->user()->land)->id));
        Node::updateOrCreate(['id' => $request->id], $request->except(
            isset($current_node_type['rules']) ? \collect($current_node_type['rules'])
                ->keys()->toArray() : []
        ) + [
            'properties' => (new Node_Type())->handler($current_node_type['handle'], $request->all()),
            'uuid' => !empty($current_node->uuid) ? $current_node->uuid : Str::random(50),
        ])
            ->updatePageLink();

        return \redirect()->route('viewNodes');
    }

    public function node(Node $node)
    {
        return $this->index($node);
    }

    public function delete(Node $node)
    {
        $node->delete();
        Session::flash('message', 'The node was deleted successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->route('viewNodes');
    }
}
