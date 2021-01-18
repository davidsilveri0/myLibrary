<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],

    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ],

    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' =>
                [
                    'application/json' => 'yii\web\JsonParser'
                ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            //'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/livro',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET total' => 'total',
                        'DELETE all' => 'delete-all',
                        'GET ano/{ano}' => 'ano',
                    ],

                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{ano}' => '<ano:\\d+>',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/biblioteca',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET total' => 'totalbibliotecas',
                        'GET codigopostal/{cod_postal}' => 'codigopostal',
                        'GET {id}/nome' => 'nome',
                    ],

                    'tokens' => [
                        '{cod_postal}' => '<cod_postal:\\d+>',
                        '{id}' => '<id:\d+>',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/requisicao',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET estado/{estado}' => 'procurar-estado',
                        'GET utilizadoresMaisRequisicoes' => 'utilizadores-mais-requisicoes',
                        'GET requisicoesBiblioteca' => 'requisicoes-biblioteca',
                        'GET tempoRestante' => 'tempo-restante',
                        'GET emrequisicao/{id}' => 'verificar-em-requisicao',
                        'GET total/{id}' => 'total-em-requisicao',
                        'POST create' => 'create-requisicao',
                    ],

                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{estado}' => '<estado:\\d+>',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/utilizador',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST create' => 'create-utilizador',
                        'GET leitor/{numero}' => 'utilizador-numero',
                        'GET bloqueados' => 'bloqueados',
                        'POST login' => 'login',
                        'POST editar/{id}' => 'editar',
                        'GET dadosLeitor/{id}' => 'leitor',
                    ],

                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{numero}' => '<numero:\\w+>',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/favorito',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET utilizador/{id}' => 'utilizador-favs'
                    ],

                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/comentario',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET utilizador/{id}' => 'utilizador-coments'
                    ],

                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST email/{id}' => 'editar-email'
                    ],

                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/requisicao_livro',
                    'pluralize' => false,
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/autor',
                    'pluralize' => false,
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/editora',
                    'pluralize' => false,
                ],

            ],
        ],
    ],
    'params' => $params,
];
