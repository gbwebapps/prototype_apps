<option value="">-- Select a Category --</option>
<?php foreach($categoriesDropdown->getResult() as $categoryDropdown): ?>
    <option value="<?= esc($categoryDropdown->category_id); ?>">
        <?= esc($categoryDropdown->category); ?>
    </option>
<?php endforeach; ?>