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
    let x = order_weight.value;
    console.log(x);
    // global_order_weight = order_weight.value;
    let order_price_label = document.getElementById('order_price_label');
    let order_price_input = document.getElementById('order_price');

    let price_value = Math.round( ((weight_constant * parseFloat(x)) * (service_type_constant * getOrderServiceChoiceValue())) );
    order_price_label.innerText = price_value;
    order_price_input.value = price_value;
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