<?php
declare(strict_types=1);
?>
<div style="max-width:760px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 4px 0;">Review bewerken</h1>
    <p style="margin:0 0 18px 0;color:#57534e;">ID <?= h((string)$review->id) ?></p>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar reviews', ['controller' => 'Admin', 'action' => 'reviews'], ['style' => 'color:#1f2937;']) ?></p>

    <?= $this->Flash->render() ?>

    <?= $this->Form->create(null, ['url' => ['controller' => 'Admin', 'action' => 'editReview', $review->id]]) ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
            <div>
                <label style="display:block;margin-bottom:4px;font-weight:600;">Naam gast</label>
                <input type="text" name="guest_name" value="<?= h((string)$review->guest_name) ?>" required style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
            </div>
            <div>
                <label style="display:block;margin-bottom:4px;font-weight:600;">Initialen</label>
                <input type="text" name="initials" value="<?= h((string)($review->initials ?? '')) ?>" maxlength="6" style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
            <div>
                <label style="display:block;margin-bottom:4px;font-weight:600;">Locatie</label>
                <input type="text" name="location" value="<?= h((string)($review->location ?? '')) ?>" style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
            </div>
            <div>
                <label style="display:block;margin-bottom:4px;font-weight:600;">Beoordeling (1–5)</label>
                <input type="number" name="rating" value="<?= h((string)$review->rating) ?>" min="1" max="5" required style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
            </div>
        </div>
        <div style="margin-bottom:14px;">
            <label style="display:block;margin-bottom:4px;font-weight:600;">Recensietekst</label>
            <textarea name="review_text" rows="6" required style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;font-family:inherit;font-size:inherit;box-sizing:border-box;"><?= h((string)$review->review_text) ?></textarea>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
            <div>
                <label style="display:block;margin-bottom:4px;font-weight:600;">Datum recensie</label>
                <input type="date" name="review_date" value="<?= h((string)($review->review_date ?? '')) ?>" style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
            </div>
            <div>
                <label style="display:block;margin-bottom:4px;font-weight:600;">Volgorde</label>
                <input type="number" name="sort_order" value="<?= h((string)$review->sort_order) ?>" min="0" style="width:100%;padding:10px;border:1px solid #d6d3d1;border-radius:8px;box-sizing:border-box;" />
            </div>
        </div>
        <div style="margin-bottom:20px;display:flex;align-items:center;gap:8px;">
            <input type="hidden" name="is_published" value="0" />
            <input type="checkbox" id="is_published" name="is_published" value="1" <?= $review->is_published ? 'checked' : '' ?> />
            <label for="is_published">Gepubliceerd</label>
        </div>
        <?= $this->Form->button('Opslaan', ['style' => 'padding:10px 20px;border:0;border-radius:8px;background:#1f2937;color:#fff;font-weight:600;cursor:pointer;']) ?>
    <?= $this->Form->end() ?>
</div>
