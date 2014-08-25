<?php

/**
 * Your functions.php code goes here...
*/

/*
 * Put this code at the end of your functions.php file.
 * It works with Woocommerce Version 2.1.12
 * with other versions it could have bugs, because for easier integration in woocommerce, it takes HTML code and parses it.
*/
add_filter('woocommerce_get_price_html','fwoocommerce_get_price_html', 10, 2);
add_filter('woocommerce_cart_item_price','fwoocommerce_cart_item_price', 10, 3);
add_filter('woocommerce_cart_item_subtotal','fwoocommerce_cart_item_subtotal', 10, 3);
add_filter('woocommerce_cart_total', 'fwoocommerce_cart_total', 10, 1);
add_filter('woocommerce_cart_total_ex_tax', 'fwoocommerce_cart_total_ex_tax', 10, 1);
add_filter('woocommerce_cart_subtotal', 'fwoocommerce_cart_subtotal', 10, 3);
add_filter('woocommerce_cart_shipping_method_full_label', 'fwoocommerce_cart_shipping_method_full_label', 10, 2);
add_filter('woocommerce_cart_totals_coupon_html', 'fwoocommerce_cart_totals_coupon_html', 10, 2);
add_filter('woocommerce_order_formatted_line_subtotal', 'fwoocommerce_order_formatted_line_subtotal', 10, 3);
add_filter('woocommerce_get_order_item_totals', 'fwoocommerce_get_order_item_totals', 10, 2);
add_filter('woocommerce_get_formatted_order_total', 'fwoocommerce_get_formatted_order_total', 10, 2);
add_filter('woocommerce_variation_price_html', 'fwoocommerce_variation_price_html', 10, 2);
add_filter('woocommerce_variation_sale_price_html', 'fwoocommerce_variation_sale_price_html', 10, 2);


function fwoocommerce_variation_sale_price_html($price, $this) {
	$prices = explode("Lt", strip_tags($price));
	return "<del>" . fwoocommerce_variation_price_html($prices[0]." Lt", $this) . "</del><ins>" . fwoocommerce_variation_price_html($prices[1]." Lt", $this) ."</ins>" ;
}

function fwoocommerce_variation_price_html($price, $this) {
	return "<span class=\"amount\">" . strip_tags($price) . " / " . number_format(strip_tags(str_ireplace(",", "", $price)) / 3.4528, 2)." €</span>";
}

function fwoocommerce_get_formatted_order_total($formatted_total, $this) {
	$formatted_total = $formatted_total . " / " . number_format(strip_tags(str_ireplace(",", "", $formatted_total)) / 3.4528, 2)." €";
	return $formatted_total;
}

function fwoocommerce_get_order_item_totals($total_rows, $this) {

	foreach ($total_rows as &$row) {
		if (strpos($row["value"],"€") == false) { 
			$row["value"] = $row["value"] . " / " . number_format(strip_tags(str_ireplace(",", "", $row["value"])) / 3.4528, 2)." €";
		} 
	}

	return $total_rows;
}

function fwoocommerce_order_formatted_line_subtotal($subtotal, $item, $this ) {
	$eurPrice = strip_tags($subtotal);
	return $subtotal . " / ". number_format(str_ireplace(",", "", $eurPrice) / 3.4528, 2)." €";
}

function fwoocommerce_cart_totals_coupon_html($value, $coupon) {
	$valuez = explode("</span>", $value);
	
	if (count($valuez) == 2) {
		$value = $valuez[0] . " / ";
		$valuez[0] = str_ireplace(" lt", '', $valuez[0]);
		$value .= number_format(strip_tags(str_ireplace(",", "", $valuez[0])) / 3.4528, 2)." €";
		$value .= "</span>" . $valuez[1];
	}

	return $value;
}

function fwoocommerce_cart_shipping_method_full_label($label, $method) {
//	var_dump(htmlspecialchars($label));
	$labelz = explode("<span class=\"amount\">", $label);
	$label = $labelz[0];
	$label .= "<span class=\"amount\">".$labelz[1]."</span>";
	$label .= " / " . number_format(str_ireplace(",", "", $labelz[1]) / 3.4528, 2)." €";
	return $label;
}


function fwoocommerce_cart_subtotal($cart_subtotal, $compound, $this) { //ok

	$cart_subtotal = strip_tags($cart_subtotal);
	return "<span class=\"amount\">". $cart_subtotal." / ".number_format(str_ireplace(",", "", $cart_subtotal) / 3.4528, 2)." €</span>";
}

function fwoocommerce_cart_item_subtotal($price, $cart_item, $cart_item_key ){

	$eurPrice = strip_tags($price);

	$eurPrice = str_ireplace(" lt", '', $eurPrice);

	return $price." / ". (float)round(str_ireplace(",", "", $eurPrice) / 3.4528, 2) ." €";
}

function fwoocommerce_cart_item_price($price, $cart_item, $cart_item_key ){
	
	$eurPrice = strip_tags($price);

	$eurPrice = str_ireplace(" lt", '', $eurPrice);

	return $price." / ". (float)round(str_ireplace(",", "", $eurPrice) / 3.4528, 2) ." €";
}


function fwoocommerce_cart_total($price){ //ok

	$eurPrice = strip_tags($price);
	return $price." / ".number_format(str_ireplace(",", "", $eurPrice) / 3.4528, 2)." €";
}



function fwoocommerce_cart_total_ex_tax($price){ //ok

	$eurPrice = strip_tags($price);
	return $price." / ".number_format(str_ireplace(",", "", $eurPrice) / 3.4528, 2)." €";
}

function fwoocommerce_get_price_html($price, $productId){ //ok
	//var_dump(htmlspecialchars($price));

	$product = get_product($productId);
	$prices = explode("&ndash;", strip_tags($price));

	if (count($prices) == 1) {
        	return $price." / ".number_format(str_ireplace(",", "", $product->get_price()) / 3.4528, 2)." €";
	} else if (count($prices) == 2) {
		return $price." / ".number_format(str_ireplace(",", "", $prices[0]) / 3.4528, 2)."€-". number_format(str_ireplace(",", "", $prices[1]) / 3.4528, 2). "€";
	} else {
		return $price;
	}
}