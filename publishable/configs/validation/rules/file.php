<?php
return [
    'file' => [
        [
            'rule' => [
                'required',
                'file',
                'max:10240'
            ],
            'action' => [
                'upload',
            ],
        ],
    ],
    'file_md5' => [
        [
            'rule' => [
                'required',
                'string',
                'max:32'
            ],
            'action' => [
                'upload',
            ],
        ],
    ],
    'use_type' => [
        [
            'rule' => [
                'string',
            ],
            'action' => [
                'upload',
            ],
        ],
    ],
    'resource_keyword' => [
        [
            'rule' => [
                'required_with:use_type',
            ],
            'action' => [
                'upload',
            ],
        ],
    ],
    'filemd5' => [
        [
            'rule' => [
                'required_without:filename_prefix,filename_extension',
                'string'
            ],
            'action' => [
                'show',
            ],
        ]
    ],
    'filename_prefix' => [
        [
            'rule' => [
                'required_without:filemd5',
                'string'
            ],
            'action' => [
                'show',
            ],
        ]
    ],
    'filename_extension' => [
        [
            'rule' => [
                'required_with:filename_prefix',
                'string'
            ],
            'action' => [
                'show',
            ],
        ]
    ],
];
