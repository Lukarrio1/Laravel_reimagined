<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    public function todos()
    {
        return ['todos' => Todo::all()];
    }


    public function saveTodo(Request $request)
    {
        Todo::create($request->all());
        return $this->todos();
    }

    public function update(Request $request, Todo $todo)
    {
        $todo->update($request->all());
        return $this->todos();
    }

    public function deleteTodo(Todo $todo)
    {
        $todo->delete();
        return $this->todos();
    }
}
