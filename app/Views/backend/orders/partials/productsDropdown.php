<option value="">-- Select a Product --</option>
<?php foreach($productsDropdown->getResult() as $productDropdown):

    $items = $warehouse->checkStock($productDropdown->product_id);

    /* qui calcolo l'iva per ogni prodotto */
    $tax_amount = ($productDropdown->net_price * $productDropdown->tax_percentage) / 100;

    /* Qui sommo il prezzo base + l'iva calcolata precedentemente */
    $gross_price = $productDropdown->net_price + $tax_amount; 

    /* Preparazione degli attributi a seconda di alcuni casi... */
    if(($items['real_quantity'] >= 1) && ($items['real_quantity'] < 5)):
        /* La giacenza è maggiore di 1 ma minore di 5, warning! */
        $attr = 'class="font-weight-bold text-warning" 
                 net_price="' . esc($productDropdown->net_price) . '" 
                 tax_amount = "' . esc($tax_amount) . '" 
                 gross_price = "' . esc($gross_price) . '" 
                 tax_percentage = "' . esc($productDropdown->tax_percentage) . '"';
    elseif($items['real_quantity'] < 1):
        /* La giacenza è minore di 1, danger! */
        $attr = 'class="font-weight-bold text-danger" 
                 net_price="' . esc($productDropdown->net_price) . '" 
                 tax_amount = "' . esc($tax_amount) . '" 
                 gross_price = "' . esc($gross_price) . '" 
                 tax_percentage = "' . esc($productDropdown->tax_percentage) . '" disabled';
    else:
        /* La giacenza è più di 5, siamo tranquilli */
        $attr = 'class="font-weight-bold text-success" 
                 net_price="' . esc($productDropdown->net_price) . '" 
                 tax_amount = "' . esc($tax_amount) . '" 
                 gross_price = "' . esc($gross_price) . '" 
                 tax_percentage = "' . esc($productDropdown->tax_percentage) . '"';
    endif; ?>

    <option value="<?= esc($productDropdown->product_id); ?>"<?= $attr; ?>>
        <?= esc($productDropdown->product . ' - (' . $items['real_quantity'] . ')'); ?>
    </option>';

<?php endforeach; ?>