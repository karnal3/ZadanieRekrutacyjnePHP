<?php
include "db.php";
class File {

    private $localisation;
    private $id;
    private $vac;
    private $startDate;
    private $endDate;
    private $dateSet;
    private $text;
//test 
    public function __construct($arg){
        $dateCreate=new DateTime();
        if (isset($arg['vac'])) $this->vac = $arg['vac'];
        if (isset($_SESSION['id'])) $this->id = $_SESSION['id'];
        if (isset($arg['startDate'])) $this->startDate = $arg['startDate'];
        if (isset($arg['endDate'])) $this->endDate = $arg['endDate'];
        $this->dateSet = $dateCreate->format('Y-m-d H:i:s');
        if (isset($arg['text'])) $this->text = $arg['text'];
        if (isset($_FILES['myfile'])) $this->validateExtentionFile();
        if (isset($_FILES['myfile']['tmp_name']))$this->localisation = 'temp/'.$dateCreate->format('Y_m_d_H_i_s_u').$_FILES['myfile']['name'];
    }

    public function addFile(){
        try {
            if(is_uploaded_file($_FILES['myfile']['tmp_name'])) {
                if(!move_uploaded_file($_FILES['myfile']['tmp_name'],$this->localisation )) throw new Exception("Problem: Nie udało się skopiować pliku do katalogu.");}
            else throw new Exception("Problem: Możliwy atak podczas przesyłania pliku.<BR/>Plik nie został zapisany."); 

            $sql = "INSERT INTO Docs ( iduser, vactype, datefrom, dateto, dateset, fileurl, comment)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            (new DbConn())->sql($sql,[$this->id,$this->vac,$this->startDate,$this->endDate,$this->dateSet ,$this->localisation,$this->text]);
            echo 'Dodano prośbę o urlop<br>';
        } catch (Exception $e) {
            echo  $e->getMessage().'<br>';
            $_SESSION["err"] =$e->getMessage();
        }
     

    }

    public function deleteFile($id){
        try{
            $sql = "SELECT iduser,fileurl FROM Docs WHERE id=?";
            $result =  (new DbConn())->sql($sql,[$id]);
            $result = $result->fetch(PDO::FETCH_ASSOC);
            if ($_SESSION['id']!=$result['iduser']) throw new Exception("Problem, nie jesteś autoryzowany do usunięcia pliku");
            $sql = "DELETE  FROM Docs WHERE id=?";
            (new DbConn())->sql($sql,[$id]);
            array_map('unlink', glob($result['fileurl'])); 
            echo 'Usunięto prośbę o urlop <br>';
        } catch (Exception $e) {
            echo  $e->getMessage().'<br>';
            $_SESSION["err"] =$e->getMessage();
        }
    }

    protected function validateExtentionFile(){
        try {
            if (!isset($_FILES)) throw new Exception("Nie przesłąno pliku.");
            if ($_FILES['myfile']['error'] > 0)
		    {
                switch ($_FILES['myfile']['error'])
                {
                    // jest większy niż domyślny maksymalny rozmiar,
                    // podany w pliku konfiguracyjnym
                    case 1: {throw new Exception("Rozmiar pliku jest zbyt duży.") ; break;} 
                    // jest większy niż wartość pola formularza 
                    // MAX_FILE_SIZE
                    case 2: {throw new Exception("Rozmiar pliku jest zbyt duży."); break;}
                    // plik nie został wysłany w całości
                    case 3: {throw new Exception("Plik wysłany tylko częściowo."); break;}
                    // plik nie został wysłany
                    case 4: {throw new Exception("Nie wysłano żadnego pliku."); break;}
                    // pozostałe błędy
                    default: {throw new Exception("Wystąpił błąd podczas wysyłania.".$_FILES['myfile']['error']);
                    break;}
                }
            }
            $filter = array('image/jpeg', 'image/jng', 'application/pdf');
            if (!in_array($_FILES["myfile"]["type"], $filter)) throw new Exception("Wysłany plik ma złe rozszerzenie.");

        } catch (Exception $e) {
            echo  $e->getMessage().'<br><a href=".">Refresh</a>';
            $_SESSION["err"] =$e->getMessage();
            die();
        }
    }


    
}

?>