let xhr = [];
let my_orders = document.getElementsByClassName('remaining_time_class');
// console.log(my_orders);

for(let i=0; i<my_orders.length; i++)
{
    // console.log(my_orders[i].id);
    xhr.push(new XMLHttpRequest());
}
// console.log(xhr);
for(let i=0; i<my_orders.length; i++)
{
    xhr[i].onload = function()
    {
        let serverResponse = document.getElementById(my_orders[i].id);
        let response_data = this.responseText;
        serverResponse.innerHTML = response_data;
        if(response_data <= 0)
        {
            location.reload();
        }
    };
}



setInterval(function() {
    for(let i=0; i<my_orders.length; i++)
    {
        xhr[i].open('POST', 'admin_update_duration.php');
        xhr[i].setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr[i].send(`a_remaining_time${my_orders[i].id}=goo`);
    }
}, 500);