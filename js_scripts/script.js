// let global_price_value = Math.round( 12 * ((5 * parseFloat(x)) + (10 * getOrderServiceChoiceValue())) );
// order_price_label.innerText = global_price_value;
// order_price_input.value = global_price_value;


function putPriceValue()
{
    let order_price_label = document.getElementById('order_price_label');
    let order_price_input = document.getElementById('order_price');
    let price_value = 70;
    order_price_label.innerText = price_value;
    order_price_input.value = price_value;

    let order_duration_label = document.getElementById('order_duration_label');
    let order_duration_input = document.getElementById('order_duration');
    let duration_value = 1;
    order_duration_label.innerText = duration_value;
    order_duration_input.value = duration_value;
}



function getOrderServiceChoiceValue()
{
    let order_service_choice = document.getElementById('services');
    console.log(order_service_choice.value);
}

let order_service_choice = document.getElementById('services');
// order_service_choice.addEventListener('onchange', getOrderServiceChoiceValue);


let order_weight = document.getElementById('weight');
order_weight.addEventListener('input', getOrderWeightValue);


function getOrderWeightValue()
{
    let service_type_constant = 35;
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
    order_price_label.innerText = price_value;
    order_price_input.value = price_value;

    let duration_value = Math.round( ((duration_constant * parseFloat(order_weight_value)) * (service_type_constant * getOrderServiceChoiceValue())) );
    order_duration_label.innerText = duration_value;
    order_duration_input.value = duration_value;
}


function getOrderServiceChoiceValue()
{
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
        xhr[i].open('POST', 'update_duration.php');
        xhr[i].setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr[i].send(`remaining_time${my_orders[i].id}=goo`);
    }
}, 500);



// setInterval(function() {
//     location.reload();
//     console.log('erer reload');
// }, 1900);