<?php declare(strict_types = 1); 

namespace App\Libraries;

class Warehouse {

	private $db;

	public function __construct()
	{
		$this->db = db_connect();
	}
	
	/**
	 * Il metodo checkStock effettua il controllo sulla nuova quantità iniziale di un prodotto, 
	 * perché non sia minore del numero dei prodotti già in ordine, cioé dei prodotti già venduti.
	 * Il metodo checkStock restituisce un array di due valori. Il primo valore, real_quantity, restituisce 
	 * la giacenza reale in magazzino in quel momento storico di un determinato prodotto, quindi il vendibile. 
	 * Il secondo valore, partial_quantity, restituisce la quantità effettivamente venduta di un prodotto.
	 * 
	 * La nuova quantità iniziale può essere al massimo uguale al numero dei prodotti già in ordine (venduti) 
	 * e determinare, al limite, una giacenza reale di zero prodotti.
	 * Senza questo controllo, si potrebbero immettere valori inferiori alla giacenza reale e causare 
	 * giacenze reali con numero negativo, creando così instabilità e valori non veritieri.
	 */
	public function checkStock(String $id): Array
	{
		$builder = $this->db->table('products');
		$productsData = $builder->select('initial_quantity')->getWhere(['id' => $id]);

		$builder = $this->db->table('orders_products');
		$data = $builder->select('orders_products.quantity')->getWhere(['orders_products.product_id' => $id]);

		$partial_quantity = 0;

		foreach($data->getResult() as $row):
			$partial_quantity += $row->quantity;
		endforeach;

		/**
		 * real_quantity è la giacenza di magazzino di un prodotto in un dato momento, cioé la quantità ancora vendibile di quel prodotto.
		 * Si ottiene dalla differenza tra la quantità totale di prodotto già venduto (ottenibile da una somma del campo quantity nella tabella orders_products), 
		 * e la quantità iniziale del prodotto (campo initial_quantity nella tabella products). 
		 * La quantità totale di prodotto venduto si ottiene sommando il valore del campo quantity tante volte 
		 * per quante volte si incontra l'ID del prodotto passato nel campo product_id.
		 * 
		 * partial_quantity è la quantità totale di prodotto venduto (cioé presente nella tabella orders_products)
		 */

		return [
			'real_quantity' => intval($productsData->getRow('initial_quantity')) - intval($partial_quantity), 
			'partial_quantity' => intval($partial_quantity)
		];
	}

	public function checkStockSingle(String $product_id, String $order_id): Int
	{
		$builder = $this->db->table('orders_products');
		$data = $builder->select('orders_products.quantity')->getWhere(['orders_products.product_id' => $product_id, 'orders_products.order_id' => $order_id]);

		// Ritorna il numero di pezzi di un prodotto all'interno di un ordine. Serve per quando si modifica un ordine.
		// Sostanzialmente serve per capire se in una eventuale modifica di un ordine, il campo quantità di un prodotto 
		// di quell'ordine viene modificato in aumento.
		// Se viene modificato si calcola la differenza fra la quantità originale e la nuova.

		return intval($data->getRow('quantity'));
	}

	/**
	 * Questo metodo esegue il controllo sulla quantità di prodotto richiesta, sia in un nuovo ordine che in uno esistente.
	 * La richiesta non deve mai superare la quantità reale, ovvero la giacenza
	 */
	public function checkOrderQuantity(String $productid, String $quantity, String $orderid = ''): Array
	{
		/* Per ragioni di sicurezza mi accerto che quantity sia un intero... */
		$quantity = (int)$quantity;

		/* Se la quantità è 0, blocchiamo tutto... */
		if($quantity <= 0):
			return ['result' => false, 'message' => sprintf('The quantity value has to be at least 1 piece!')];
		endif;

		/* Recupero il nome del prodotto passato... */
		$builder = $this->db->table('products');
		$product = $builder->select('product')->where('id', $productid)->get();

		/* Ottengo la giacenza disponibile del prodotto con $items['real_quantity'] */
		$items = $this->checkStock($productid);

		/* Siamo in fase di add order... */
		if(empty($orderid)):
			
			/* Se la quantità richiesta supera la giacenza, blocchiamo tutto... */
			if($quantity > $items['real_quantity']):
				return ['result' => false, 'message' => sprintf('For <b>%s</b> you are asking for <b>%d</b> pieces, the stock is <b>%d</b> pieces!', esc($product->getRow('product')), esc($quantity), esc($items['real_quantity']))];
			endif;

		/* Siamo in fase di edit order... */
		else:

			/**
			 * Ottengo il numero dei pezzi già richiesti per questo prodotto in questo stesso ordine.
			 * Serve per capire se la quantità richiesta per questo prodotto è in aumento rispetto al dato originale.
			 */
			$item = $this->checkStockSingle($productid, $orderid);

			/* Se la nuova quantità ($quantity) è maggiore della quantità originale ($item)... */
			if($quantity > $item):

				/* Ottengo la differenza... */
				$diffquantity = $quantity - $item;

				/* Se la differenza è maggiore della quantità in stock, la richiesta è troppo elevata e quindi blocco l'aggiornamento dell'ordine */
				if($diffquantity > $items['real_quantity']):

					/* Questo vale nel caso in cui si aggiunge all'ordine un nuovo prodotto, diversifico il messaggio... */
					if($item > 0):
						$message = sprintf('For <b>%s</b> you are increasing the original request of <b>%d</b>, by <b>%d</b> pieces, <b>%d</b> pieces are available!', esc($product->getRow('product')), esc($item), esc($diffquantity), esc($items['real_quantity']));
					else:
						$message = sprintf('For <b>%s</b> you are asking for <b>%d</b> pieces, <b>%d</b> pieces are available!', esc($product->getRow('product')), esc($quantity), esc($items['real_quantity']));
					endif;

					return ['result' => false, 'message' => $message];

				endif;

			endif;

		endif;

		return [];
	}

	/**
	 * Viene verificato se sono stati inseriti duplicati nell'ordine, nel caso blocco l'invio...
	 */
	public function checkDuplicates(Array $productIds, Object $products): Array
	{
		$duplicates = [];

		foreach(array_count_values($productIds) as $k => $v):
		    if($v > 1) $duplicates[$v] = $k;
		endforeach;

		if(count($duplicates)):
			foreach($products->getResult() as $product):
				foreach($duplicates as $k => $v):
					if($v == $product->id):
						return ['times' => $k, 'product' => $product->product];
					endif;
				endforeach;
			endforeach;
		endif;

		return [];
	}

	/**
	 * Ultimi controlli prima dell'inserimento in ordini
	 */
	public function lastCheckAdd(Array $attributes, Object $products): Array
	{
		foreach($products->getResult() as $product):

			foreach($attributes as $id => $q):

				if($id == $product->id):

					$items = $this->checkStock($product->id);

					/* La condizione sottostante dovrebbe verificarsi solo se cé una manipolazione da html */
					if($items['real_quantity'] < 1):
						return ['message' => sprintf('<b>%s</b> does not have enough stock!', $product->product)];
					endif;

					/* Se la quantità richiesta supera la giacenza, blocchiamo tutto... */
					if($q > $items['real_quantity']):
						return ['message' => sprintf('For <b>%s</b> you are asking for <b>%d</b> pieces, the stock is <b>%d</b> pieces!', $product->product, $q, $items['real_quantity'])];
					endif;

				endif;

			endforeach;

		endforeach;

		return [];
	}

	/**
	 * Ultimi controlli prima dell'aggiornamento ordine
	 */
	public function lastCheckEdit(Array $attributes, Object $products): Array
	{
		foreach($products->getResult() as $product):

			foreach($attributes as $id => $q):

				if($id == $product->id):

					/* Ottengo la giacenza disponibile del prodotto con $items['real_quantity'] */
					$items = $this->checkStock($product->id);

					/* Ottengo il numero dei pezzi già richiesti per questo prodotto in questo stesso ordine. */
					/* Serve per capire se la quantità richiesta per questo prodotto è in aumento rispetto al dato originale. */
					$item = $this->checkStockSingle($product->id, $id);

					/* Se la nuova quantità ($q) è maggiore della quantità originale ($item)... */
					if($q > $item):

						/* ...ottengo la differenza... */
						$dq = $q - $item;

						/* Se la differenza è maggiore della quantità in stock, la richiesta è troppo elevata e quindi blocco l'aggiornamento dell'ordine */
						if($dq > $items['real_quantity']):

							if($item > 0): /* <-- Questo vale nel caso in cui si aggiunge all'ordine un nuovo prodotto, diversifico il messaggio... */
								$message = sprintf('For <b>%s</b> you are increasing the original request of <b>%d</b>, by <b>%d</b> pieces, <b>%d</b> pieces are available!', $product->product, $item, $dq, $items['real_quantity']);
							else:
								$message = sprintf('For <b>%s</b> you are asking for <b>%d</b> pieces, <b>%d</b> pieces are available!', $product->product, $q, $items['real_quantity']);
							endif;

							return ['message' => $message];

						endif;

					endif;

				endif;

			endforeach;

		endforeach;

		return [];
	}

	public function prepareBatch(Array $attributes, Object $products, String $id): Array
	{
		$insert = []; 

		$i = 0;

        foreach($products->getResult() as $product):

            foreach($attributes as $k => $q):

                if($k == $product->id):
                    $insert[$i]['order_id'] = $id;
                    $insert[$i]['product_id'] = $k;
                    $insert[$i]['quantity'] = $q;
                    $insert[$i]['price'] = $product->net_price;
                    $insert[$i]['tax'] = $product->tax_percentage;
                    $i++;
                endif;
                
            endforeach;

        endforeach;

        return $insert;
	}
	
}