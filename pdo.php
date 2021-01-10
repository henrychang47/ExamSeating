<?php
$dbms='mysql';
$host='118.232.212.69';
$dbName='SmartSeating';
$user='smartseating';
$pass='q96yji4jo4';
$dsn="$dbms:host=$host;dbname=$dbName";

try {
    $dbh = new PDO($dsn, $user, $pass); 

    //$targetExamID = $_GET['examID'];
    $seatIDARR = array();
    $stuIDArr = array();
    $statusArr = array();
    $nameArr = array();
    $classArr = array();

    $dbh->query("SET NAMES 'UTF8'");

    foreach ($dbh->query('SELECT * from Seating where examID='.$targetExamID) as $row) {
        
        $seatIDArr = explode(',', $row["seatID_array"]);
        $stuIDArr = explode(',', $row["username_array"]);
        $statusArr = explode(',', $row["status_array"]);
        
    }
    foreach( $stuIDArr as $username){
        if(strcmp($username, "n")!=0){
            $info = $dbh->query("SELECT name,class from users where username ='".$username."'")->fetch(PDO::FETCH_ASSOC);
            
            if(isset($info['name'])){
                array_push($nameArr,$info['name']);
            }else{
                array_push($nameArr,"");
            }
            
            if(isset($info['class'])){
                array_push($classArr,$info['class']);
            }else{
                array_push($classArr,"");
            }

        }else{
            array_push($nameArr,"n");
            array_push($classArr,"n");
        }
    }

    $info = $dbh->query("SELECT seatingID,examID FROM Seating WHERE examID=1")->fetch(PDO::FETCH_ASSOC);
    $seatingID = $info['seatingID'];
    $examID = $info['examID'];

    $dbh = null;
    
} catch (PDOException $e) {
    die ("Error!: " . $e->getMessage() . "<br/>");
}


?>
<script type="text/javascript">   
    window.seatingID=<?php echo $seatingID ?>;
    window.examID=<?php echo $examID ?>;
    window.seatIDArr = <?php echo json_encode($seatIDArr); ?>;
    window.stuIDArr = <?php echo json_encode($stuIDArr); ?>;
    window.statusArr = <?php echo json_encode($statusArr); ?>;
    window.nameArr = <?php echo json_encode($nameArr); ?>;
    window.classArr = <?php echo json_encode($classArr); ?>;
    window.backupSeatID = seatIDArr.slice();
    window.backupStuID = stuIDArr.slice();
    window.backupStatus = statusArr.slice();
</script>  
