<?php

namespace Zijinghua\Zfilesystem\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request)
    {
        return [
            'keyword' => $this->keyword,
            'value' => $this->value,
            'remark' => $this->remark,
        ];
    }
}
