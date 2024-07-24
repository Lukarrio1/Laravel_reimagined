<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Export;
use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Models\Reference\Reference;
use Illuminate\Support\Facades\Cache;

class ReferenceController extends Controller
{
    public function index()
    {
        $owner_model = \request()->get('owner_model', '');
        $owned_model = \request()->get('owned_model', '');
        $model_fields = !empty($owner_model) ? (new Export())->getAllTableColumns((new $owner_model())->table_name) : null;
        $owners = !empty($owner_model) ? (new $owner_model())->all() : [];
        $owned_model_fields =
            !empty($owned_model) ? (new Export())->getAllTableColumns((new $owned_model())->table_name) : [];
        return view('Reference.View', [
            'models' => (new Node())->getAllModels(),
            'model_fields' => $model_fields ?? [],
            'reference' => null,
            'owners' => $owners ?? [],
            'owned_model_fields' => $owned_model_fields ?? [],
            'types' => optional(collect(Cache::get('settings'))->where('key', 'reference_types')->first())->getSettingValue() ?? []
        ]);
    }
    public function index2()
    {
        $owner_model = \request()->get('owner_model');
        $owned_model = \request()->get('owned_model');
        $model_fields =
            isset($owner_model) && $owner_model != "null" ? (new Export())->getAllTableColumns((new $owner_model())->table_name) : null;
        $owners
            = isset($owner_model) && $owner_model != "null" ? (new $owner_model())->all() : [];
        $owned = isset($owned_model) && $owned_model != "null" ? (new $owned_model())->all() : [];
        $owned_model_fields =
            !empty($owned_model) ? (new Export())->getAllTableColumns((new $owned_model())->table_name) : [];
        $references = Reference::latest()->get();
        return  [
            'models' => (new Node())->getAllModels(),
            'model_fields' => $model_fields ?? [],
            'reference' => null,
            'references' => $references,
            'owners' => $owners ?? [],
            'owned' => $owned,
            'owned_model_fields' => $owned_model_fields ?? [],
            'types' => optional(collect(Cache::get('settings'))->where('key', 'reference_types')->first())->getSettingValue() ?? []
        ];
    }

    public function save(Request $request)
    {
        $owner_model = \request()->get('owner_model', '');
        $owned_model = \request()->get('owned_model', '');
        $owner_item = !empty($owner_model) ? (new $owner_model())->find($request->owner_item)->{request('owner_model_display_aid')} : null;
        $owned_item = !empty($owned_model) ? (new $owned_model())->find($request->owned_item)->{request('owned_model_display_aid')} : null;
        $description = $owner_item . " is the " . $request->type . ' of ' . $owned_item;
        $reference = Reference::updateOrCreate(['id' => $request->id], [
            "owner_id" => $request->owner_item,
            "owner_model" => $request->owner_model,
            "owned_model" => $request->owned_model,
            "owned_id" => $request->owned_item,
            "type" => $request->type,
            'description' => $description,
            // 'has_many' => (int)$request->has_many
        ]);
        return \redirect()->route('viewReferences');
    }

    public function delete(Reference $reference)
    {
        $reference->delete();
        return \response()->json([]);
    }
}
