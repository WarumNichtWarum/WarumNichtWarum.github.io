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
			left: calc(50% - 336px);
			gap:10px;
			border: 2px solid black;
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
		<div class="kartenfeld" id="kartenfeld">

		</div>
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
	let loadType = info[5];

	function back(){
		infoStr = infos.name+";"+infos.password+";"+infos.gold+";"+infos.deck_ids+";"+infos.cards
		location.replace("./main#"+infoStr);
	}
	function addKarte(karte){
		let card = document.createElement("div");
		card.setAttribute("class","karte");
		card.innerHTML = '<div>'+karte.name+'</div><div style="height: calc(6 * 11.5px)"><div>*<br>*</div><div>image</div></div><div style="height: calc(2 * 11.5px)"><div>ATK: <br>'+karte.attack+'</div><div>DEF: <br>'+karte.defense+'</div></div><div> TYP: xxxxxx</div>';
		document.getElementById("kartenfeld").appendChild(card);
	}
	async function loadCards(){
		let card = {id:0,name:"",attack:0,defense:0};
		let returnvalues = [];
		let data = await fetch("data.php",{method:"POST",body:JSON.stringify({cards:"all", type:"cards"})});
		let temp = await data.text();
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
	async function loadYourCards(){
		let card = {id:0,name:"",attack:0,defense:0};
		let returnvalues = [];
		let data = await fetch("data.php",{method:"POST",body:JSON.stringify({cards:infos.cards, type:"cards"})});
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
	}

	if (loadType == "all"){
		loadCards()
		.then(function(value){
			for (let i = 0; i < value.length; i++){
				addKarte(value[i]);
			}
		})
	}
	else if(loadType == "your" && infos.cards != ""){
		loadYourCards()
		.then(function(value){
			for (let i = 0; i < value.length; i++){
				addKarte(value[i]);
			}
		})
	}
</script>