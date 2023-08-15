<?php
	include("database.php");

	$output = [];

    // Fetch Gold Price
	$gold_price_sql = "SELECT id, rate FROM gold_price";
	$gold_price_query = $conn->query($gold_price_sql);
	while($gold_price_row = $gold_price_query->fetch_array()){
		$output["gold_price"] = $gold_price_row;
	}

    // Fetch Gold Product List
	$gold_product_list_sql = "SELECT id, name FROM gold_product_list";
	$gold_product_list_query = $conn->query($gold_product_list_sql);
    $output["gold_product_list"]['name'] = [];
	while($gold_product_list_row = $gold_product_list_query->fetch_assoc()){
		$output["gold_product_list"]['name'] = $gold_product_list_row['name'];
	}

    header('Content-Type: application/json; charset=utf-8');
	echo json_encode($output, JSON_PRETTY_PRINT);
?>