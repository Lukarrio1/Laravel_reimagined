<?php

namespace App\Http\Controllers\Node;

use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Models\Node\Node_Type;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class NodeController extends Controller
{
    public function index($node = null)
    {
        $translate = ['name' => 'name', 'description' => 'small_description', 'type' => 'node_type'];
        $translate_eg = ['name' => 'Link 1', 'description' => 'link to the home page', 'type' => '2'];

        $search_placeholder = \collect($translate)->keys()->map(function ($key, $idx) use ($translate, $translate_eg) {
            if ($idx == 0) {
                return '|' . $key . ":$translate_eg[$key]";
            }
            if ($idx + 1 == count($translate)) {
                return $key . ":$translate_eg[$key]|";
            }
            return $key . ":$translate_eg[$key]";
        })->join('|');

        $search_params = collect(explode('|', \request()->get('search')))
            ->filter(fn($sec) => !empty($sec))
            ->map(function ($sec) {
                return \explode(':', $sec);
            });

        $nodes = Node::query()
        // ->when($search, fn($q) => $q->where('name', 'LIKE', '%' . $search . '%'))
        ;
        collect($search_params)->each(function ($sec) use ($nodes, $translate) {
            $sec = \collect($sec);
            $convert_value = $translate[$sec->first()] == "node_type"?\array_search(\ucfirst($sec->last()), Node::NODE_TYPE) : $sec->last();
            $nodes->where($translate[$sec->first()], 'LIKE', '%' . $convert_value . '%');
        });

        return \view('Nodes.View', [
            'types' => (new Node_Type())->NODE_TYPES($node),
            'authentication_levels' => Node::Authentication_Levels,
            'node_statuses' => Node::NODE_STATUS,
            'nodes' => $nodes->latest()->get()
            // ->filter(fn($node)=>$node->node_type['value']==1)
            ,
            'node' => $node,
            'extra_scripts' => (new Node_Type())->extraScripts()->join(''),
            'permissions' => Permission::all(),
            'search_placeholder' => $search_placeholder,
        ]);
    }

    public function save(Request $request)
    {
        // dd($request->all());
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

        Node::updateOrCreate(['id' => $request->id], $request->except(
            isset($current_node_type['rules']) ?
            \collect($current_node_type['rules'])->keys()->toArray()
            : []) + ['properties' => (new Node_Type())
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
        return \redirect()->route('viewNodes');
    }

    public function testerFunction(Node $param)
    {

        return Node::query()->get();
    }
}
