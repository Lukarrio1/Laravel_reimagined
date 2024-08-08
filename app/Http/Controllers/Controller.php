<?php

namespace App\Http\Controllers;

use ReflectionClass;
use App\SendEmailTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use SendEmailTrait;

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize');
    }

    public function getCurrentRoute()
    {
        $currentRoute = join('::', explode('@', Route::currentRouteAction()));
        return Cache::get('routes')
        ->where('properties.value.route_function', $currentRoute)
        ->first();
    }

    public function getValidationRules()
    {
        $rules = [ 'required', 'integer', 'min:3', 'min:5', 'min:10', 'sometimes', 'present', 'max:3', 'max:5', 'max:10' ];
        return $rules;
    }

    public function backupDatabase($databaseName, $databaseUser, $databasePassword, $databaseHost, $databasePort = 3306)
    {
        $backupFileName = $databaseName.'-backup-' . date('Y-m-d-h-m-s') . '.sql';
        $backupFilePath = storage_path('app/backups/'.$databaseName."/" . $backupFileName);

        // Ensure the backups directory exists
        if (!file_exists(dirname($backupFilePath))) {
            mkdir(dirname($backupFilePath), 0755, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%d %s > %s',
            escapeshellarg($databaseUser),
            escapeshellarg($databasePassword),
            escapeshellarg($databaseHost),
            ( int )$databasePort,
            escapeshellarg($databaseName),
            escapeshellarg($backupFilePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return $backupFileName;
        } else {
            throw new \Exception('Error creating database backup.');
        }
    }

    public function getHttpData($url)
    {
        return !empty($url) ? Http::get($url)->json() : [];

    }
}
