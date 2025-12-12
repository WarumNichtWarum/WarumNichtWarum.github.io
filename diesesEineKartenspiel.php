<html>
    <head>
        <title>
            DiesesEineKartenspiel - Login
        </title>
    </head>
    <body>
        <label for="name">Username:</label>
        <input type="text" id="name" name="name">
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>
        <button type="button" onclick="register()">Registrieren</button>
        <button type="button" onclick="login()">Einloggen</button>
        <br><br>
        <div id="info">

        </div>
    </body>
</html>

<script>
    function hideInfo(){
        document.getElementById("info").innerHTML = "";
    }
    async function register(){
        let data = await fetch("./diesesKartenspiel/login",{method:"POST",body:JSON.stringify({username:document.getElementById("name").value,password:document.getElementById("password").value,type:"register"})});
		let temp = await data.text();
        document.getElementById("info").innerHTML = temp;
        setTimeout(hideInfo,3000);
    }
    async function login(){
        let data = await fetch("./diesesKartenspiel/login",{method:"POST",body:JSON.stringify({username:document.getElementById("name").value,password:document.getElementById("password").value,type:"login"})});
		let temp = await data.text();
        if (temp == "Falscher Benutzername oder Password"){
            document.getElementById("info").innerHTML = temp;
            setTimeout(hideInfo,3000);
        }
        else{
            location.assign("./diesesKartenspiel/main#"+temp);
        }
    }
</script>