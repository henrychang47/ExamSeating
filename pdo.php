<?php
$dbms='mysql';     //数据库类型
$host='localhost'; //数据库主机名
$dbName='test';    //使用的数据库
$user='root';      //数据库连接用户名
$pass='';          //对应的密码
$dsn="$dbms:host=$host;dbname=$dbName";

try {
    $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
    //echo "连接成功<br/>";

    $i=0;
    $seating = array();
    $examID = 1;//eid to check
    $seatingID = null;
    $seatIDARR = array();
    $stuIDArr = array();

    foreach ($dbh->query('SELECT * from seatings where examID=1') as $row) {
        //print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
        //echo $row["id"] . " " . $row["state"] . "<br>";
        //$classroom[$i]["id"] = $row["id"];
        //$classroom[$i++]["state"] = $row["state"];
        if($row["examID"] == $examID){
            $seatIDArr = explode(',', $row["seatIDArr"]);
            $stuIDArr = explode(',', $row["stuIDArr"]);
            break;
        }
    }


    $info = $dbh->query("SELECT seatingID,examID FROM seatings WHERE examID=1")->fetch(PDO::FETCH_ASSOC);
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
    window.backupSeatID = seatIDArr.slice();
    window.backupStuID = stuIDArr.slice();
</script>  
