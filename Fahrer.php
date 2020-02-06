<?php	// UTF-8 marker äöüÄÖÜß€


require_once './Page.php';

class Fahrer extends Page
{
    private $orders;

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
        $sqlSelect = "SELECT BestellungID, Adresse, GROUP_CONCAT(PizzaName) As Pizzen, SUM(Preis) AS Gesamtpreis, Status 
        FROM bestellung JOIN (bestelltepizza JOIN angebot on PizzaNummer = fPizzaNummer) ON fBestellungID = BestellungID
        GROUP BY BestellungID
        HAVING 'fertig' = all(SELECT Status FROM bestelltepizza WHERE fBestellungID = BestellungID) OR
        'unterwegs' = all(SELECT Status FROM bestelltepizza WHERE fBestellungID = BestellungID) OR
        'geliefert' = all(SELECT Status FROM bestelltepizza WHERE fBestellungID = BestellungID)";

        $this->orders = $this->_database->query($sqlSelect);
    }

    protected function generateView() 
    {
        $this->getViewData();
        $this->generatePageHeader('Fahrer');

        echo
<<<HTML
    <script src="scripts/reload.js"></script>
    <article>
        <section class="sectionStyle">
            <h4 class="title">Pizzaübersicht für den Fahrer</h4>
            <form id="lieferstatus" action="Fahrer.php" method="POST">
HTML;

        if($this->orders->num_rows > 0)
        {
            while($row = $this->orders->fetch_assoc())
            {
                $address = htmlspecialchars($row['Adresse']);
                $pizzas = htmlspecialchars($row['Pizzen']);
                $price = htmlspecialchars($row['Gesamtpreis']);
                $status = htmlspecialchars($row['Status']);
                $orderID = htmlspecialchars($row['BestellungID']);

                $finished= $status == 'fertig' ? 'checked' : '';
                $delivering = $status == 'unterwegs' ? 'checked' : '';
                $delivered = $status == 'geliefert' ? 'checked' : '';

                if($status == 'fertig' || $status == 'unterwegs')
                {
                    echo
<<<HTML
                <div class="defaultContainer">
                    <div>
                        <p class="FatUnderlineTitle">Bestellung: $orderID </p>
                        <a class="UnderlineTitle">Anschrift:</a>
                        <a> $address</a>
                    </div>
                    <div>
                        <a class="UnderlineTitle">Pizzen:</a>
                        <a>$pizzas</a>
                    </div>
                    <div>
                        <a class="UnderlineTitle">Preis:</a>
                        <a>$price €</a>
                    </div>
                    <div class="status-items-header-start">
                        <span>
                            Fertig:
                        </span>
                        <span>
                            Unterwegs:
                        </span>
                        <span>
                            Geliefert:
                        </span>
                    </div>
                    <div class="status-items-item padding2">
                        <span>
                            <input type="radio" name=$orderID value="fertig" $finished onclick="document.forms['lieferstatus'].submit()">
                        </span>
                        <span>
                            <input type="radio" name=$orderID value="unterwegs" $delivering onclick="document.forms['lieferstatus'].submit()">
                        </span>
                        <span>
                            <input type="radio" name=$orderID value="geliefert" $delivered onclick="document.forms['lieferstatus'].submit()">
                        </span>
                    </div>
                </div>
HTML;
                }
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
                foreach($_POST as $orderID => $status)
                {
                    $orderID = $this->_database->real_escape_string($orderID);
                    $status = $this->_database->real_escape_string($status);
                    //Update der DB auf den neuen Status der Pizza
                    $sqlSelect = "UPDATE bestelltepizza SET Status = '$status' WHERE fBestellungID = $orderID";
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
            $page = new Fahrer();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Fahrer::main();
?>