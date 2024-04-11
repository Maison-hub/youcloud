<?php
namespace youcloud\Services\ViaPdo;

class Bdd {
    public  $connect = null;
    public function __construct(){
        try{
            $this->connect=new \PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8',
                                            BDD_HOST, BDD_BDNAME), 			   
                                    BDD_USER, BDD_PASS,
                                    array(
                                        \PDO::ATTR_PERSISTENT => true,
                                        //                                        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                                    )
            );
        }catch (\PDOException $e) {
            $msg="Error!: " . $e->getMessage() ;
            if (ini_get('display_error')){
                print($msg. "<br/>");
            }
            error_log($msg);
            die();    
        }
    }
    protected function log($msg){
        error_log($msg);
        /*
        if (ini_get('display_errors')){
            print($msg.'<br>');
        }
        */
    }
    public function estConnectee():bool{
        return $this->connect!==null;
    }
    
    public function executer(\PDOStatement $stmt, int $line=0, string $file=__FILE__):bool{
        $ex=$stmt->execute();
        if ($ex===FALSE){
            /* avoid stuf in json !!
            Typically tail -f /var/log/apache2/error.log       
            Normalement les requêtes SQL sont correctes, mais pour l'ajout
            on risque d'avoir des double sur certains index primaire
            dont la valeur vient du csv.
            */
            ob_start();
            $stmt->debugDumpParams();
            $stmtdgstr=ob_get_contents();
            ob_end_clean();
            //see: https://www.php.net/manual/fr/pdostatement.errorinfo.php
            $errorInfos=$stmt->errorInfo();
            $this->log(sprintf("execute failed %s -  %s - %s - %s : %s (%s:%d)", 
            $stmt->errorCode(), $errorInfos[0], $errorInfos[1], $errorInfos[2],
            $stmtdgstr,
            $file, $line));
        }
        return ($ex===FALSE?FALSE:TRUE); //PHP bool sucks !
    }
}

