<?php

namespace Zijinghua\Zfilesystem\Repositories;

use Zijinghua\Zfilesystem\Models\Config;

class ConfigRepository
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config->newQuery();
    }
    /**
     * get config list
     *
     * @return void
     */
    public function getList()
    {
        $config = $this->config
            ->orderByDesc('created_at')
            ->get();
        return $config;
    }
}
