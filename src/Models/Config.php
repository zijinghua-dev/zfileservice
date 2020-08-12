<?php

namespace Zijinghua\Zfilesystem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zijinghua\Zfilesystem\Models\UuidTrait;

class Config extends Model
{
    use SoftDeletes, UuidTrait;
    protected $table = 'configs';

    protected $fillable = [
        'uuid', 'keyword', 'value', 'remark'
    ];

    protected $hidden = [
        'id'
    ];
}
