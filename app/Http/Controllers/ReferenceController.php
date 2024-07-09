<?php

namespace App\Http\Controllers;

use App\Models\Export;
use App\Models\Node\Node;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function index()
    {
        $owner_model = \request()->get('owner_model', '');
        $model_fields = !empty($owner_model) ? (new Export())->getAllTableColumns((new $owner_model())->table_name) : null;
        $owners = (new $owner_model())->all();
        return view('Reference.View', [
            'models' => (new Node())->getAllModels(),
            'model_fields' => $model_fields,
            'reference' => null,
            'owners' => $owners
        ]);
    }
}
