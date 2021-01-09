<?php
    require("pdo.php");

    try {
        $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
        echo "连接成功<br/>";

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //echo $_GET["newUsername"];
        //echo $_GET["newStatus"];
        $sql = "UPDATE Seating SET username_array= '" . $_GET["newUsername"] . "',status_array='" . $_GET["newStatus"] . "' WHERE seatingID = 1";
      
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