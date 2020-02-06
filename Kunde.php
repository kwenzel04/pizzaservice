<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Kunde extends Page
{
    private $orderedPizzas = array();
    private $address = "";
    private $lastOrderID;
    private $customerMap = array();

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
        if(isset($_SESSION['orderID'])){
            $sqlSelect = "SELECT PizzaName, Status, fBestellungID, PizzaID FROM angebot JOIN bestelltepizza ON PizzaNummer = fPizzaNummer WHERE fBestellungID = {$_SESSION['orderID']} ORDER BY fBestellungID";
            $db_output = $this->_database->query($sqlSelect);
            if($db_output->num_rows > 0)
            {
                while($row = $db_output->fetch_assoc())
                {
                    $this->customerMap[] = ['name' => htmlspecialchars($row['PizzaName']), 'status' => htmlspecialchars($row['Status']), 'bID' => htmlspecialchars($row['fBestellungID']), 'pID' => htmlspecialchars($row['PizzaID'])];
                }
            }
        }
    }  


    
    protected function generateView() 
    {
        $this->getViewData();
        $this->generatePageHeader('Kunde');
        echo
<<<HTML
    <script src="scripts/StatusUpdate.js"></script>
    <article>
        <section class="sectionStyle">
            <h2 class="title">Lieferstatus</h2>
            <div class="status-items-header">
                <span>Bestellung:</span>
                <span>Bestellt:</span>
                <span>Im Ofen:</span>
                <span>Fertig:</span>
                <span>Unterwegs:</span>
                <span>Geliefert</span>
            </div>
            <div id="customerOrder">
HTML;
        foreach($this->customerMap as $cM)
        {
            $ordered = $cM['status'] == 'bestellt' ? 'checked' : '';
            $inOven = $cM['status'] == 'im ofen' ? 'checked' : '';
            $finished = $cM['status'] == 'fertig' ? 'checked' : '';
            $delivering = $cM['status'] == 'unterwegs' ? 'checked' : '';
            $delivered = $cM['status'] == 'geliefert' ? 'checked' : '';
            $pizzaID = $cM['pID'];
            echo
<<<HTML
                <div class="defaultContainer status-items-item">
                    <span>
                        ({$cM['bID']}) {$cM['name']}
                    </span>
                    <span>
                        <input type="radio" name=$pizzaID value="bestellt" $ordered disabled>
                    </span>
                    <span>
                        <input type="radio" name=$pizzaID value="im ofen" $inOven disabled>
                    </span>
                    <span>
                        <input type="radio" name=$pizzaID value="fertig" $finished disabled>
                    </span>
                    <span>
                        <input type="radio" name=$pizzaID value="unterwegs" $delivering disabled>
                    </span>
                    <span>
                        <input type="radio" name=$pizzaID value="geliefert" $delivered disabled>
                    </span>
                </div>
HTML;
        }


        echo
<<<HTML
            </div>
            <div>
                <button class="blackButton" onclick="location.href = 'bestellung.php';">Neue Bestellung</button>
            </div>     
        </section>
    </article>
HTML;

        $this->generatePageFooter();
    }





    
    protected function processReceivedData() 
    {
        parent::processReceivedData();
        {
        //falls die Seite nicht über den Button geöffnet wird, muss nichts aus _POST eingelesen werden
        if(isset($_POST['address']) && isset($_POST['pizzas']))
        {
            
            //Werte aus _POST in variablen übertragen
            $this->orderedPizzas = $_POST ["pizzas"];
            $this->address = $_POST["address"];
            $this->address = $this->_database->real_escape_string($this->address);

            //Bestellung mit der Adresse $adress in der DB anlegen
            $sqlInsert = "INSERT INTO bestellung(Adresse) VALUES ('$this->address')";
            $this->_database->query($sqlInsert); 

            //ID der zuletzt hinzugefügten Bestellung
            $this->lastOrderID = $this->_database->insert_id;

            //OrderID in Session schreiben
            $_SESSION['orderID'] = $this->lastOrderID;

            $this->lastOrderID = $this->_database->real_escape_string($this->lastOrderID);
            
            //Jede bestellte Pizza wird in die tabelle BestelltePizza inserted
            foreach($this->orderedPizzas as $pizza){
                $sqlSelect = "SELECT PizzaNummer FROM Angebot WHERE PizzaName = '$pizza'";
                $db_output = $this->_database->query($sqlSelect)->fetch_assoc();
                $pizzaID = $db_output['PizzaNummer'];
                $pizzaID = $this->_database->real_escape_string($pizzaID);
                $sqlInsert = "INSERT INTO bestelltepizza(fBestellungID, fPizzaNummer, Status) VALUES ('$this->lastOrderID', '$pizzaID', 'bestellt')";
                $this->_database->query($sqlInsert); 
            }
        }
        else
        {
            return;
        }
                
        //falls Probleme beim schreiben in DB -> gibt Fehler aus
        //echo $this->_database->errno.'-'.$this->_database->error;
        }
    }

    public static function main() 
    {
        session_start();
        try {
            $page = new Kunde();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


Kunde::main();
?>