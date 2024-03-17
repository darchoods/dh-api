<?php

// key = permission_name
// value = enabled by default?
return [
    '*' => true,
        'core.*' => false,
            'core.admin' => true,

        'api.*' => false,
            'api.admin' => true,
            'api.key' => true,

        'qdb.*' => false,
            'qdb.admin' => true,

        'news.*' => false,
            'news.admin' => true,
];