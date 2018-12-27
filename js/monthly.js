function getTable(product)
{
    var map = new Array();
    map['milk'] = 'pr00';
    map['egg'] = 'pr01';
    map['bread'] = 'pr02';
    map['pineapple'] = 'pr03';
    map['knife'] = 'pr04';
    map['shovel'] = 'pr05';
    var pid = map[product.toLocaleLowerCase()];
    if(pid==null)
    {
        pid = "pr00";
        product = "milk";
    }

    var request = new XMLHttpRequest();
    request.open("GET", "php/monthly.php?pid="+pid+"&name="+product, true);
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