var products;
var customers;
var employees;
var purid;

function getData(type)
{
    var request = new XMLHttpRequest();
    request.open("GET", "php/purchaseData.php?type="+type, false);
    request.send(null);
    return request.responseText;
}

function initial()
{
    products = JSON.parse(getData("products"));
    customers = JSON.parse(getData("customers"));
    employees = JSON.parse(getData("employees"));
    purid = Number(getData("purid"));
    //console.log(purid);
}

function toHTML(products)
{
    result = "";
    for(var i=0; i<products.length; i++)
    {
        result += '<div class="product" id="'+products[i].pid+'" name="'+products[i].pname+'" threshold="'+products[i].qoh_threshold+'" stock="'+products[i].qoh+'" onclick="showStock(this);">';
        result += '<div class="productImg"> <img src="image/'+products[i].pname+'.jpg" alt="'+ products[i].pname +'" width="100%" height="100%"></div>';
        result += '<div class="productName">'+ products[i].pname +'</div><hr><div class="price">';
        
        oprice = Number(products[i].original_price);
        price = (1-Number(products[i].discnt_rate)) * oprice; 

        result += '<span class="productPrice">￥ ' + price.toFixed(2) + "</span>";
        result += '<s class="originalPrice">￥ ' + oprice.toFixed(2) + '</s></div></div>\n\n';
    }
    //console.log(result);
    return result;
}

function showImage()
{
    initial();
    document.getElementById('goods').innerHTML = toHTML(products);
}

function getCustomer()
{
    var person = prompt("Please input your cid.","").toLocaleLowerCase();
    if(person==null)
        return "error";

    for(i=0; i<customers.length; i++)
        if(person == customers[i].cid.toLocaleLowerCase())
            return customers[i].cid;
    alert("Error cid. Please check your cid.");
    return "error";
}

function getEmployee()
{
    var person=prompt("Please input the employee's id.","").toLocaleLowerCase();
    if(person==null)
        return "error";

    for(i=0; i<employees.length; i++)
        if(person == employees[i].eid.toLocaleLowerCase())
            return employees[i].eid;
    alert("Error eid. Please check the employee's id.");
    return "error";
}

function setPurid()
{
    purid++;
    var tmp=purid/1000;
    tmp = tmp.toFixed(3);
    tmp = "p"+tmp.substr(tmp.indexOf('.')+1);
    //console.log(tmp);
    return tmp;
}

function check_qoh(name, count, stock, qoh_threshold)
{
    count = Number(count);
    stock = Number(stock);
    qoh_threshold = Number(qoh_threshold);

    if(stock-count < qoh_threshold)
    {
        alert('The quantity of ' + name + " is " + (stock-count) + ".");
        alert('The quantity of ' + name + ' has been increased by ' + (stock+count) + '.')
    }
}

function purchase(cid, eid, pid, count, stock, qoh_threshold, name)
{
    var purchaseID = setPurid();
    var param="purid="+purchaseID;
    param += "&cid="+cid;
    param += "&eid=" + eid;
    param += "&pid=" + pid;
    param += "&count=" + count;

    var request = new XMLHttpRequest();
    request.open("POST", "php/purchase.php", false);
    request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    request.onreadystatechange = function()
    {
        if(request.readyState==4)
        {
            //console.log(request.responseText);
            var check = JSON.parse(request.responseText);
            if(check)
            {
                alert("Purchase sucessfully.");
                check_qoh(name, count, stock, qoh_threshold);
            }
            else {
                alert('Purchase error.');
                $purid--;
            }
        }
    }
    request.send(param);
}

function getCount(stock)
{
    var count = prompt("We still have "+stock+" in the repertory. \nPlease input the quantity you want to buy.","");
    if(count==null)
        return "error";
    count = Number(count);

    if(!isFinite(count) || count<=0)
    {
        alert("Please input in right format.");
        return "error";
    }
    else if(count > stock)
    {
        alert("We only hava "+stock+". Please input a smaller number.");
        return "error";
    }
    return count;
}

function showStock(element)
{
    stock = element.getAttribute('stock');
    qoh_threshold = element.getAttribute('threshold');
    name = element.getAttribute('name').toLocaleLowerCase();

    message = "There still " + stock + " " + name + "s in the repertory!\n";
    message += "Do you want to buy some?"
	var r = confirm(message);
    if (r)
    {
        var cid = getCustomer();
        if(cid=="error")
            return;

        var eid = getEmployee();
        if(eid=="error")
            return;
        
        var count = getCount(stock);
        if(count=="error")
            return;

        //console.log(element.id);
        purchase(cid, eid, element.id, count, stock, qoh_threshold, name);
	}
}