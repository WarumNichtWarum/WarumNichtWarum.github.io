<html>
	<head>
		<title>
			Dieses eine Kartenspiel - Karten
		</title>
	</head>
	<style>
		.kartenfeld{
			display: grid;
			grid-template-columns: auto auto auto auto auto auto;
			position:absolute;
			gap:10px;
			border: 2px solid black;
			top:50px;
			width: 672px;
		}
		.karte{
			display: grid;
			grid-template-rows: auto auto auto auto;
			background-color: beige;
			border: 2px solid brown;
			height: 115px;
			width: 92px;
			gap:0px;
			margin: auto;
			margin-top:10px;
			margin-left:10px;
			margin-bottom:10px;
			margin-right:10px;
		}
		.karte div{
			display: grid;
			grid-template-columns: auto auto auto;
			border: 1px solid brown;
			font-size: 9px;
			height: 9px;
		}
		.karte div div{
			width:38px;
			border: 0px;
			padding: 2px;
		}
	</style>
	<body>
		<button type="button" onclick="back()">Zurück</button>
		<button type="button" onclick="remove()">Lösche Deck</button>
		<button type="button" onclick="add()">Neues Deck</button>
		<input type="text" id="nameOfDeck">
		<div class="kartenfeld" id="alleKarten" style="left:calc(25% - 336px)"></div>
		<div class="kartenfeld" id="deckKarten" style="left:calc(75% - 336px)"></div>
		<button type="button" style="position: absolute; top: 50px; left:calc(50% - 25px); width:50px;" onclick="swap()">Swap</button>
	</body>
</html>

<script>
	let infoStr =location.hash.substring(1,location.hash.length);
	location.hash = "";
	info = infoStr.replace(/%20/g," ");
	info = info.split(";");
	let infos = {
		name : info[0],
		password : info[1],
		gold : parseInt(info[2]),
		deck_ids : info[3],
		cards : info[4]
	}

	let deckButtons = [];
	let selectedButton = -1;
	let selectedCard = -1;
	let isMain = false;
	let kartenLinks = [];
	let kartenRechts = [];

	function showCards(){
		document.getElementById("alleKarten").innerHTML = "";
		document.getElementById("deckKarten").innerHTML = "";
		for(let i = 0; i < kartenLinks.length; i++){
			addKarte(kartenLinks[i],true);
		}
		for(let i = 0; i < kartenRechts.length; i++){
			addKarte(kartenRechts[i],false);
		}
	}
	function back(){
		infoStr = infos.name+";"+infos.password+";"+infos.gold+";"+infos.deck_ids+";"+infos.cards;
		location.replace("./main#"+infoStr);
	}
	function swap(){
		if(selectedCard!=-1 && selectedButton != -1){
			if (isMain){
				for(let i = 0; i < kartenLinks.length; i++){
					if (kartenLinks[i].id == selectedCard){
						kartenRechts.push(kartenLinks[i]);
						kartenLinks.splice(i,1);
						showCards();
						loadDeck(deckButtons[selectedButton].id)
						.then(function(value){
							saveDeck(deckButtons[selectedButton].id,value[1]+selectedCard.toString().padStart(3,"0"));
							selectedCard = -1;
						})
						break
					}
				}
			}
			else{
				for(let i = 0; i < kartenRechts.length; i++){
					if (kartenRechts[i].id == selectedCard){
						kartenLinks.push(kartenRechts[i]);
						kartenRechts.splice(i,1);
						showCards();
						loadDeck(deckButtons[selectedButton].id)
						.then(function(value){
							console.log(value[1]);
							console.log(selectedCard.toString().padStart(3,"0"));
							saveDeck(deckButtons[selectedButton].id,value[1].replace(selectedCard.toString().padStart(3,"0"),""));
							selectedCard = -1;
						})
						break
					}
				}
			}
		}
	}
	async function loadDeck(id){
		let returnvalue = [];
		let data = await fetch("data.php",{method:"POST",body:JSON.stringify({deck:id, type:"getDecks"})});
		let temp = await data.text();
		temp = temp.split(";");
		returnvalue[0] = temp[0];
		returnvalue[1] = temp[1];
		return returnvalue;
	}
	function saveDeck(id,cards){
		fetch("data.php",{method:"POST",body:JSON.stringify({deck:id,cards:cards, type:"saveDecks"})});
	}
	function addKarte(karte,main){
		if(main){
			let card = document.createElement("div");
			card.setAttribute("class","karte");
			card.setAttribute("onClick","onSelect("+karte.id+",true)");
			card.innerHTML = '<div>'+karte.name+'</div><div style="height: calc(6 * 11.5px)"><div>*<br>*</div><div>image</div></div><div style="height: calc(2 * 11.5px)"><div>ATK: <br>'+karte.attack+'</div><div>DEF: <br>'+karte.defense+'</div></div><div> TYP: xxxxxx</div>';
			document.getElementById("alleKarten").appendChild(card);
		}
		else{
			let card = document.createElement("div");
			card.setAttribute("class","karte");
			card.setAttribute("onClick","onSelect("+karte.id+",false)");
			card.innerHTML = '<div>'+karte.name+'</div><div style="height: calc(6 * 11.5px)"><div>*<br>*</div><div>image</div></div><div style="height: calc(2 * 11.5px)"><div>ATK: <br>'+karte.attack+'</div><div>DEF: <br>'+karte.defense+'</div></div><div> TYP: xxxxxx</div>';
			document.getElementById("deckKarten").appendChild(card);
		}
	}
	function onSelect(id,main){
		selectedCard = id;
		isMain = main;
	}
	async function createDeck(name){
		let id = await fetch("data.php",{method:"POST",body:JSON.stringify({username:infos.name, type:"setDecks", deckname:name})});
		id = await id.text();
		return parseInt(id);
	}
	async function loadYourCards(cards){
		let card = {id:0,name:"",attack:0,defense:0};
		let returnvalues = [];
		let data = await fetch("data.php",{method:"POST",body:JSON.stringify({cards:cards, type:"cards"})});
		let temp = await data.text();
		if(temp != ""){
		temp = temp.split(";");
			for(let i = 0; i < temp.length; i+=4){
				let returnvalue = Object.create(card);
				returnvalue.name = temp[i];
				returnvalue.attack = temp[i+1];
				returnvalue.defense = temp[i+2];
				returnvalue.id = temp[i+3];
				returnvalues.push(returnvalue);
			}
			return returnvalues;
		}
		return [];
	}
	function deleteDeck(id){
		fetch("data.php",{method:"POST",body:JSON.stringify({id:id, type:"unsetDecks"})});
	}
	function add() {
		if(document.getElementById("nameOfDeck").value != "" && infos.deck_ids.length != 30){
			createDeck(document.getElementById("nameOfDeck").value)
			.then(function(deck_id) {
				infos.deck_ids += ("00000" + deck_id).substring(-6);
				let newButton = document.createElement("button");
				newButton.type = "button";
				newButton.setAttribute("onclick","showDeck("+deckButtons.length+")");
				newButton.innerHTML = document.getElementById("nameOfDeck").value;
				newButton.id = deck_id;
				document.body.appendChild(newButton);
				deckButtons.push(newButton);
			})
		}
	}
	function remove(){
		if (selectedButton != -1){
			let button = deckButtons[selectedButton];
			deleteDeck(button.id);
			deckButtons.splice(selectedButton,1);
			document.body.removeChild(button);
			infos.deck_ids = infos.deck_ids.substring(0,selectedButton * 6) + infos.deck_ids.substring(selectedButton * 6 + 6,infos.deck_ids.length);
			selectedButton = -1;
		}
		for(let i = 0; i < deckButtons.length; i ++){
			deckButtons[i].setAttribute("onclick","showDeck("+i+")");
		}
	}
	function showDeck(pos){
		selectedButton = pos;
		selectedCard = -1;
		for(let i = 0; i < deckButtons.length; i++){
			if(i == pos){
				deckButtons[i].style = "font-size:14px";
			}
			else{
				deckButtons[i].style = "font-size:12px";
			}
		}
		document.getElementById("alleKarten").innerHTML = "";
		kartenRechts = [];
		kartenLinks = [];
		loadDeck(deckButtons[selectedButton].id)
		.then(function(val) {
			loadYourCards(val[1])
			.then(function(value2){
				let karten = infos.cards.repeat(1);
				for(let i = 0; i < value2.length; i++){
					karten = karten.replace(value2[i].id.padStart(3,"0"),"");
					kartenRechts.push(value2[i]);
				}
				loadYourCards(karten)
				.then(function(value){
					for(let i = 0; i < value.length; i++){
						kartenLinks.push(value[i]);
					}
					showCards();
				});
			});
		});
	}
	for(let i = 0; i < infos.deck_ids.length; i += 6){
		let deck_id = parseInt(infos.deck_ids.substring(i,i+6));
		loadDeck(deck_id)
		.then(function(value){
			let newButton = document.createElement("button");
			newButton.type = "button";
			newButton.setAttribute("onclick","showDeck("+deckButtons.length+")");
			newButton.id = deck_id;
			newButton.innerHTML = value[0];
			document.body.appendChild(newButton);
			deckButtons.push(newButton);
		});
	}
</script>