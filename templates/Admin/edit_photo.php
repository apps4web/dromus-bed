<?php
declare(strict_types=1);
?>
<div style="max-width:760px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 4px 0;">Foto bewerken</h1>
    <p style="margin:0 0 18px 0;color:#57534e;"><?= h((string)$photo->section_key) ?> — ID <?= h((string)$photo->id) ?></p>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar foto\'s', ['controller' => 'Admin', 'action' => 'photos'], ['style' => 'color:#1f2937;']) ?></p>

    <?= $this->Flash->render() ?>

    <?= $this->Form->create(null, ['url' => ['controller' => 'Admin', 'action' => 'editPhoto', $photo->id]]) ?>
        <div style="margin-bottom:14px;">
            <label style="display:block;margin-bottom:4px;font-weight:600;">Titel</label>
            <input type="text" name="title" value="<?= h((string)($photo->title ?? '')) ?>" style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
        </div>
        <div style="margin-bottom:14px;">
            <label style="display:block;margin-bottom:4px;font-weight:600;">Alt-tekst</label>
            <input type="text" name="alt_text" value="<?= h((string)($photo->alt_text ?? '')) ?>" style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
        </div>
        <div style="margin-bottom:14px;">
            <label style="display:block;margin-bottom:4px;font-weight:600;">Afbeeldings-URL</label>
            <input type="text" name="image_url" value="<?= h((string)$photo->image_url) ?>" required style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
        </div>
        <div style="margin-bottom:14px;">
            <label style="display:block;margin-bottom:4px;font-weight:600;">Volgorde</label>
            <input type="number" name="sort_order" value="<?= h((string)$photo->sort_order) ?>" min="0" style="width:120px;padding:10px;border:1px solid #d6d3d1;border-radius:8px;" />
        </div>
        <div style="margin-bottom:20px;display:flex;align-items:center;gap:8px;">
            <input type="hidden" name="is_active" value="0" />
            <input type="checkbox" id="is_active" name="is_active" value="1" <?= $photo->is_active ? 'checked' : '' ?> />
            <label for="is_active">Actief</label>
        </div>
        <?= $this->Form->button('Opslaan', ['style' => 'padding:10px 20px;border:0;border-radius:8px;background:#1f2937;color:#fff;font-weight:600;cursor:pointer;']) ?>
    <?= $this->Form->end() ?>
</div>
