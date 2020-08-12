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

    public function store($params)
    {
        $data = $params->only(['keyword', 'value', 'remark']);
        $config = $this->config->updateOrCreate(
            [
                'keyword' => $data->get('keyword'),
                'value' => $data->get('value')
            ],
            $data->all()
        );
        return $config;
    }
}
