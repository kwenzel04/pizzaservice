<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class KundenStatus extends Page
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
        $serializedData = json_encode($this->customerMap);
        echo $serializedData;
    }
    

    
    protected function generateView() 
    {
        $this->getViewData();

    }
    
    protected function processReceivedData() 
    {
        header("Content-Type: application/json; charset=UTF-8");
        //bleibt leer
        parent::processReceivedData();
    }
    





    public static function main() 
    {
        session_start();
        try {
            $page = new KundenStatus();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


KundenStatus::main();

?>