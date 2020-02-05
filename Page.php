<?php	
 
abstract class Page
{
    // --- ATTRIBUTES ---

    private $user = 'root';
    private $pw = "";
    private $db = 'pizzaservice';

    protected $_database = null;
    
    // --- OPERATIONS ---
    
    protected function __construct() 
    {
        $this->_database = new mysqli('localhost', $this->user, $this->pw, $this->db) or die ("unable to connect!");
    }
    

    protected function __destruct()    
    {
        $this->_database -> close();
    }
    

    protected function generatePageHeader($headline = "") 
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
        
        $currentPage = basename($_SERVER['PHP_SELF']);
        echo

<<<HTML
    <!DOCTYPE html>
    <html lang="de">  
    <head>
        <meta charset="UTF-8" />
        <title>$headline</title>
    </head>
    <header></header>
    <body class="body">
        <nav class="navbar">
            <ul id="nav">
HTML;

//NAVBAR----------

if($currentPage== "Bestellung.php"){
    echo
<<<HTML
                <li><a class="active" href="Bestellung.php">Bestellung</a></li>
                <li><a href="Kunde.php">Kunde</a></li>
                <li><a href="Baecker.php">Bäcker</a></li>
                <li><a href="Fahrer.php">Fahrer</a></li>
            </ul>
        </nav>
HTML;
}


if($currentPage== "Kunde.php"){
    echo
<<<HTML
                <li><a href="Bestellung.php">Bestellung</a></li>
                <li><a class="active" href="Kunde.php">Kunde</a></li>
                <li><a href="Baecker.php">Bäcker</a></li>
                <li><a href="Fahrer.php">Fahrer</a></li>
            </ul>
        </nav>
HTML;
}


if($currentPage== "Baecker.php"){
    echo
<<<HTML
                <li><a href="Bestellung.php">Bestellung</a></li>
                <li><a href="Kunde.php"> Kunde</a></li>
                <li><a class="active" href="Baecker.php">Bäcker</a></li>
                <li><a href="Fahrer.php">Fahrer</a></li>
            </ul>
        </nav>
HTML;
}

if($currentPage== "Fahrer.php"){
    echo
<<<HTML
                <li><a href="Bestellung.php">Bestellung</a></li>
                <li><a href="Kunde.php"> Kunde</a></li>
                <li><a href="Baecker.php">Bäcker</a></li>
                <li><a class="active" href="Fahrer.php">Fahrer</a></li>
            </ul>
        </nav>
HTML;


    }

}




    protected function generatePageFooter() 
    {
        echo
<<<HTML
    </body>
</html>
HTML;
    }

    protected function processReceivedData() 
    {
        if (get_magic_quotes_gpc()) {
            throw new Exception
                ("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
} 

?>