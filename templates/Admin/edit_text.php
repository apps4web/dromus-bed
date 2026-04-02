<?php
declare(strict_types=1);
?>
<div style="max-width:760px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 4px 0;">Tekst bewerken</h1>
    <p style="margin:0 0 18px 0;color:#57534e;"><?= h((string)$text->section_key) ?> / <?= h((string)$text->field_key) ?> (<?= h((string)$text->locale) ?>)</p>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar teksten', ['controller' => 'Admin', 'action' => 'texts'], ['style' => 'color:#1f2937;']) ?></p>

    <?= $this->Flash->render() ?>

    <?= $this->Form->create(null, ['url' => ['controller' => 'Admin', 'action' => 'editText', $text->id]]) ?>
        <div style="margin-bottom:14px;">
            <label style="display:block;margin-bottom:4px;font-weight:600;">Inhoud</label>
            <textarea name="content" rows="8" style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;font-family:inherit;font-size:inherit;box-sizing:border-box;"><?= h((string)$text->content) ?></textarea>
        </div>
        <div style="margin-bottom:20px;display:flex;align-items:center;gap:8px;">
            <input type="hidden" name="is_active" value="0" />
            <input type="checkbox" id="is_active" name="is_active" value="1" <?= $text->is_active ? 'checked' : '' ?> />
            <label for="is_active">Actief</label>
        </div>
        <?= $this->Form->button('Opslaan', ['style' => 'padding:10px 20px;border:0;border-radius:8px;background:#1f2937;color:#fff;font-weight:600;cursor:pointer;']) ?>
    <?= $this->Form->end() ?>
</div>
