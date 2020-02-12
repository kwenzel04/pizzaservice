<?php

require_once './Page.php';

class Abrechnung extends Page
{


    protected function __construct(){

        parent::__construct();
    }

    protected function __destruct(){

        parent::__destruct();
    }

    protected function getViewData(){

    
    }

    protected function generateView(){
        $this->generatePageHeader('Abrechnung');
        
        echo
        <<<HTML
            <script src="scripts/abbrechnung.js"></script>
            <article>
                <section class="sectionStyle">
                    <h4 class="title">Abrechnung</h4>
                </section>
                <section id="tableSec">
                </section>
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
            $page = new Abrechnung();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Abrechnung::main();
?>
