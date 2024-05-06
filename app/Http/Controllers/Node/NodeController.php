<?php

namespace App\Http\Controllers\Node;

use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Models\Node\Node_Type;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NodeController extends Controller
{
    public function index($node=null)
    {
        return \view('Nodes.View', [
            'types' => (new Node_Type())->NODE_TYPES($node),
            'authentication_levels' => Node::Authentication_Levels,
            'node_statuses'=>Node::NODE_STATUS,
            'nodes' => Node::query()->latest()->get()
            // ->filter(fn($node)=>$node->node_type['value']==1)
            ,
            'node'=>$node
        ]);
    }

    public function save(Request $request)
    {
        $main_rules = [
            'name' => 'required',
            'small_description' => 'required',
            'authentication_level' => 'required',
            'node_type' => 'required',
            'node_status'=>'required'
        ];
        $current_node_type = (new Node_Type())->NODE_TYPES()->firstWhere('id', $request->node_type);
        $validator = Validator::make($request->all(), isset($current_node_type['rules']) ? $current_node_type['rules'] + $main_rules : $main_rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Node::updateOrCreate(['id'=>$request->id],$request->except(
            isset($current_node_type['rules']) ?
            \collect($current_node_type['rules'])->keys()->toArray()
            : []) + ['properties' => (new Node_Type())
            ->handler($current_node_type['handle'], $request->all())]);

        return \redirect()->route('viewNodes');
    }

    public function node(Node $node){
       return  $this->index($node);
    }


    public function delete(Node $node){
        $node->delete();
        return \redirect()->route('viewNodes');
    }



    public function testerFunction(Node $param){

        dd($param->toArray());
        return Node::query()->when($param,fn($q)=>$q->where('id',$param->id))->get();
    }
}
