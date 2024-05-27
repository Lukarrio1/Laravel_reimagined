<?php

namespace App\Models\Node;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Node_Type extends Model
{
    use HasFactory;

    public const ControllerExceptionList = [];

    public function NODE_TYPES($filler = null)
    {
        // gets the controller methods
        $methods = \collect((new Node())->getControllerMethods());
        $options = '';
        // creates a string that has the controller name and method as options
        $methods->filter(fn ($con, $loc) => \in_array('Api', \explode('\\', $loc)))
            ->each(function ($controller, $location) use (&$options, $filler) {
                collect($controller)
                    ->each(function ($method) use ($location, &$options, $filler) {
                        $selected = !empty($filler) && optional(\optional($filler)->properties['value'])->route_function == $location . '::' . $method ? "selected" : '';
                        $options .= "<option value='" . $location . '::' . $method . "' $selected>" . $location . "::" . $method . "</option>";
                    });
            });
        $node_pages_options = '';
        // creates a string that has node pages as option
        Node::where('node_type', 3)->get()->each(function ($page) use (&$node_pages_options, $filler) {
            $selected = !empty($filler) && optional(\optional($filler)->properties['value'])->node_page == $page->id ? "selected" : '';
            $node_pages_options .= "<option value='" . $page->id . "'$selected>" . $page->name . "</option>";
        });
        // creates a string that has route method as option
        $route_method_options = '';
        collect(['put', 'post', 'get', 'delete'])->each(function ($route_method) use (&$route_method_options, $filler) {
            $selected = !empty($filler) && \optional(\optional($filler)->properties['value'])->route_method == $route_method ? 'selected' : '';
            $route_method_options .= "<option value='$route_method' $selected>$route_method</option>";
        });
        $node_route = empty($filler) ? '' : \collect(\explode('/', \optional(\optional($filler)->properties['value'])->node_route))
            ->filter(function ($dt, $key) use ($filler) {
                if (array_search('api', \explode('/', \optional(\optional($filler)->properties['value'])->node_route)) < $key) {
                    return true;
                }
                return false;
            })->join('/');
        $node_page_name = empty($filler) ? '' : \optional(\optional($filler)->properties['value'])->node_page_name;
        $page_link = empty($filler) ? '' : \optional(\optional($filler)->properties['value'])->page_link;
        $node_audit_message = empty($filler) ? '' : \optional(\optional($filler)->properties['value'])->node_audit_message;
        $actual_component = empty($filler) ? '' : \optional(\optional($filler)->properties['value'])->actual_component;
        $link_page_node_route = empty($filler) ? '' : \optional(\optional($filler)->properties['value'])->node_route;
        $app_auditing = (int) optional(collect(Cache::get('settings'))
            ->where('key', 'app_auditing')->first())
            ->getSettingValue('last') == 1 ? "<div class='mb-3'>
                    <label for='route ' class='form-label'>Node Audit Message <small>( use {name} for user name, {at} for the current time and date.)</small></label>
                    <input
                    type='text' class='form-control'
                     id='node_audit_message' aria-describedby='node_audit_message' name='node_audit_message'
                     value='" . $node_audit_message . "'>
                </div>" : '';

        $node_message_auditing_rules =  (int) optional(collect(Cache::get('settings'))
            ->where('key', 'app_auditing')->first())
            ->getSettingValue('last') == 1 ? ['location' => 'properties'] : [];
        return collect([
            'link' => [
                'id' => 2,
                'rules' => ['node_route' => 'required', 'node_page' => 'required'],
                'handle' => [
                    'node_route' => ['location' => 'properties'],
                    'node_page' => ['location' => 'properties'],
                    'node_page_name' => ['location' => 'properties'],
                ],
                'extra_html' => "<div>
                 <input
                    type='hidden' name='node_page_name'
                    id='node_page_name'
                     value='" . $node_page_name . "'>

                  <div class='mb-3'>
                    <label for='route ' class='form-label'>Node route</label>
                    <input
                    type='text' class='form-control'
                     id='node_route' aria-describedby='node_name' name='node_route'
                     value='" . $link_page_node_route . "' required>
                </div>
                 <div class='mb-3'>
                      <label for='node_page' class='form-label'>Node Page</label>
                      <select id='node_page' class='form-select' name='node_page' required>
                       $node_pages_options
                      </select>
                  </div>
                </div>",
            ],
            'route' => [
                'id' => 1,
                // storage handler
                'handle' => [
                    'node_route' => ['location' => 'properties'],
                    'route_function' => ['location' => 'properties'],
                    'route_method' => ['location' => 'properties'],
                    'node_audit_message' => $node_message_auditing_rules,
                ],
                'rules' => ['node_route' => 'required', 'route_function' => 'required', 'route_method' => 'required', 'node_audit_message' => ''],
                'extra_html' => "<div>
                 <div class='mb-3'>
                    <label for='route ' class='form-label'>Node route <small>(you can add parameters to the route eg. test/{param}/{param1})</small></label>
                    <input
                    type='text' class='form-control'
                     id='node_route' aria-describedby='node_name' name='node_route'
                     value='" . $node_route . "' required>
                </div>
                 $app_auditing
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
            'page' => [
                'id' => 3,
                'rules' => [],
                'handle' => [
                    'page_link' => ['location' => 'properties'],
                    'actual_component' => ['location' => 'properties']
                ],
                'extra_html' => "<div><input
                    type='hidden' name='page_link'
                    id='page_link'
                     value='" . $page_link . "'>
                     <div class='mb-3'>
                    <label for='route ' class='form-label'>Actual Framework Component Name (react)</small></label>
                    <input
                    type='text' class='form-control'
                     id='actual_component' aria-describedby='actual_component' name='actual_component'
                     value='" . $actual_component . "' required>
                </div>
                     </div>",
            ],
            'component' => ['id' => 4, 'rules' => [], 'handle' => []],
        ]);
    }

    public function handler($handler, $data)
    {
        $storage = \collect([]);
        collect($handler)->keys()->each(function ($key) use ($storage, $data) {
            if (!empty($data[$key]))
                $storage->put($key, $data[$key]);
        });
        return \json_encode($storage->toArray());
    }

    public function extraScripts()
    {
        // this handles the setting of the selected node page as????
        return \collect([
            "<script>
          document.addEventListener('DOMContentLoaded', () => {
          setTimeout(() => {
          const node_page_name = document.querySelector('#node_page_name');
          const node_page = document.querySelector('#node_page');
          const selectedOption = node_page.options[node_page.selectedIndex];
          node_page_name.value = selectedOption.innerHTML;
          node_page.addEventListener('change',(event)=>{
            const selectedOption = node_page.options[node_page.selectedIndex];
            node_page_name.value = selectedOption.innerHTML;
          })
       }, 1000);
            });
       </script>",
        ]);
    }
}
