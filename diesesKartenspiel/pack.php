<html>
	<head>
		<title>
			Dieses eine Kartenspiel - Kartenpack
		</title>
	</head>
	<style>
		.karte{
			display: grid;
			position: absolute;
			grid-template-rows: auto auto auto auto;
			background-color: beige;
			border: 2px solid brown;
			height: 115px;
			width: 92px;
			gap:0px;
			margin: auto;
			margin-top:0px;
			top: 40%;
			left: calc(50% - 46px);
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
		<div class="karte" id="karte" onclick="next()"></div>
		<div id="output"></div>
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
	async function getPackCards(id){
		let card = {id:0,name:"",attack:0,defense:0};
		let returnvalues = [];
		let data = await fetch("data.php",{method:"POST",body:JSON.stringify({deck:id, type:"deck"})});
		let temp = await data.text();
		console.log(temp);
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
	function next(){
		pos++;
		if (pos==pack.length){
			for (let i = 0; i < 5; i++){
				let id = "000"+pack[i].id;
				data.cards += id.substring(id.length-3,id.length);
			}
			infoStr = data.name+";"+data.password+";"+data.gold+";"+data.deck_ids+";"+data.cards
			location.replace("./main#"+infoStr);
		}
		let karte = pack[pos];
		document.getElementById("karte").innerHTML = '<div>'+karte.name+'</div><div style="height: calc(6 * 11.5px)"><div>*<br>*</div><div>image</div></div><div style="height: calc(2 * 11.5px)"><div>ATK: <br>'+karte.attack+'</div><div>DEF: <br>'+karte.defense+'</div></div><div> TYP: xxxxxx</div>';
	}
	let pack = [];
	let pos = -1;
	getPackCards("1")
	.then(function(value) {
		for(let i = 0; i < 5; i++){
			pack[i] = value[Math.round(Math.random()*value.length-0.5)];
		}
	});
</script>