<?php

namespace App\Providers;

use Exception;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('google', function ($app, $config) {
            $options = [];

            if (!empty($config['teamDriveId'] ?? null)) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }

            if (!empty($config['sharedFolderId'] ?? null)) {
                $options['sharedFolderId'] = $config['sharedFolderId'];
            }

            $client = new Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);

            // Masbug Google Drive adapter requires access token to be set explicitly
            // if we are not relying on a json credentials file
            $token = $client->fetchAccessTokenWithRefreshToken($config['refreshToken']);

            if (isset($token['access_token'])) {
                $client->setAccessToken($token);
            } else {
                throw new Exception('Google Drive token error: ' . json_encode($token));
            }

            $service = new Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folderId'] ?? '/', $options);
            $driver = new Filesystem($adapter);

            return new FilesystemAdapter($driver, $adapter);
        });
    }
}
