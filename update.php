<?php
    //require("pdo.php");

    $dbms='mysql';
    $host='118.232.212.69';
    $dbName='SmartSeating';
    $user='smartseating';
    $pass='q96yji4jo4';
    $dsn="$dbms:host=$host;dbname=$dbName";

    try {
        $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE Seating SET username_array= '" 
            . $_GET["newUsername"] . "',status_array='" 
            . $_GET["newStatus"] . "' WHERE examID = ' " . $_GET['examID'] . " ' ";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
      
        // echo a message to say the UPDATE succeeded
        echo $stmt->rowCount() . " records UPDATED successfully";
        
    } catch (PDOException $e) {
        die ("Error!: " . $e->getMessage() . "<br/>");
    }

?>
<html>
    <body>
        <form id="eid" action="main.php" method="post">
            <input type="hidden" name="examID" value="<?php echo $_GET['examID']?>">
        </form>
    </body>
</html>
<script>
    //window.location.href = 'main.php';
    document.getElementById("eid").submit();
</script>