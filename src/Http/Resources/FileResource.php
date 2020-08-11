<?php

namespace Zijinghua\Zfilesystem\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
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
            'uuid' => $this->uuid,
            'url'  => $this->real_path,
            'filename' => $this->filename,
            'type' => $this->type,
        ];
    }
}
