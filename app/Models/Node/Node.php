<?php

namespace App\Models\Node;

use App\TenantTrait;
use ReflectionClass;
use ReflectionMethod;
use App\Models\Setting;
use App\HasCustomPagination;
use App\Models\Tenant\Tenant;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Node extends Model
{
    use HasFactory;
    use HasCustomPagination;
    use TenantTrait;

    public function __construct()
    {
        // $this->initializeTenancy();
    }
    protected $guarded = ['id'];

    public const Authentication_Levels = [
        1 => 'Authenticated',
        0 => 'Unauthenticated',
        2 => 'Public',
    ];
    public const NODE_STATUS = [1 => 'Enabled', 0 => 'Disabled'];

    public const NODE_TYPE = [1 => 'Route', 2 => 'Link', 3 => 'Page', 4 => 'Component', 5 => 'Layout'];

    public function getAllControllerClasses()
    {
        // Path to the Controllers directory
        $controllersPath = app_path('Http/Controllers');

        // Get all PHP files in the Controllers directory
        $controllerFiles = File::allFiles($controllersPath);

        // Base namespace for controllers
        $baseNamespace = 'App\\Http\\Controllers\\';

        // Use a set ( associative array ) for unique controller classes
        $controllerClasses = [];

        // Loop through the files and extract class names
        foreach ($controllerFiles as $file) {
            // Remove base directory to get relative path
            $relativePath = ltrim(str_replace($controllersPath, '', $file->getPathname()), '/');

            // Convert to namespace structure by replacing '/' with '\\'
            $className = $baseNamespace . str_replace(['/', '.php'], ['\\', ''], $relativePath);

            // Avoid duplicates by using associative array keys
            if (!isset($controllerClasses[$className])) {
                $controllerClasses[$className] = true;
            }
        }

        // Return the keys of the associative array, which are the unique class names
        return array_keys($controllerClasses);
    }

    public function getControllerMethods()
    {
        $controllerClasses = $this->getAllControllerClasses();
        $controllerMethods = [];

        foreach ($controllerClasses as $controllerClass) {
            if (class_exists($controllerClass)) {
                $reflectionClass = new ReflectionClass($controllerClass);

                // Get all public methods ( excluding inherited ones like __construct )
                $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

                foreach ($methods as $method) {
                    // Skip inherited methods ( like those from parent classes or traits )
                    if ($method->getDeclaringClass()->getName() === $controllerClass) {
                        $controllerMethods[$controllerClass][] = $method->getName();
                    }
                }
            }
        }

        return $controllerMethods;
    }

    public function getAllModels()
    {
        $modelsPath = app_path('Models');
        $modelFiles = File::allFiles($modelsPath);
        $modelClasses = array_map(
            function ($file) {
                $relativePath = str_replace(app_path(), '', $file->getRealPath());
                $className = 'App' . str_replace(['/', '.php'], ['\\', ''], $relativePath);
                return $className;
            },
            $modelFiles
        );
        return $modelClasses;
    }

    public function getAuthenticationLevelAttribute($value)
    {
        return ['value' => $value, 'human_value' => self::Authentication_Levels[$value]];
    }

    public function getNodeTypeAttribute($value)
    {
        return ['value' => $value, 'human_value' => self::NODE_TYPE[$value]];
    }

    public function getPropertiesAttribute($value)
    {
        // An item
        return [
            'value' => $this->addAppUrlToNodeRoute(\json_decode($value)),
            'html_value' => "<ul class='list-group list-group-flush'>" . \collect($this->addAppUrlToNodeRoute(json_decode($value)))->map(
                function ($value, $key) {
                    return "<li class='list-group-item'>" . collect(\explode('_', $key))->map(fn ($word) => \ucfirst($word))->join(' ') . "<strong>:</strong> $value </li>";
                }
            )->join('') . '</ul>'
        ];
    }

    public function addAppUrlToNodeRoute($value)
    {
        $value = \collect($value);
        if (\in_array($this->node_type['value'], [1])) {
            $app_url = \collect(Cache::get('settings'))->where('key', 'app_url')->pluck('properties')->first();
            $seg = $this->node_type['value'] == 2 ? '/' : '/api/';
            $value = $value->put('node_route', $app_url . $seg . $value->get('node_route'));
        }

        return \json_decode(\json_encode($value));
    }

    public function getNodeStatusAttribute($value)
    {
        return ['value' => $value, 'human_value' => self::NODE_STATUS[$value]];
    }


    public function getVerbiageAttribute($value)
    {
        $verbiage = collect([]);
        collect(\explode('||', $value))->map(function ($item) use ($verbiage) {
            $segments = collect(\explode(':', $item));
            $verbiage->put($segments->first(), \str_replace('"', '', $segments->filter(fn ($_, $idx) => $idx > 0)->join(':')));
        });
        return ['value' => $value, 'human_value' => $verbiage];
    }

    public function updatePageLink()
    {
        if (\optional($this->node_type)['value'] == 2 && !empty(\optional(optional($this->properties)['value'])->node_page)) {
            $page = Node::find((int) $this->properties['value']->node_page);
            $page->update([
                'properties' => \json_encode([
                    'page_link' => $this->name,
                    'actual_component' => $page->properties['value']->actual_component,
                    'layout_id' => !empty(\optional(optional($page->properties)['value'])->layout_id) ? $page->properties['value']->layout_id : '',
                    'layout_name' => !empty(\optional(optional($page->properties)['value'])->layout_name) ? \optional(optional($page->properties)['value'])->layout_name : ''
                ]),
            ]);
        }
        return $this;
    }

    public function updatePageLayoutName()
    {
        if (\optional($this->node_type)['value'] == 3) {
            $layout = !empty(\optional(optional($this->properties)['value'])->layout_id) ?
                Node::find((int) $this->properties['value']->layout_id) : null;
            $this->update([
                'properties' => \json_encode([
                    'page_link'
                    => \optional($this->properties['value'])->page_link,
                    'actual_component' => \optional($this->properties['value'])->actual_component,
                    'layout_id' => !empty(\optional(optional($this->properties)['value'])->layout_id) ? $this->properties['value']->layout_id : '',
                    'layout_name' => !empty(\optional(optional($this->properties)['value'])->layout_id) ? $layout->name : ''
                ]),
            ]);
        }
        return $this;
    }



    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }
}
