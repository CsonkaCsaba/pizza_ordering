<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Próbafeladat</title>
    <link href="style.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script> 
</head>
<body>
    <div class="maincontainer">
        <h1 class="title">Pizza</h1>
        <div class="form-container">
            <form method="post" id="myForm">
                <div class="form-item">
                    <label for="name">Név</label>
                    <input type="text" name="name" required id="nev"/> 
                </div>
                <div class="form-item">
                    <label for="email">Email</label>
                    <input type="email" name="email" required pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" id="email"/>
                </div>
                <div class="form-item">
                    <label for="phone">Telefon</label>
                    <input type="tel" name="phone" required pattern="[\+]36\d{9}" id="phone"/>   
                </div>
                <div class="form-item">
                    <div class="right-inline">
                        <label for="pizza">Pizza</label>
                        <select id="pizza" name="pizza" required>
                            <option value="Margareta">Margaréta</option>
                            <option value="Funghi">Funghi</option>
                            <option value="Husos">Húsos</option>
                        </select> 
                    
                        <label for="pizzasize"></label>
                        <select id="pizzasize" name="pizzasize" required>
                            <option value="kicsi">Kicsi</option>
                            <option value="nagy">Nagy</option>
                        </select> 
                    </div>
                </div>
                <div class="right-inline-checkbox">
                    <label for="extracheese">Extra sajt</label>
                    <input type="checkbox" name="extracheese" id="extracheese"/>   
                </div>
                <div>
                    <button class="button" type="button" id="submit">OK</button>
                </div>
            </form>
            <div id="message" style="display: none;">
            
            </div>
            </div>
        </div>
</div>
<script>
$(document).ready(function() {
    const myForm = document.getElementById("myForm");
    $('#submit').click(function(){
        let nev = $('#nev').val();
        let email = $('#email').val();
        let phone = $('#phone').val();
        let pizza = $('#pizza').val();
        let pizzasize = $('#pizzasize').val();
        let extracheese = $('#extracheese').prop("checked");
        let valid = true;
        let lastRequestTime = 0;

        if(nev!=="" && email!=="" && phone !==""){

                let patternName = /^(?=.*\s).{6,}$/;
                let patternEmail = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}/;
                let patternPhone = /[\+]36\d{9}/;

                if (patternName.test(nev) === false) {
                    alert('A név legalább 6 karakter hosszú és legalább 1 szóközt tartalmaz');
                    $('#nev').focus();
                    valid = false;
                }
                if (patternEmail.test(email) === false) {
                    alert('Kérjük, hogy valid email címet adjon meg');
                    $('#email').focus();
                    valid = false;
                }
                if (patternPhone.test(phone) === false) {
                    alert('A telefonszám + jellel kezdődik és 9 számjegyet tartalmaz');
                    $('#phone').focus();
                    valid = false;
                }
                if(extracheese === true && pizza !== "Husos"){
                alert("A kiválasztott pizzához nem kérhető extra sajt");  
                } else if(valid === true) {
                        extracheese = 'igen';
                        const currentTime = Date.now();
                        const minInterval = 60 * 1000;
                        const diff = currentTime - lastRequestTime;
                        const sessionEmail = sessionStorage.getItem("lastEmail");
                        if((diff >= minInterval) && (sessionEmail != email)){
                            $.ajax({
                            type:"POST",
                            url:"db.php",
                            data: 
                            {   nev: nev,
                                email: email,
                                phone: phone,
                                pizza: pizza,
                                pizzasize: pizzasize,
                                extracheese: extracheese
                            },
                            success: function(data){
                                const array = $.parseJSON(data);
                                $('#message').append("<p> Rendelés sikeresen elküldve<br> Azonosító: "+array.azon+"</p>");     
                                $('#myForm').hide();
                                $('#message').show();
                                const lastEmail =  sessionStorage.setItem("lastEmail", array.lastEmail);
                                }
                            })
                        } else {
                            alert('Erről az email címről egy percen belül már adtak le rendelést!')
                        }
                    } 

            } else{
            alert('A név, email és telefonszám kitöltése kötelező');
            } 
        });
    });

</script>
</body>
</html>