// let global_price_value = Math.round( 12 * ((5 * parseFloat(x)) + (10 * getOrderServiceChoiceValue())) );
// order_price_label.innerText = global_price_value;
// order_price_input.value = global_price_value;
function putPriceValue()
{
    let order_price_label = document.getElementById('order_price_label');
    let order_price_input = document.getElementById('order_price');
    let price_value = 30;
    order_price_label.innerText = ` ₱${price_value}`;
    order_price_input.value = price_value;

    let order_duration_label = document.getElementById('order_duration_label');
    let order_duration_input = document.getElementById('order_duration');
    let duration_value = 1;
    order_duration_label.innerText = ` ${duration_value}`;
    order_duration_input.value = duration_value;
}




function getOrderServiceChoiceValue()
{
    let order_service_choice = document.getElementById('services');
    let x = order_service_choice.value;
    // alert('haha');
    console.log(x);
    return parseFloat(x);
    // console.log(order_service_choice.val());
    // console.log(order_service_choice.options[order_service_choice.selectedIndex].text);
    // console.log(order_service_choice.target.options[order_service_choice.selectedIndex].text);
    // var value = e.value;
    // var text = e.options[e.selectedIndex].text;
}



let order_weight = document.getElementById('weight');
order_weight.addEventListener('input', getOrderWeightValue);


function getOrderWeightValue()
{
    let service_type_constant = 15;
    let weight_constant = 2;
    let duration_constant = 0.02;
    let order_weight_value = order_weight.value;
    console.log(order_weight_value);
    // global_order_weight = order_weight.value;
    let order_price_label = document.getElementById('order_price_label');
    let order_price_input = document.getElementById('order_price');

    let order_duration_label = document.getElementById('order_duration_label');
    let order_duration_input = document.getElementById('order_duration');

    let price_value = Math.round( ((weight_constant * parseFloat(order_weight_value)) * (service_type_constant * getOrderServiceChoiceValue())) );
    order_price_label.innerText = ` ₱${price_value}`;
    order_price_input.value = price_value;

    let duration_value = Math.round( ((duration_constant * parseFloat(order_weight_value)) * (service_type_constant * getOrderServiceChoiceValue())) );
    let digits = '';
    if(duration_value <= 1)
    {
        duration_value = 1;
    }
    if(duration_value >= 10)
    {
        digits = '';
    }else {
        digits = '  ';
    }
    order_duration_label.innerText = `  ${duration_value}`;
    order_duration_input.value = duration_value;
}







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
        xhr[i].send(`remainingtime${my_orders[i].id}=goo`);
    }
}, 500);




















let p = -1;
let xhr_notification = [];
let my_notifications = document.getElementsByClassName('link_button');
if(p < 0)
{
    console.log('LABAY');
    
    
    console.log(my_notifications);
    for(let i=0; i<my_orders.length; i++)
    {
        // console.log(my_orders[i].id);
        xhr_notification.push(new XMLHttpRequest());
    }
    // console.log(xhr_notification);
    for(let i=0; i<my_notifications.length; i++)
    {
        xhr_notification[i].onload = function()
        {
            let order_notification = document.getElementById(my_notifications[i].id);
            let notif_response_data = this.responseText;
            console.log(notif_response_data);
            
            if(notif_response_data == `NAA_${my_notifications[i].id}`)
            {
                console.log(`NAA_${my_notifications[i].id}`);
                // order_notification.value = "P";
                order_notification.style.backgroundColor = '#f00';
                // order_notification.style.backgroundColor = '#e1ecf4';
            }
            else if(notif_response_data == `WALA_${my_notifications[i].id}`)
            {
                // order_notification.value = "H";
                order_notification.style.backgroundColor = '#e1ecf4';
                // order_notification.style.backgroundColor = '#f00';
                // order_notification.style.border = '1px solid #7aa7c7';
            }
        };
    }
    p = 1;
}


setInterval(function() {
    for(let i=0; i<my_notifications.length; i++)
    {
        xhr_notification[i].open('POST', 'admin_notification.php');
        xhr_notification[i].setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr_notification[i].send(`${my_notifications[i].id}=goo`);
    }
}, 500);

