<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\assets\CommonAsset;

use common\components\FooterLayout;
use common\components\NavBarLayout;

use frontend\assets\AppAsset;
use common\widgets\Alert;

CommonAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
NavBar::begin([
    'brandLabel' => NavBarLayout::brandLabel(),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
$menuItems = [
    ['label' => 'Inicio', 'url' => ['/site/index']],
    ['label' => 'Equipo', 'url' => ['/equipo/index']],
    ['label' => 'Calendario', 'url' => ['/site/calendario']],
    [
        'label' => 'Únete/Lucha',
        'items' => [
            ['label' => 'Únete a nosotros', 'url' => ['/site/unete']],
            ['label' => 'Lucha contra nuestro equipo', 'url' => ['/site/luchar']]
        ]
    ]
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = NavBarLayout::loginButton();
} else {
    $menuItems[] = [
        'label' => 'Mi cuenta',
        'items' => [
            '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->nombre . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>',
            ['label' => 'Mi perfil', 'url' => ['/site/perfil']]
        ]
    ];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => $menuItems,
]);
NavBar::end();
?>

<div class="container-fluid">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>
</div>

<?= FooterLayout::mostrar() ?>

<?php $this->endBody() ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-120627051-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-120627051-1');
    </script>
</body>
</html>
<?php $this->endPage() ?>
