<?php

use App\Models\Node\Node;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getSetting')) {
    /**
    * Method getSetting
    *
    * @param $key $key [ setting key ]
    * @param $value $value [ setting value ( 'first' or 'last' ) default value is last ]
    * @param $default_value $default_value [ used if value does not exist ]
    *
    * @return mixed
    */

    function getSetting($key = '', $value = 'last', $default_value = null)
    {
        if (!Cache::has('settings')) {
            return null;
        }

        return \optional(Cache::get('settings')
        ->where('key', $key)->first())
        ->getSettingValue($value) ?? $default_value;
    }
}


if (!function_exists('getNode')) {
    /**
    * Method getNode
    *
    * @param $uuid $uuid [ node uuid ]
    * @return Node
    */




    function getNode($uuid = '', $property = '')
    {
        // Fetch the node using the UUID and ensure it's enabled
        $node = Node::query()->enabled()->where('uuid', $uuid)->first();

        // If no node is found, return an empty collection
        if (empty($node)) {
            return collect();
        }

        // Use Laravel's Arr::get to retrieve nested properties safely
        $current_value = Arr::get($node, $property, null);

        // Debug output to check the current value (you can remove this after testing)

        return !empty($property) ? $current_value : $node;
    }

}
