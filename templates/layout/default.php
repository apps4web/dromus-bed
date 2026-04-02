<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (\Cake\Core\Configure::read('debug')): ?>
        <meta name="robots" content="noindex, nofollow, noarchive">
        <meta name="googlebot" content="noindex, nofollow, noarchive">
    <?php else: ?>
        <meta name="robots" content="index, follow, archive">
        <meta name="googlebot" content="index, follow, archive">
    <?php endif; ?>
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', 'favicon.ico') ?>
    <?= $this->Html->meta('icon', 'favicon-32x32.png', ['type' => 'image/png', 'sizes' => '32x32']) ?>
    <?= $this->Html->meta('icon', 'favicon-16x16.png', ['type' => 'image/png', 'sizes' => '16x16']) ?>
    <?= $this->Html->meta('apple-touch-icon', 'apple-touch-icon.png', ['rel' => 'apple-touch-icon', 'sizes' => '180x180']) ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>" aria-label="Dromus Bed &amp; Boetiek">
                <?= $this->Html->image('dromus-logo.jpg', [
                    'alt' => 'Dromus Bed & Boetiek logo',
                    'style' => 'height: 42px; width: auto; display: block;',
                ]) ?>
            </a>
        </div>
        <div class="top-nav-links">
            <a target="_blank" rel="noopener" href="https://book.cakephp.org/5/">Documentation</a>
            <a target="_blank" rel="noopener" href="https://api.cakephp.org/">API</a>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
