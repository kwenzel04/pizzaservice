var request = new XMLHttpRequest(); 

function requestData() { // fordert die Daten asynchron an
    
    request.open("GET", "AbrechnungUpdate.php"); // URL für HTTP-GET
    request.onreadystatechange = processData; //Callback-Handler zuordnen
    request.send(null);
}

    function process(data){
    
        "use strict";
        let obj = JSON.parse(data);
    
        let tableSec = document.getElementById("tableSec");
    
    
        while (tableSec.hasChildNodes()) {   
            tableSec.removeChild(tableSec.firstChild);
        }

        
        var rTable = document.createElement("table");
        var rTableHeaderRow = document.createElement("tr");
        var heA = document.createElement("th");
        var heB = document.createElement("th");
        var heC = document.createElement("th");

        rTableHeaderRow.appendChild(heA.appendChild(document.createTextNode("BestellungID")));
        rTableHeaderRow.appendChild(heB.appendChild(document.createTextNode("Adresse")));
        rTableHeaderRow.appendChild(heC.appendChild(document.createTextNode("Bestellzeit")));
        rTable.appendChild(rTableHeaderRow);
        tableSec.appendChild(rTable);
        


        obj.forEach(function(row) {
            var rTableRow = document.createElement("tr");
            var tdA = document.createElement("td");
            var tdB = document.createElement("td");
            var tdC = document.createElement("td");

        rTableRow.appendChild(tdA.appendChild(document.createTextNode(row['BestellungID'])));
        rTableRow.appendChild(tdB.appendChild(document.createTextNode(row['Adresse'])));
        rTableRow.appendChild(tdC.appendChild(document.createTextNode(row['Bestellzeitpunkt'])));
        rTable.appendChild(rTableRow);
                
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