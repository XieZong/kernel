<?php

namespace Kernel;

abstract class BaseDoc
{
    protected const REQUEST_HEADER = [
        ['value' => 'value', 'label' => '字段'],
        ['value' => 'label', 'label' => '说明'],
        ['value' => 'type', 'label' => '类型'],
        ['value' => 'required', 'label' => '必填'],
        ['value' => 'desc', 'label' => '备注'],
    ];

    protected const RESPONSE_HEADER = [
        ['value' => 'value', 'label' => '字段'],
        ['value' => 'label', 'label' => '说明'],
        ['value' => 'type', 'label' => '类型'],
        ['value' => 'desc', 'label' => '备注']
    ];

    protected const STRING = 'String';

    protected static function doc($request = [], $response = []): array
    {
        $doc = [];
        $request && $doc['request'] = [
            'header' => self::REQUEST_HEADER,
            'content' => $request
        ];
        $response && $doc['response'] = [
            'header' => self::RESPONSE_HEADER,
            'content' => $response
        ];
        return $doc;
    }
}
