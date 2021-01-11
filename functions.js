// Parses the mxGraph XML file format
function read(graph, filename) {
    var req = mxUtils.load(filename);
    var root = req.getDocumentElement();
    var dec = new mxCodec(root.ownerDocument);

    dec.decode(root, graph.getModel());
};

function selectionChanged(graph) {
    var div = document.getElementById('properties');

    // Forces focusout in IE
    graph.container.focus();

    // Clears the DIV the non-DOM way
    div.innerHTML = '';

    // Gets the selection cell
    var cell = graph.getSelectionCell();

    if (cell == null) {
        mxUtils.writeln(div, 'Nothing selected.');
    } else {
        // Writes the title
        var center = document.createElement('center');

        mxUtils.writeln(center, '座位id: ' + cell.id);
        mxUtils.writeln(center, "-------------------------------------")



        if (seatIDArr.includes(cell.id)) {

            if (stuIDArr[seatIDArr.indexOf(cell.id)] != "n") {

                var img = document.createElement('img');
                img.src = "headpic.png";
                center.appendChild(img);

                var br = document.createElement('br');
                center.appendChild(br);

                mxUtils.writeln(center, "帳號: " + cell.getValue() + "");
                mxUtils.writeln(center, "姓名: " + nameArr[seatIDArr.indexOf(cell.id)]);
                mxUtils.writeln(center, "班級: " + classArr[seatIDArr.indexOf(cell.id)]);
            } else {
                mxUtils.writeln(center, "空位");
            }

        } else {
            mxUtils.writeln(center, "未登記的座位");
        }



        div.appendChild(center);
        mxUtils.br(div);

        //換位子模式
        if (window.editing == true && seatIDArr.indexOf(cell.id) != -1 && stuIDArr[seatIDArr.indexOf(cell.id)] != "n") {

            var moveto = prompt("將" + cell.value + "移動到:");

            //取消
            if (moveto == null) {
                //alert("cancel");
                graph.getSelectionModel().removeCell(cell);
            } else if (moveto == "-1") {
                statusArr[seatIDArr.indexOf(cell.id)] = 1;
            } else if (moveto == "-2") {
                statusArr[seatIDArr.indexOf(cell.id)] = 2;
            } else if (moveto == "-3") {
                statusArr[seatIDArr.indexOf(cell.id)] = 3;
            }
            //輸入不可換入的座位號碼
            else if (stuIDArr[seatIDArr.indexOf(moveto)] != "n") {
                alert("此座位不可用");
            } else {
                //alert("可換");
                changeSeat(cell.id, moveto);
            }
            stateAutoRefresh();

        }

    }
}

function stateAutoRefresh() {

    seatIDArr.forEach(function(seatID, index) {
        cell = graph.getModel().getCell(seatID);
        if (statusArr[index] == "0") {
            cell.setStyle("fillColor=#CFD2DE");
            if (stuIDArr[index] == "n") {
                cell.setValue("(" + cell.id + ")");
            } else {
                cell.setValue(stuIDArr[index]);
            }
        } else if (statusArr[index] == "1") {
            cell.setStyle("fillColor=#D1E1CB");
            cell.setValue(stuIDArr[index]);
        } else if (statusArr[index] == "2") {
            cell.setStyle("fillColor=#F5BE8E");
            cell.setValue(stuIDArr[index]);
        }

    })

    graph.refresh();
}
//顯示可換目標
function interChangeable(graph) {

    seatIDArr.forEach(function(seatID, index) {
        if (stuIDArr[index] == "n") {
            var cell = graph.getModel().getCell(seatID);
            cell.setStyle("fillColor=green");
            cell.setValue(cell.id);
        }

    })
    graph.refresh();
}

//換座位
function changeSeat(oldID, newID) {
    var oldIndex = seatIDArr.indexOf(oldID);
    var newIndex = seatIDArr.indexOf(newID);

    stuIDArr[newIndex] = stuIDArr[oldIndex];
    statusArr[newIndex] = "0";
    stuIDArr[oldIndex] = "n";
    statusArr[oldIndex] = "0";
}

function editMode() {
    window.editing = true;
    document.getElementById("edit_message").innerText = "editMode";
    document.getElementById("edit_message").color = "blue";
    document.getElementById("graphContainer").style.borderColor = "blue";
    document.getElementById("editBtn").disabled = true;
    document.getElementById("cancelEditBtn").disabled = false;
    document.getElementById("finishEditBtn").disabled = false;

    var cell = graph.getSelectionCell();
    graph.getSelectionModel().removeCell(cell);
}

function cancelEdit() {
    window.editing = false;
    document.getElementById("edit_message").innerText = "viewMode ";
    document.getElementById("edit_message").color = "black";
    document.getElementById("graphContainer").style.borderColor = "black";
    document.getElementById("editBtn").disabled = false;
    document.getElementById("cancelEditBtn").disabled = true;
    document.getElementById("finishEditBtn").disabled = true;

    stuIDArr = backupStuID.slice();
    statusArr = backupStatus.slice();
    stateAutoRefresh();
}

function finishEdit() {
    window.editing = false;
    document.getElementById("edit_message").innerText = "viewMode ";
    backupStuID = stuIDArr.slice();

    var newUsername = stuIDArr.join(',');
    var newStatus = statusArr.join(',');

    var newdata = "newUsername=" + newUsername + "&" + "newStatus=" + newStatus +
        "&" + "examID=" + examID;

    //console.log(newdata);
    window.location.href = 'update.php?' + newdata;
}

function setInfobox() {
    document.getElementById("seatingid").innerHTML += seatingID;
    document.getElementById("examid").innerHTML += examID;

    var unassigned_count = 0;
    var login_count = 0;
    var backupLogin_count = 0;
    statusArr.forEach(function(status, index) {
        if (status == "0") {
            unassigned_count++;
        } else if (status == "1") {
            login_count++;
        } else if (status == "2") {
            backupLogin_count++;
        }
    })
    document.getElementById("state_unassigned").innerHTML += "(" + unassigned_count + ")";
    document.getElementById("state_login").innerHTML += "(" + login_count + ")";
    document.getElementById("state_backupLogin").innerHTML += "(" + backupLogin_count + ")";
}

function setAutoReload(value, change) {
    if (change) {
        window.clearTimeout(window.timeoutID);
    }
    if (value > 0) {
        window.timeoutID = window.setTimeout(function() { location.href = "main.php?examID="+examID+"&autoReload=" + value }, value * 1000);
        document.getElementById("autoReloader").value = value;
        console.log(timeoutID);
    } else if (value == 0) {
        location.href = "main.php";
    }
}