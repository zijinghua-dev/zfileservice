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
        if ($this->filename) {
            $filename = $this->filename;
        } else {
            $filename = $this->filename_prefix ?
            $this->filename_prefix . '.' . $this->filename_extension :
            '';
        }
        
        return [
            'file_md5' => $this->file_md5,
            'url'  => $this->real_path,
            'filename' => $filename,
            'type' => $this->type,
        ];
    }
}
