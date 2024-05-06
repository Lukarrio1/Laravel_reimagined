<?php

namespace App\Models\Node;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Node_Type extends Model
{
    use HasFactory;

    public function NODE_TYPES($filler=null)
    {
        $methods = \collect((new Node())->getControllerMethods());
        $options = '';
        $methods->each(function ($controller, $location) use (&$options ,$filler) {
            collect($controller)->each(function ($method) use ($location, &$options,$filler) {
                $selected = !empty($filler) &&optional(\optional($filler)->properties['value'])->route_function == $location . '::' . $method ?"selected":'';
                $options .= "<option value='" . $location . '::' . $method . "' $selected>" . $location . "::" . $method . "</option>";
            });
        });

        $route_method_options ='';
        collect(['put','post','get','delete'])->each(function($route_method) use(&$route_method_options,$filler){
            $selected =!empty($filler) && \optional(\optional($filler)->properties['value'])->route_method==$route_method? 'selected' :'';
            $route_method_options.= "<option value='$route_method' $selected>$route_method</option>";
        });
        $node_route = empty($filler)?'':\optional(\optional($filler)->properties['value'])->node_route;
        return collect([
            'link' => ['id' => 2, 'rules' => ['route' => 'required'], 'handle' => []],
            'route' => [
                'id' => 1,
                // storage handler
                'handle' => [
                    'node_route' => ['location' => 'properties'],
                    'route_function' => ['location' => 'properties'],
                    'route_method' => ['location' => 'properties'],
                ],
                'rules' => ['node_route' => 'required', 'route_function' => 'required','route_method' => 'required'],
                'extra_html' => "<div>
                 <div class='mb-3'>
                    <label for='route ' class='form-label'>Node route</label>
                    <input
                    type='text' class='form-control'
                     id='node_route' aria-describedby='node_name' name='node_route'
                     value='".$node_route."' required>
                </div>
                  <div class='mb-3'>
                      <label for='route_function' class='form-label'>Route Function</label>
                      <select id='route_function' class='form-select' name='route_function' required>
                        $options
                      </select>
                  </div>
                  <div class='mb-3'>
                      <label for='route_method' class='form-label'>Route Method</label>
                      <select id='route_method' class='form-select' name='route_method' required>
                      $route_method_options
                      </select>
                  </div>
                  </div>",
            ],
            'page' => ['id' => 3, 'rules' => [], 'handle' => []],
            'component' => ['id' => 4, 'rules' => [], 'handle' => []],
        ]);
    }

    public function handler($handler, $data)
    {
        $storage = \collect([]);
        collect($handler)->keys()->each(function ($key) use ($storage, $data) {
            $storage->put($key, $data[$key]);
        });
        return \json_encode($storage->toArray());
    }
}
