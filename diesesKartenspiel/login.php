<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	$conn = new mysqli("localhost", "root", "", "dieseseinekartenspiel");
	$data = file_get_contents('php://input');
	$jsondata = json_decode($data, true);
	if ($jsondata["type"] == "register"){
		$value = $conn->query("SELECT * FROM user WHERE username='".$jsondata["username"]."'");
		if ($value->num_rows > 0){
			echo "Fehler: Benutzername ist bereits vorhanden";
		}
		else{
			$conn->query("INSERT INTO user (username, password, money, deck_ids, cards) VALUES ('".$jsondata["username"]."', '".$jsondata["password"]."', 20, '', '')");
			echo "Account wurde erstellt";
		}
	}
	else{
		$value = $conn->query("SELECT * FROM user WHERE username = '".$jsondata["username"]."' and password = '".$jsondata["password"]."'");
		if ($value->num_rows > 0){
			$value = $value->fetch_assoc();
			echo $value["username"].";".$value["password"].";".$value["money"].";".$value["deck_ids"].";".$value["cards"].";";
		}
		else{
			echo "Falscher Benutzername oder Password";
		}
	}
	$conn->close();
}

?>