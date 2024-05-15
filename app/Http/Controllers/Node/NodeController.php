<?php

namespace App\Http\Controllers\Node;

use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Models\Node\Node_Type;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Cache\CacheController;

class NodeController extends Controller
{
    public $cache;

    public function __construct()
    {

        $this->cache = new CacheController();
    }
    public function index($node = null)
    {

        $translate = [
            'name' => 'name',
            'description' => 'small_description',
            'type' => 'node_type',
        ];

        $translateExamples = [
            'name' => 'Link 1',
            'description' => 'link to the home page',
            'type' => 'Link',
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
            ->filter(fn($section) => !empty($section)) // Filter out empty sections
            ->map(function ($section) {
                return explode(':', $section);
            });

        // Query for Nodes and apply filters based on search parameters
        $nodes = Node::query();

        $searchParams->each(function ($section) use ($nodes, $translate) {
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
        });

        return \view('Nodes.View', [
            'types' => (new Node_Type())->NODE_TYPES($node),
            'authentication_levels' => Node::Authentication_Levels,
            'node_statuses' => Node::NODE_STATUS,
            'nodes' => $nodes->latest()->take(\request('load_more'))->get(),
            'node' => $node,
            'extra_scripts' => (new Node_Type())->extraScripts()->join(''),
            'permissions' => Permission::all(),
            'search_placeholder' => $searchPlaceholder,
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
        $validator = Validator::make($request->all(), isset($current_node_type['rules']) ? $current_node_type['rules'] + $main_rules : $main_rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request->merge(['permission_id' => empty($request->permission_id) ? 0 : $request->permission_id]);
        Node::updateOrCreate(['id' => $request->id], $request->except(
            isset($current_node_type['rules']) ?
            \collect($current_node_type['rules'])->keys()->toArray()
            : []
        ) + ['properties' => (new Node_Type())
                ->handler($current_node_type['handle'], $request->all())])
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
