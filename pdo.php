<?php
$dbms='mysql';     //数据库类型
$host='118.232.212.69';//'localhost'; //数据库主机名
$dbName='SmartSeating';//'test';    //使用的数据库
$user='smartseating';//'root';      //数据库连接用户名
$pass='q96yji4jo4';//'';          //对应的密码
$dsn="$dbms:host=$host;dbname=$dbName";

try {
    $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
    //echo "连接成功<br/>";

    $i=0;
    $seating = array();
    $tocheck_examID = 1;
    $seatingID = null;
    $seatIDARR = array();
    $stuIDArr = array();
    $statusArr = array();
    $nameArr = array();
    $classArr = array();

    $dbh->query("SET NAMES 'UTF8'");

    foreach ($dbh->query('SELECT * from Seating where examID=1') as $row) {
        //print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
        //echo $row["id"] . " " . $row["state"] . "<br>";
        //$classroom[$i]["id"] = $row["id"];
        //$classroom[$i++]["state"] = $row["state"];
        if($row["examID"] == $tocheck_examID){
            $seatIDArr = explode(',', $row["seatID_array"]);
            $stuIDArr = explode(',', $row["username_array"]);
            $statusArr = explode(',', $row["status_array"]);
            break;
        }
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

//默认这个不是长连接，如果需要数据库长连接，需要最后加一个参数：array(PDO::ATTR_PERSISTENT => true) 变成这样：
//$db = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));
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
</script>  
