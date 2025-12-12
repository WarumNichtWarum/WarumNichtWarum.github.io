<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	$conn = new mysqli("localhost", "root", "", "dieseseinekartenspiel");
	$data = file_get_contents('php://input');
	$jsondata = json_decode($data, true);
	if ($jsondata['type'] == "deck"){
		$result = $conn->query("SELECT cards FROM decks WHERE deck_id =" . $jsondata['deck']);
		$value = $result->fetch_assoc();
		$result2 = $conn->query("SELECT * FROM karten");
		$values = $result2-> fetch_all();
		for ($i = 0; $i < strlen($value["cards"]); $i+=3){
			$karte = $values[intval(substr($value["cards"], $i, 3))];
			echo $karte[1];
			echo ";";
			echo $karte[2];
			echo ";";
			echo $karte[3];
			echo ";";
			echo $karte[0];
			if($i + 3 < strlen($value["cards"])){
			echo ";";}
		}
	}
	elseif ($jsondata['type'] == "save"){
		$conn->query("UPDATE user SET money=".$jsondata["money"].",deck_ids='".$jsondata["deck_ids"]."',cards='".$jsondata["cards"]."'");
	}
	elseif ($jsondata['type'] == "cards"){
		if($jsondata['cards'] == "all"){
			$result = $conn->query("SELECT * FROM karten");
			$values = $result->fetch_all();
			for( $i = 0; $i < count($values);$i++){
				$karte = $values[$i];
				echo $karte[1];
				echo ";";
				echo $karte[2];
				echo ";";
				echo $karte[3];
				echo ";";
				echo $karte[0];
				if ($i + 1 < count($values)){
					echo ";";
				}
			}
		}
		else{
			$kartenData = $conn->query("SELECT * FROM karten");
			$karten = $kartenData->fetch_all();
			for ($i = 0; $i < strlen($jsondata['cards']); $i += 3){
				$karte = $karten[intval(substr($jsondata['cards'],$i,3))-1];
				echo $karte[1];
				echo ";";
				echo $karte[2];
				echo ";";
				echo $karte[3];
				echo ";";
				echo $karte[0];
				if ($i + 3 < strlen($jsondata['cards'])){
					echo ";";
				}
			}
		}
	}
	elseif($jsondata['type']== "getDecks"){
		$result = $conn->query("SELECT * FROM decks WHERE deck_id=".$jsondata['deck']);
		$result = $result->fetch_assoc();
		echo $result['deckname'].";".$result['cards'];
	}
	elseif($jsondata['type']== "saveDecks"){
		$conn->query("UPDATE decks SET cards = '".$jsondata["cards"]."' WHERE deck_id=".$jsondata['deck']);
	}
	elseif($jsondata['type'] == "setDecks"){
		$conn->query("INSERT INTO decks (username,deckname,cards) VALUES ('".$jsondata['username']."','".$jsondata['deckname']."','')");
		echo $conn->insert_id;
	}
	elseif($jsondata['type'] == "unsetDecks"){
		$conn->query("DELETE FROM decks WHERE deck_id = ".$jsondata['id']);
	}
	$conn->close();
}

?>