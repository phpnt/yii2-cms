<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.08.2018
 * Time: 19:32
 */

use yii\bootstrap\Modal;
use common\widgets\Elfinder\ElFinder;

/* @var $this yii\web\View */
?>
<?php
Modal::begin([
    'id' => 'file-manager',
    'size' => 'modal-lg',
    'header' => '<h2 class="text-center">' . Yii::t('app', 'Файловый менеджер') . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);

$options = [
    'roots' => [
        [
            'alias' => 'BACKEND',
            'driver' => 'LocalFileSystem',
            'path' => Yii::getAlias('@backend/web'),
            'URL' => Yii::getAlias('@backend/web'),
            'mimeDetect' => 'internal',
            'imgLib' => 'gd',
            'accessControl' => function ($attr, $path) {
                // hide files/folders which begins with dot
                return (strpos(basename($path), '.') === 0) ?
                    !($attr == 'read' || $attr == 'write') :
                    null;
                return ($attr == 'locked');
            },
            'attributes' => [
                [
                    'pattern' => '/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
                [
                    'pattern' => '/assets/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
                [
                    'pattern' => '/css/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
                [
                    'pattern' => '/js/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
                [
                    'pattern' => '/fonts/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
            ]
        ],
        [
            'alias' => 'FRONTEND',
            'driver' => 'LocalFileSystem',
            'path' => Yii::getAlias('@frontend/web'),
            'URL' => Yii::getAlias('@frontend/web'),
            'mimeDetect' => 'internal',
            'imgLib' => 'gd',
            'accessControl' => function ($attr, $path) {
                // hide files/folders which begins with dot
                return (strpos(basename($path), '.') === 0) ?
                    !($attr == 'read' || $attr == 'write') :
                    null;
            },
            'attributes' => [
                [
                    'pattern' => '/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
                [
                    'pattern' => '/uploads/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
                [
                    'pattern' => '/assets/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
                [
                    'pattern' => '/assets/.*/',
                    'read'    => true,
                    'write'   => false,
                    'locked'  => true,
                    'hidden'  => false
                ],
                [
                    'pattern' => '/css/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
                [
                    'pattern' => '/js/',
                    'read'    => true,
                    'write'   => Yii::$app->user->can('admin'),
                    'locked'  => !Yii::$app->user->can('admin'),
                    'hidden'  => false
                ],
            ]
        ],
    ],
];
?>

<?= ElFinder::widget([
    'id' => 'file-manager-modal',
    'elfinderOptions' => $options,
    'settings' => [
        'height' => 400,
        'commands' => ['*'],
        'commandsOptions' => [
            'getfile' => [
                'multiple' => true
            ]
        ]
    ],
]) ?>
    <div class="clearfix"></div>
<?php
Modal::end();