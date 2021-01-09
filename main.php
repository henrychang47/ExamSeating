<?php
    require("pdo.php");
?>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>監考頁面</title>

        <!-- Sets the basepath for the library if not in same directory -->
        <script type="text/javascript">
            mxBasePath = './src';
        </script>

        <!-- Loads and initializes the library -->
        <script type="text/javascript" src="./src/mxClient.js"></script>

        <!-- functions -->
        <script type="text/javascript" src="functions.js"></script>
        <!-- Example code -->
        <script type="text/javascript">

            function main(container) {
                // Checks if the browser is supported
                if (!mxClient.isBrowserSupported()) {
                    // Displays an error message if the browser is not supported.
                    mxUtils.error('Browser is not supported!', 200, false);
                } else {
                    // Creates the graph inside the given container
                    window.graph = new mxGraph(container);
    
                    //graph.setEnabled(false);//禁止任何與graph的互動
                    graph.setPanning(true); //panning:平移
                    graph.setCellsLocked(true) //
                     //graph.setTooltips(true);//tooltips:工具提示
                    graph.panningHandler.useLeftButtonForPanning = true; //左鍵可平移整張圖

                    new mxCellTracker(graph, '#FF0000'); //滑鼠停留時發亮

                    graph.isHtmlLabel = function(cell) 
                    {
                        return true; 
                    };

                    graph.getModel().beginUpdate();
                    try {
                        read(graph, examID+'.xml');                      
                    } finally {
                        graph.getModel().endUpdate();
                    }

                    graph.getSelectionModel().addListener(mxEvent.CHANGE, function(sender, evt) {
                        selectionChanged(graph);
                    });

                    selectionChanged(graph);

                    //test
                    document.getElementById("reloadBtn").addEventListener("click", function() {
                        location.reload();
                    });

                    stateAutoRefresh();

                    if (mxClient.IS_QUIRKS) //IS_QUIRKS:True if the current browser is Internet Explorer and it is in quirks mode.
                    {
                        document.body.style.overflow = 'hidden';
                        new mxDivResizer(container);
                    }

                    window.editing=false;

                    setInfobox();
                    setAutoReload(<?php echo isset($_GET["autoReload"])?$_GET["autoReload"]:-1 ?>, false);
                }
            };

        </script>
    </head>

    <!-- Page passes the container for the graph to the program -->

    <body onload="main(document.getElementById('graphContainer'))" style="margin:4px;">

        <table style="margin-left:auto; margin-right:auto; width:1600px; text-align:center;">
            <tr>          
                <td >
                    <div id="tools" style="border: solid 3px black; padding: 10px;  height: 50px;">
                        <button class="tool_button">離開</button>
                        <button class="tool_button" onclick="editMode()" id="editBtn">變更座位</button>
                        <button class="tool_button" onclick="cancelEdit()" id="cancelEditBtn" disabled="true">取消變更</button>
                        <button class="tool_button" onclick="finishEdit()" id="finishEditBtn" disabled="true">完成變更</button>
                        <button class="tool_button" id="reloadBtn">刷新頁面</button>
                        <font size='5'>自動刷新間隔:</font>
                        <select value="10" id="autoReloader" onchange="setAutoReload(this.value,true)" style="font-size:20px">
                            <option value='0'>禁止自動刷新</option>
                            <option value='5'>每五秒進行刷新</option>
                            <option value='10'>每十秒進行刷新</option>
                            <option value='30'>每三十秒進行刷新</option>
                            <option value='60'>每一分鐘進行刷新</option>
                        </select>
                    </div>
                </td>
                <td style="border: solid 3px black; padding: 10px;  height: 50px; weight=100px">
                    <div id="msgBox"><font size="5" id="edit_message" >viewMode</font></div>
                </td>
            </tr>

            <tr>
                <td rowspan="2">
                    <div id="graphContainer" style="border: solid 3px black; overflow:hidden; padding: 10px; width:1200px; height:900px;">
                    </div>
                </td>
                <td>
                    <div id="properties" style="border: solid 3px black; padding: 10px; width:400px;height:570px;font-size:30px">
                    </div>
                </td>
            </tr>
            <tr>
                 <td>
                    <div id="properties2" style="border: solid 3px black; padding: 10px; width:400px;height:300px; text-align:left;">
                    <h2 id="seatingid">座位表id: </h2>
                    <h2 id="examid">考試id: </h2>
                    <h2 id="states">考試狀態: </h2>
                    <div class="state_colors state_unassigned"></div><h3 id="state_unassigned">空位</h3>
                    <div class="state_colors state_login"></div><h3 id="state_login">正常登入</h3> 
                    <div class="state_colors state_backupLogin"></div><h3 id="state_backupLogin">備用登入</h3>
                    </div>
                </td>
            </tr>
        </table>
    </body>
    <style>
        .tool_button {
            padding: 15px 20px;
            margin: 1px 10px
        }

        .state_colors {
            float: left;
            width: 15px;
            height: 15px;
            margin: 5px;
            border: 1px solid rgba(0, 0, 0, .2);
        }

        .state_unassigned {
            background: #CFD2DE;
         }

        .state_login {
            background: #D1E1CB;
        }

        .state_backupLogin{
            background: #F5BE8E;
        }
    </style>

    </html>