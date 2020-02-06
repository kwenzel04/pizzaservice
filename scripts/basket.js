function addToWarenkorb(pizza, price){
    "use strict";
    var select = document.getElementById('warenkorbSelect');
    var newOpt = document.createElement('option');
    newOpt.text = pizza;
    newOpt.dataset.pizzaPrice = price;
    newOpt.value = pizza;
    newOpt.className = "optionStyle";
    select.add(newOpt);

    // var list = document.getElementById('warenkorbListe');
    // var entry = document.createElement('li');
    // entry.appendChild(document.createTextNode(pizza));
    // list.appendChild(entry);

    var totalPrice = document.getElementById('total').value;

    var pizzaPrice = parseFloat(price);

    var totalPriceFloat = parseFloat(totalPrice);
    totalPriceFloat += pizzaPrice

    var totalPriceRound = totalPriceFloat.toFixed(2);

    document.getElementById('total').value = totalPriceRound;
}


function deleteSelected(){
    "use strit";
    var pizzaSelect = document.getElementById('warenkorbSelect')
    console.log(pizzaSelect.selectedIndex);
    while(pizzaSelect.selectedIndex != -1){

        var deletePrice = pizzaSelect.options[pizzaSelect.selectedIndex].dataset.pizzaPrice;

        var totalPrice = document.getElementById('total').value;
        var deletePriceFloat = parseFloat(deletePrice);
        var totalPriceFloat = parseFloat(totalPrice);
        totalPriceFloat -= deletePriceFloat;
    
        var totalPriceRound = totalPriceFloat.toFixed(2);
        document.getElementById('total').value = totalPriceRound;

        pizzaSelect.remove(pizzaSelect.selectedIndex);
    }   
}


function deleteAll(){
    "use strict";
    var select = document.getElementById("warenkorbSelect");
    var length = select.options.length;
    for (var i = 0; i < length; i++) {
        select.options.remove(0);
    }
    document.getElementById('total').value = "0.00";
}


function submitCondition(){
    "use strict"
    var select = document.getElementById("warenkorbSelect");
    var length = select.options.length;
    if(length > 0){
        return true;
    }else{
        return false;
    }
}


function selectAll(){
    "use strict"
    var select = document.getElementById("warenkorbSelect");
    for(var i = 0; i < select.options.length; i++){
        select.options[i].selected = true;
    }
}