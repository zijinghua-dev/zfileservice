<?php

namespace Zijinghua\Zfilesystem\Http\Services\AliOss;

use Jacobcyl\AliOSS\AliOssAdapter;
use OSS\OssClient;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ZAliOssAdapter extends AliOssAdapter
{
    public function __construct(
        OssClient $client,
        $bucket,
        $endPoint,
        $ssl,
        $cdnDomain,
        $isCname = false,
        $debug = false,
        $prefix = null,
        array $options = []
    ) {
        parent::__construct(
            $client,
            $bucket,
            $endPoint,
            $ssl,
            $cdnDomain,
            $isCname,
            $debug,
            $prefix,
            $options
        );
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function getUrl($path)
    {
        if (!$this->has($path)) {
            throw new FileNotFoundException($path.' not found');
        }
        $path = $this->getPathPrefix() . ltrim($path, '\\/');
        return ( $this->ssl ? 'https://' : 'http://' ) . ( $this->isCname ?
                ( $this->cdnDomain == '' ? $this->endPoint : $this->cdnDomain ) :
                $this->bucket . '.' . $this->endPoint ) . '/' . ltrim($path, '/');
    }
}