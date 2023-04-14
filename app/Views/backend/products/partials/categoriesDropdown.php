<?php if($id !== 0): ?>
	
	<?php foreach($categoriesDropdown->getResult() as $category): ?>
	    <option value="<?= esc($category->category_id); ?>">
			<?= esc($category->category); ?>
	    </option>';
	<?php endforeach; ?>	

<?php endif; ?>