<?php

return [
    'index' => [
        'submit' => [
            'success' => [
                'http_code' => 200,
                'code' => 'ZBASEMENT_CODE_CONFIG_INDEX_SUBMIT_SUCCESS',
                'status' => true,
                'message' => '配置获取成功!'
            ],
            'failed' => [
                'http_code' => 403,
                'code' => 'ZBASEMENT_CODE_CONFIG_INDEX_SUBMIT_FAILED',
                'status' => false,
                'message' => '配置获取失败!'
            ],
        ],
        'load' => [
            'rules' => [
                'success' => [
                    'http_code' => 200,
                    'code' => 'ZBASEMENT_CODE_CONFIG_INDEX_LOAD_RULES_SUCCESS',
                    'status' => true,
                    'message' => '配置数据查询操作所需验证规则加载成功!'
                ],
                'failed' => [
                    'http_code' => 403,
                    'code' => 'ZBASEMENT_CODE_CONFIG_INDEX_LOAD_RULES_FAILED',
                    'status' => false,
                    'message' => '配置数据查询操作所需验证规则加载失败!'
                ],
            ],
            'messages' => [
                'success' => [
                    'http_code' => 200,
                    'code' => 'ZBASEMENT_CODE_CONFIG_INDEX_LOAD_MESSAGES_SUCCESS',
                    'status' => true,
                    'message' => '配置数据插入操作所需验证规则的提示信息加载成功!'
                ],
                'failed' => [
                    'http_code' => 403,
                    'code' => 'ZBASEMENT_CODE_CONFIG_INDEX_LOAD_MESSAGES_FAILED',
                    'status' => false,
                    'message' => '配置数据插入操作所需验证规则的提示信息加载失败!'
                ],
            ],
        ],
        'validation' => [
            'failed' => [
                'http_code' => 422,
                'code' => 'ZBASEMENT_CODE_CONFIG_INDEX_VALIDATION_FAILED',
                'status' => false,
                'message' => '保存配置数据时输入参数验证失败!'
            ]
        ],
    ]
];
