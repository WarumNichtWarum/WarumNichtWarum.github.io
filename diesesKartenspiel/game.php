<!DOCTYPE html>
<html>
<head><title>dies ist ein Kartenspiel</title></head>
<body>
<style>
	#feld{
		display: flex;
		position: absolute;
		background-color: aliceblue;
		left: calc(50% - 299px);
		width:598px;
		height: 552px;
		border: 1px solid black;
	}
	#log{
		display:flex;
		position: absolute;
		border: 1px solid black;
		width: 200px;
		height: calc(100% - 20px);
		right:0px;
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
		margin-top:0px;
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
	#kartengrid{
		display: grid;
		grid-template-columns: auto auto auto;
		position: absolute;
		height: 253px;
		width: 322px;
		left: calc(50% - 161px);
		top: 60px;
		row-gap: 15px;
	}
	#deck{
		display: grid;
		grid-template-columns: auto auto auto auto auto;
		position: absolute;
		height: 115px;
		width: 500px;
		top: 437px;
		left: calc(50% - 250px);
	}
	.buttons{
		display: grid;
	}
</style>

<div id="feld">
	<br> Lebenspunkte Gegner: 1000 Lebenspunkte Du: 1000
</div>

<div id="log">
	Kampflog:<br>
	Hmmm...<br>
	Was das wohl sein könnte...
</div>

<div id="kartengrid">
	<div class="karte" id="1" style="opacity:0"></div>
	<div class="karte" id="2" style="opacity:0"></div>
	<div class="karte" id="3" style="opacity:0"></div>
	<div class="karte" id="4" style="opacity:0"></div>
	<div class="karte" id="5" style="opacity:0"></div>
	<div class="karte" id="6" style="opacity:0"></div>
</div>

<div id="deck">
	<div class="karte" id="d1" style="opacity:0"></div>
	<div class="karte" id="d2" style="opacity:0"></div>
	<div class="karte" id="d3" style="opacity:0"></div>
	<div class="karte" id="d4" style="opacity:0"></div>
	<div class="karte" id="d5" style="opacity:0"></div>
</div>

<div class="buttons">
	Mögliche Aktionen:<br>
</div>

</body>
</html>

<script>
	function addKarte(pos,karte){
		document.getElementById(pos).style = "opacity:1";
		document.getElementById(pos).innerHTML = '<div>'+karte.name+'</div><div style="height: calc(6 * 11.5px)"><div>*<br>*</div><div>image</div></div><div style="height: calc(2 * 11.5px)"><div>ATK: <br>'+karte.attack+'</div><div>DEF: <br>'+karte.defense+'</div></div><div> TYP: xxxxxx</div>';
	}
	function removeKarte(pos){
		document.getElementById(pos).style = "opacity:0";
	}
	function showHand(){
		let i = 1;
		for(let i = 1; i <= 5; i++){
			if (hand[i-1] != null){
				addKarte("d"+i, hand[i-1]);
			}
			else{
				removeKarte("d"+i);
			}
		}
	}
	function showField(){
		let i = 1;
		for(let i = 1; i <= 6; i++){
			if (feld[i-1] != null){
				addKarte(i, feld[i-1]);
			}
		}
	}
	function placeCard(pos){
		if (feld[3] == null){
			feld[3] = hand[pos-1];
			hand.splice(pos-1,1);
		}
		else if(feld[4] == null){
			feld[4] = hand[pos-1];
			hand.splice(pos-1,1);
		}
		else if(feld[5] == null){
			feld[5] = hand[pos-1];
			hand.splice(pos-1,1);
		}
		update();
	}
	function update(){
		showHand();
		showField();
		for(let i = 0; i < buttons.length; i++){
			document.body.removeChild(buttons[i])
			document.body.removeChild(divs[i]);
		}
		divs = [];
		buttons = [];
		if(feld[3] == null || feld[4] == null || feld[5] == null)
		for(let i = 1; i <= hand.length; i++){
			buttons[i-1] = document.createElement("button");
			buttons[i-1].innerHTML = "Platziere "+hand[i-1].name;
			buttons[i-1].setAttribute("onClick","placeCard("+i+")");
			document.body.appendChild(buttons[i-1]);
			divs[i-1] = document.createElement("div")
			document.body.appendChild(divs[i-1]);
		}
		if (hand.length < 5 && deck.length > 0){
			let button = document.createElement("button");
			button.innerHTML = "Ziehe Karte";
			button.setAttribute("onClick","drawCard()");
			buttons[buttons.length] = button;
			document.body.appendChild(buttons[buttons.length-1]);
			divs[divs.length] = document.createElement("div")
			document.body.appendChild(divs[divs.length-1]);
		}
	}
	function drawCard(){
		hand[hand.length] = deck.shift();
		update();
	}
	function shuffleDeck(){
		let newDeck = [];
		let copyOfDeck = deck.slice();
		for(let i = 0; i < deck.length; i++){
			let random = Math.round(Math.random()*(copyOfDeck.length-1));
			newDeck[i] = copyOfDeck[random];
			copyOfDeck.splice(random,1);
		}
		return newDeck;
	}
	async function getDeckInfo(id){
		let card = {name:"",attack:0,defense:0};
		let returnvalues = [];
		let data = await fetch("data.php",{method:"POST",body:JSON.stringify({deck:id, type:"deck"})});
		let temp = await data.text();
		console.log(temp);
		temp = temp.split(";");
		for(let i = 0; i < temp.length; i+=3){
			let returnvalue = Object.create(card);
			returnvalue.name = temp[i];
			returnvalue.attack = temp[i+1];
			returnvalue.defense = temp[i+2];
			returnvalues.push(returnvalue);
		}
		return returnvalues;
	}
	let deck = [];
	let feld = [];
	let hand = [];
	let buttons = [];
	let divs = [];
	getDeckInfo("1").then(function(value) {
		deck = value;
		deck = shuffleDeck();
		drawCard();
	});
</script>