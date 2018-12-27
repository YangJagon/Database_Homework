function initial()
{
    var tableName = window.location.search.substr(1); 
    if(tableName == "" || tableName == null) tableName="employees";
    showTable(tableName);
    showForm(tableName);
}

function showTable(tableName)
{
    var request = new XMLHttpRequest();
    request.open("GET", "php/information.php?tablename="+tableName+"&type=table", true);
    request.onreadystatechange = function()
    {
        if(request.readyState==4)
        {
            document.getElementById("Table").innerHTML = request.responseText;
            //console.log(request.responseText);
        }
    }
    request.send(null);
}

function showForm(tableName)
{
    //document.getElementById('tablename').value = tableName;
    document.getElementById('submit').setAttribute("onclick", "insertTuple(\""+tableName+"\"); return false;")
    var request = new XMLHttpRequest();
    request.open("GET", "php/information.php?tablename="+tableName+"&type=form", true);
    request.onreadystatechange = function()
    {
        if(request.readyState==4)
        {
            document.getElementById("Form").innerHTML = request.responseText;
            //console.log(request.responseText);
        }
    }
    request.send(null);
}

function deleteTuple(tableName, fname, id)
{
    var request = new XMLHttpRequest();
    request.open("GET", "php/delete.php?tablename="+tableName+"&"+fname+"="+id, true);
    request.onreadystatechange = function()
    {
        if(request.readyState==4)
        {
            var check = JSON.parse(request.responseText);
            if(check)
                showTable(tableName);
            else alert('Delete error!!! \nYou can not delete this tuple because it is a foreign key of other tables.');
        }
    }
    request.send(null);
}

function updateTable(tableName, fname, id)
{
    var request = new XMLHttpRequest();
    request.open("GET", "php/upTable.php?tablename="+tableName+"&"+fname+"="+id, true);
    request.onreadystatechange = function()
    {
        if(request.readyState==4)
        {
            document.getElementById("Table").innerHTML = request.responseText;
            //console.log(request.responseText);
        }
    }
    request.send(null);
}

function updateTuple(tableName, idn, idd)
{
    var param = "tablename="+tableName + "&idname=" + idn + "&iddata=" + idd;
    var data = document.getElementById(idd).nextSibling.childNodes;
    var fields = document.getElementById('fields').childNodes;

    for(i=0; i<data.length-1; i++)
    {
        param += "&" + fields[i].firstChild.textContent.toLowerCase();
        param += "=" + data[i].firstChild.value;
    }

    var request = new XMLHttpRequest();
    request.open("post", "php/update.php", true);
    request.onreadystatechange = function()
    {
        if(request.readyState==4)
        {
            console.log(request.responseText);
            var check = JSON.parse(request.responseText);
            if(check)
                showTable(tableName);
            else alert('Update error!!! Please check your data again.');
        }
    }
    //console.log(param);
    request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    request.send(param);
}

function clear(data)
{
    for(i=0; i<data.length; i++)
        data[i].firstChild.value = "";
}

function insertTuple(tableName)
{
    var param = "tablename="+tableName;
    var data = document.getElementById("insertData").childNodes;
    var fields = document.getElementById('fields').childNodes;

    for(i=0; i<data.length; i++)
    {
        param += "&" + fields[i].firstChild.textContent.toLowerCase();
        param += "=" + data[i].firstChild.value;
    }

    var request = new XMLHttpRequest();
    request.open("post", "php/insert.php", true);
    request.onreadystatechange = function()
    {
        if(request.readyState==4)
        {
            //console.log(request.responseText);
            var check = JSON.parse(request.responseText);
            if(check)
            {
                showTable(tableName);
                clear(data);
            }
            else alert('Insert error!!! Please check your data again.');
        }
    }
    //console.log(param);
    request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    request.send(param);
}