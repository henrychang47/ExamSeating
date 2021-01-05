<?php
    require("pdo.php");

    try {
        $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
        echo "连接成功<br/>";
    
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE seatings SET stuIDArr='" .$_GET["newStr"] ."' WHERE seatingID = 1";
      
        // Prepare statement
        $stmt = $dbh->prepare($sql);
      
        // execute the query
        $stmt->execute();
      
        // echo a message to say the UPDATE succeeded
        echo $stmt->rowCount() . " records UPDATED successfully";
        
    } catch (PDOException $e) {
        die ("Error!: " . $e->getMessage() . "<br/>");
    }

?>
<script>
    window.location.href = 'main.php';
</script>