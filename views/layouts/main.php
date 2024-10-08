<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;


AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => Yii::getAlias('@web/css/style.css')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header" class="page-header">
    <nav class="main-nav">
        <a href='#' class="header-logo">
            <img class="logo-image" src="img/logotype.png" width=227 height=60 alt="taskforce">
        </a>
        <?php if (Yii::$app->controller->id !== 'registration'): ?>
        <?php $user = Yii::$app->user->identity; ?>
        <div class="nav-wrapper">
            <ul class="nav-list">
                <li class="list-item list-item--active">
                    <a class="link link--nav">Новое</a>
                </li>
                <li class="list-item">
                    <a href="#" class="link link--nav">Мои задания</a>
                </li>
                <?php if (!$user->is_performer ?? null): ?>
                    <li class="list-item">
                        <a href="<?=Url::toRoute('task/create')?>" class="link link--nav">Создать задание</a>
                    </li>
                <?php endif; ?>
                <li class="list-item">
                    <a href="#" class="link link--nav">Настройки</a>
                </li>
            </ul>
        </div>
        <?php endif ?>
    </nav>
    <?php if (Yii::$app->controller->id !== 'registration'): ?>
    <div class="user-block">
        <a href="#">
            <img class="user-photo" src="img/man-glasses.png" width="55" height="55" alt="Аватар">
        </a>
        <div class="user-menu">
            <p class="user-name"><?= $user->name ?? false ?></p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="#" class="link">Настройки</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="link">Связаться с нами</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= Url::toRoute('auth/logout') ?>" class="link">Выход из системы</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="main-container">
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">

</footer>

<?php $this->endBody() ?>
</body>
<?php $this->endPage() ?>
</html>

