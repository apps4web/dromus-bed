<?php
declare(strict_types=1);
?>
<div style="max-width: 420px; margin: 64px auto; padding: 24px; border: 1px solid #e7e5e4; border-radius: 12px; background: #fff;">
    <h1 style="margin: 0 0 18px 0; font-size: 24px;">CMS Login</h1>

    <?= $this->Flash->render() ?>

    <?= $this->Form->create() ?>
    <fieldset style="border: 0; padding: 0; margin: 0;">
        <div style="margin-bottom: 12px;">
            <?= $this->Form->control('email', [
                'label' => 'E-mailadres',
                'required' => true,
                'style' => 'width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;',
            ]) ?>
        </div>
        <div style="margin-bottom: 16px;">
            <?= $this->Form->control('password_hash', [
                'label' => 'Wachtwoord',
                'type' => 'password',
                'required' => true,
                'style' => 'width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;',
            ]) ?>
        </div>
    </fieldset>

    <?= $this->Form->button('Inloggen', [
        'style' => 'width:100%;padding:10px;border:0;border-radius:8px;background:#1f2937;color:#fff;font-weight:600;',
    ]) ?>
    <?= $this->Form->end() ?>
</div>
