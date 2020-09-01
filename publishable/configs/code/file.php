<?php
return [
    'upload' => [
        'submit' => [
            'success' => [
                'http_code' => 201,
                'code' => 'ZBASEMENT_CODE_FILE_UPLOAD_SUBMIT_SUBMIT_SUCCESS',
                'status' => true,
                'message' => '文件上传成功!'
            ],
            'failed' => [
                'http_code' => 403,
                'code' => 'ZBASEMENT_CODE_FILE_UPLOAD_SUBMIT_SUBMIT_FAILED',
                'status' => false,
                'message' => '文件上传失败!'
            ],
        ],
        'load' => [
            'rules' => [
                'success' => [
                    'http_code' => 200,
                    'code' => 'ZBASEMENT_CODE_FILE_UPLOAD_LOAD_RULES_SUCCESS',
                    'status' => true,
                    'message' => 'FILE数据插入操作所需验证规则加载成功!'
                ],
                'failed' => [
                    'http_code' => 403,
                    'code' => 'ZBASEMENT_CODE_FILE_UPLOAD_LOAD_RULES_FAILED',
                    'status' => false,
                    'message' => 'FILE数据插入操作所需验证规则加载失败!'
                ],

            ],
            'messages' => [
                'success' => [
                    'http_code' => 200,
                    'code' => 'ZBASEMENT_CODE_FILE_UPLOAD_LOAD_MESSAGES_SUCCESS',
                    'status' => true,
                    'message' => 'FILE数据插入操作所需验证规则的提示信息加载成功!'
                ],
                'failed' => [
                    'http_code' => 403,
                    'code' => 'ZBASEMENT_CODE_FILE_UPLOAD_LOAD_MESSAGES_FAILED',
                    'status' => false,
                    'message' => 'FILE数据插入操作所需验证规则的提示信息加载失败!'
                ],

            ],
        ],
        'validation' => [
            'failed' => [
                'http_code' => 422,
                'code' => 'ZBASEMENT_CODE_FILE_UPLOAD_VALIDATION_FAILED',
                'status' => false,
                'message' => '保存文件数据时输入参数验证失败!'
            ]

        ],
    ],
    'show' => [
        'submit' => [
            'success' => [
                'http_code' => 201,
                'code' => 'ZBASEMENT_CODE_FILE_SHOW_SUBMIT_SUBMIT_SUCCESS',
                'status' => true,
                'message' => '文件获取成功!'
            ],
            'failed' => [
                'http_code' => 403,
                'code' => 'ZBASEMENT_CODE_FILE_SHOW_SUBMIT_FAILED',
                'status' => false,
                'message' => '文件获取失败!'
            ],
        ],
        'load' => [
            'rules' => [
                'success' => [
                    'http_code' => 200,
                    'code' => 'ZBASEMENT_CODE_FILE_SHOW_LOAD_RULES_SUCCESS',
                    'status' => true,
                    'message' => 'FILE数据查询操作所需验证规则加载成功!'
                ],
                'failed' => [
                    'http_code' => 403,
                    'code' => 'ZBASEMENT_CODE_FILE_SHOW_LOAD_RULES_FAILED',
                    'status' => false,
                    'message' => 'FILE数据查询操作所需验证规则加载失败!'
                ],

            ],
            'messages' => [
                'success' => [
                    'http_code' => 200,
                    'code' => 'ZBASEMENT_CODE_FILE_SHOW_LOAD_MESSAGES_SUCCESS',
                    'status' => true,
                    'message' => 'FILE数据插入操作所需验证规则的提示信息加载成功!'
                ],
                'failed' => [
                    'http_code' => 403,
                    'code' => 'ZBASEMENT_CODE_FILE_SHOW_LOAD_MESSAGES_FAILED',
                    'status' => false,
                    'message' => 'FILE数据插入操作所需验证规则的提示信息加载失败!'
                ],

            ],
        ],
        'validation' => [
            'failed' => [
                'http_code' => 422,
                'code' => 'ZBASEMENT_CODE_FILE_SHOW_VALIDATION_FAILED',
                'status' => false,
                'message' => '保存文件数据时输入参数验证失败!'
            ]

        ],
    ]
];
