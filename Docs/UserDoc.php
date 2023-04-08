<?php

namespace Kernel\Docs;

use Kernel\BaseDoc;

class UserDoc extends BaseDoc
{
    public static function login(): array
    {
        return self::doc(
            request: [
                [
                    'value' => 'username',
                    'label' => '账号',
                    'type' => self::STRING,
                    'required' => true,
                    'desc' => ''
                ],
                [
                    'value' => 'password',
                    'label' => '密码',
                    'type' => self::STRING,
                    'required' => true,
                    'desc' => ''
                ]
            ]
        );
    }
}
