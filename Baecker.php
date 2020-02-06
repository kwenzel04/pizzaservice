<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Baecker extends Page
{
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
        $sqlSelect = "SELECT PizzaName, Status, fBestellungID, PizzaID FROM angebot, bestelltepizza 
                        WHERE PizzaNummer = fPizzanummer 
                        AND fBestellungID NOT IN 
                        (SELECT BestellungID FROM bestellung, bestelltepizza WHERE BestellungID = fBestellungID 
                        GROUP BY BestellungID HAVING 'unterwegs' = all(SELECT Status FROM bestelltepizza WHERE fBestellungID = BestellungID)) 
                        ORDER BY fBestellungID";


        $db_output = $this->_database->query($sqlSelect);
        if($db_output->num_rows > 0)
        {
            while($row = $db_output->fetch_assoc())
            {
                $this->customerMap[] = ['name' => htmlspecialchars($row['PizzaName']), 'status' => htmlspecialchars($row['Status']), 'bID' => htmlspecialchars($row['fBestellungID']), 'pID' => htmlspecialchars($row['PizzaID'])];
            }
        }

    }
    
    protected function generateView() 
    {
        $this->getViewData();
        $this->generatePageHeader('Bäcker');

        echo
<<<HTML
    <script src="scripts/reload.js"></script>
    <article>
        <section class="sectionStyle">
            <h4 class="title">Pizzaübersicht für den Bäcker</h4>
            <form id="ovenstatus" action="Baecker.php" method="POST">
                <div class="status-items-header">
                    <span>
                        Bestllung:
                    </span>
                    <span>
                        Bestellt:
                    </span>
                    <span>
                        Im Ofen:
                    </span>
                    <span>
                        Fertig:
                    </span>
                </div>
HTML;

        foreach($this->customerMap as $cM)
        {
            $ordered = $cM['status'] == 'bestellt' ? 'checked' : '';
            $inOven = $cM['status'] == 'im ofen' ? 'checked' : '';
            $finished = $cM['status'] == 'fertig' ? 'checked' : '';
            $pizzaID = $cM['pID'];

            //Bäcker sieht nur Pizzen, die noch nicht fertig sind
            if($cM['status'] == 'bestellt' || $cM['status'] == 'im ofen' || $cM['status'] == 'fertig')
            {
                echo
<<<HTML
                <div class="defaultContainer status-items-item">
                    <span>
                        ({$cM['bID']}) {$cM['name']}
                    </span>
                    <span>
                        <input type="radio" name=$pizzaID value="bestellt" $ordered onclick="document.forms['ovenstatus'].submit()">
                    </span>
                    <span>
                        <input type="radio" name=$pizzaID value="im ofen" $inOven onclick="document.forms['ovenstatus'].submit()">
                    </span>
                    <span>
                        <input type="radio" name=$pizzaID value="fertig" $finished onclick="document.forms['ovenstatus'].submit()">
                    </span>
                </div>
HTML;
            }
        }

        echo
<<<HTML
            </form>
        </section>
    </article>
HTML;

        $this->generatePageFooter();
    }
    

    protected function processReceivedData() 
    {
        parent::processReceivedData();

        //Bearbeiten der Werte der Radiobuttons
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            foreach($_POST as $orderedPizzaid => $status)
            {
                $orderedPizzaid = $this->_database->real_escape_string($orderedPizzaid);
                $status = $this->_database->real_escape_string($status);
                //Update der DB auf den neuen Status der Pizza
                $sqlSelect = "UPDATE bestelltepizza SET Status = '$status' WHERE PizzaID = $orderedPizzaid";
                $this->_database->query($sqlSelect);
            }
        }
        else
        {
            return;
        }



    }
 
    public static function main() 
    {
        try {
            $page = new Baecker();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Baecker::main();

?>