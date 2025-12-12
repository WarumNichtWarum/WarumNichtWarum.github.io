<html>
	<head>
		<title>
			Dieses eine Kartenspiel - Menü
		</title>
	</head>
	<body>
		<div id="info"></div>
		<br>
		<button type="button" onclick="getGold()">Hier ist ein bissel Gold</button>
		<br>
		<button type="button" onclick="drawPack()">Kartenpack ziehen</button>
		<br>
		<button type="button" onclick="yourCards()">Deine Karten</button>
		<br>
		<button type="button" onclick="yourDecks()">Deine Decks</button>
		<br>
		<button type="button" onclick="allCards()">Kartenkatalog</button>
	</body>
</html>

<script>
	let infoStr =location.hash.substring(1,location.hash.length);
	location.hash = "";
	info = infoStr.replace(/%20/g," ");
	info = info.split(";");
	let data = {
		name : info[0],
		password : info[1],
		gold : parseInt(info[2]),
		deck_ids : info[3],
		cards : info[4]
	}
	save();
	update();

	function update(){
		document.getElementById("info").innerHTML = "Hallo "+data.name;
		document.getElementById("info").innerHTML += "<br> Du hast im Moment "+data.gold+" Gold";
	}
	function drawPack(){
		if(data.gold >= 10){
			data.gold -= 10;
			infoStr = data.name+";"+data.password+";"+data.gold+";"+data.deck_ids+";"+data.cards;
			location.replace("./pack#"+infoStr);
		}
	}
	function yourCards(){
		infoStr = data.name+";"+data.password+";"+data.gold+";"+data.deck_ids+";"+data.cards+";your";
		location.replace("./cards#"+infoStr);
	}
	function yourDecks(){
		infoStr = data.name+";"+data.password+";"+data.gold+";"+data.deck_ids+";"+data.cards;
		location.replace("./decks#"+infoStr);
	}
	function allCards(){
		infoStr = data.name+";"+data.password+";"+data.gold+";"+data.deck_ids+";"+data.cards+";all";
		location.replace("./cards#"+infoStr);
	}
	async function save(){
		let output = await fetch("data.php",{method:"POST",body:JSON.stringify({type:"save",money:data.gold,deck_ids:data.deck_ids,cards:data.cards})});
		let temp = await output.text();
	}
	function getGold(){
		data.gold += 10;
		update();
		save();
	}
</script>