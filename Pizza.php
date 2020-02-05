<?php	// UTF-8 marker äöüÄÖÜß€

class Pizza {

private $imageFile;
private $pizzaName;
private $pizzaPrice;

public function __construct($pizza){
    $this->imageFile = htmlspecialchars($pizza["Bilddatei"]);
    $this->pizzaName = htmlspecialchars($pizza["PizzaName"]);
    $this->pizzaPrice = htmlspecialchars($pizza["Preis"]);
}

public function printPizza(){
    $path = "./images/";
    $path .= $this->imageFile;
    echo
<<<HTML
                    <span> 
                        <img class="pizzaImage" src="$path" widht="300px" height="300px" alt="$this->pizzaName">
                    </span>
                    <span>
                        <a class="paragraphCenter">$this->pizzaName</a>
                        <a class="paragraphCenter">$this->pizzaPrice €</a> 
                    </span>
HTML;
}
}
?>