<?php
	include("database.php");

	$output = array();
	$sql = "SELECT * FROM members WHERE memid = '".$_SESSION['user']."'";
	$query=$conn->query($sql);
	while($row=$query->fetch_array()){
		$output[] = $row;
	}

	echo json_encode($output);
?>