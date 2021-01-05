<html>
    <head>
        <script type="text/javascript">

            function main(){
                name = "henry";
                //name2 = 99;
                document.getElementById("stateBtn").addEventListener("click",function(){		
                        //location.reload();
                        changeState();

                });
            }

            function ale(){
                alert("hello");
            }

            function changeState(){
                cid  = prompt("要變更的座位ID:");
                state = prompt("新的狀態:(0 or 1)");  
                
            }

        </script>
    </head>
    <body onload=main()>
        <input type="submit" name="submitButton" value="存入" />
        <button class="tool_button" id="stateBtn">更新狀態</button>
        
    </body>
</html>
<style>
.tool_button{
	padding: 15px 20px;
	margin: 1px 10px
}
</style>
<?php
    //require("pdo.php");
    //$name = "<script type='text/javascript'>document.write( name );</script>";
    //$id  = "<script type='text/javascript'>document.write(  prompt('要變更的座位ID:'); );</script>";
    //$st =  "<script type='text/javascript'>document.write( state );</script>";
    if(isset($_POST['submitButton'])){
        echo "hello";
     }
?>