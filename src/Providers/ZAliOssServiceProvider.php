<?php

namespace Zijinghua\Zfilesystem\Providers;

use Zijinghua\Zfilesystem\Http\Services\AliOss\ZAliOssAdapter;
use Illuminate\Support\Facades\Storage;
use Jacobcyl\AliOSS\AliOssAdapter;
use Jacobcyl\AliOSS\Plugins\PutFile;
use Jacobcyl\AliOSS\Plugins\PutRemoteFile;
use League\Flysystem\Filesystem;
use OSS\OssClient;
use Illuminate\Support\ServiceProvider;

class ZAliOssServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('oss', function($app, $config)
        {
            $accessId  = $config['access_id'];
            $accessKey = $config['access_key'];

            $cdnDomain = empty($config['cdnDomain']) ? '' : $config['cdnDomain'];
            $bucket    = $config['bucket'];
            $ssl       = empty($config['ssl']) ? false : $config['ssl'];
            $isCname   = empty($config['isCName']) ? false : $config['isCName'];
            $debug     = empty($config['debug']) ? false : $config['debug'];
            $prefix    = empty($config['prefix']) ? null : $config['prefix'];

            $endPoint  = $config['endpoint']; // 默认作为外部节点
            $epInternal= $isCname?$cdnDomain:(empty($config['endpoint_internal']) ? $endPoint : $config['endpoint_internal']); // 内部节点

            if($debug) Log::debug('OSS config:', $config);
            $client  = new OssClient($accessId, $accessKey, $epInternal, $isCname);
            $adapter = new ZAliOssAdapter($client, $bucket, $endPoint, $ssl, $isCname, $debug, $cdnDomain, $prefix);

            //Log::debug($client);
            $filesystem =  new Filesystem($adapter);

            $filesystem->addPlugin(new PutFile());
            $filesystem->addPlugin(new PutRemoteFile());
            //$filesystem->addPlugin(new CallBack());
            return $filesystem;
        });
    }

    public function register()
    {
    }
}
