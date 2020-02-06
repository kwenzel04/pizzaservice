var request = new XMLHttpRequest(); 

function requestData() { // fordert die Daten asynchron an
    
    request.open("GET", "KundenStatus.php"); // URL für HTTP-GET
    request.onreadystatechange = processData; //Callback-Handler zuordnen
    request.send(null);
}

function process(data){
    

    let obj = JSON.parse(data);

    let orderDiv = document.getElementById("customerOrder");


    while (orderDiv.hasChildNodes()) {   
        orderDiv.removeChild(orderDiv.firstChild);
    }
   
    
    obj.forEach(function(order) {
        
        
        let parentDiv = document.createElement("div");
        parentDiv.className = "defaultContainer status-items-item";

        let ordered = order['status'] == 'bestellt' ? 'checked' : '';
        let inOven = order['status'] == 'im ofen' ? 'checked' : '';
        let finished = order['status'] == 'fertig' ? 'checked' : '';
        let delivering = order['status'] == 'unterwegs' ? 'checked' : '';
        let delivered = order['status'] == 'geliefert' ? 'checked' : '';
        let bestellID = order['bID'];
        let pizzaID = order['pID'];

        let check = [ordered, inOven, finished, delivering, delivered];
        let allStatus = ["bestellt", "im Ofen", "fertig", "unterwegs", "geliefert"]

        let span = document.createElement("span");
        let textNode = document.createTextNode( "(" + bestellID + ")" + " " + order['name']);
        span.appendChild(textNode);
        parentDiv.appendChild(span);


        for(let i = 0; i < 5; i++){

            let span1 = document.createElement("span");
            let input = document.createElement("input");
            input.type = "radio";
            input.name = pizzaID;
            input.value = allStatus[i];
            if(check[i] == "checked"){
                input.checked = true;
            }
            input.disabled = true;
            span1.appendChild(input);
            parentDiv.appendChild(span1);
        }

        orderDiv.appendChild(parentDiv);
    });
}


function processData() {
    
    if (request.readyState == 4) { // Uebertragung = DONE
        if (request.status == 200) {   // HTTP-Status = OK
            if (request.responseText != null)
                process(request.responseText);// Daten verarbeiten
            else console.error("Dokument ist leer");
        }
        else console.error("Uebertragung fehlgeschlagen");
    } 
    else;          // Uebertragung laeuft noch
}





   window.onload = () => {window.setInterval (requestData, 3000)};