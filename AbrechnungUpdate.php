<?php

require_once './Page.php';

class AbrechnungUpdate extends Page
{

    private $result = array();


    protected function __construct(){

        parent::__construct();
    }

    protected function __destruct(){

        parent::__destruct();
    }

    protected function getViewData(){

        $sqlSelect = "SELECT BestellungID, Adresse, Bestellzeitpunkt FROM bestellung";
        $dbOutput = $this->_database->query($sqlSelect);

        if($dbOutput->num_rows>0)
        {
            while($row=$dbOutput->fetch_assoc())
            {
                $this->result[] = ['BestellungID'=> htmlspecialchars($row['BestellungID']), 'Adresse'=>htmlspecialchars($row['Adresse']), 'Bestellzeitpunkt'=>htmlspecialchars($row['Bestellzeitpunkt'])];
            }
        }
        $serData = json_encode($this->result);
        echo $serData;
    }

    protected function generateView(){
        $this->getViewData();
    }

    protected function processReceivedData() 
    {
        header("Content-Type: application/json; charset=UTF-8");
        parent::processReceivedData();
    }

    public static function main() 
    {
        try {
            $page = new AbrechnungUpdate();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

AbrechnungUpdate::main();
?>