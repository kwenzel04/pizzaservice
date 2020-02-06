<?php	// UTF-8 marker äöüÄÖÜß€


require_once './Page.php';
require_once './Pizza.php';

class Bestellung extends Page
{

    private $pizzaList = array();

    
    protected function __construct() 
    {
        parent::__construct();
    }
    
    protected function __destruct() 
    {
        parent::__destruct();
    }

 
    protected function getViewData()
    {
        $sqlSelect = "SELECT PizzaName, Bilddatei, Preis FROM Angebot";
        $db_output = $this->_database->query($sqlSelect); 
        if($db_output->num_rows > 0)
        {
            while($row = $db_output->fetch_assoc())
            {
                $this->pizzaList[] = new Pizza($row);
            }
        }
             
    }
    
    protected function generateView() 
    {
        $this->getViewData();
        $this->generatePageHeader('Bestellung');
        echo
<<<HTML
    <article>
    <section class="sectionStyle">
        <h1 class="title">Speisekarte</h1>
        
            <div class="pizzaWrapper">
HTML;

        foreach($this->pizzaList as $pizzaItem){
            $pizzaItem->printPizza();
        }
        echo
<<<HTML
            </div>
        </section>
        <section class="sectionStyle">
            <h1 class="title">Warenkorb</h1>
            <!-- <ul id="warenkorbListe" class="warenkorbStyle">
            </ul> -->
            <form id="warenkorb" action="Kunde.php" method="POST" onsubmit="return submitCondition()">
                <select id="warenkorbSelect" multiple name="pizzas[]" tabindex="3">
                </select><br>
                <input class="inputStyle" id="total" type="text" name="price" value="0.00" disabled/><br>
                <input class="inputStyle" type="text" id = address name="address" placeholder="Name, Adresse" tabindex="4" required/><br>
                <button class="blackButton" type="submit" tabindex="5" onclick="selectAll()">Bestellen</button>
                <button class="blackButton" type="button" tabindex="6" onclick="deleteSelected()">Auswahl Löschen</button>
                <button class="blackButton" type="button" tabindex="7" onclick="deleteAll()">Alle Löschen</button>                  
            </form>
        </section>
    </article>    
HTML;
        $this->generatePageFooter();
    }
    
    
    protected function processReceivedData() 
    {
        parent::processReceivedData();
    }
  
    public static function main() 
    {
        try {
            $page = new Bestellung();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Bestellung::main();
?>