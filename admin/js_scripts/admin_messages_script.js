

let message_order_id = document.getElementById('get_order_id');
let message_user_id = document.getElementById('get_user_id');
let haha = message_order_id.textContent;
// console.log(haha);


let messages_div = document.getElementById('messages');
let individual_messages = messages_div.children;
// console.log(men);
// for(let i=0; i<men.length; i++)
// {
//     console.log(men[i].textContent);
// }
console.log('\n\n\n');

if(individual_messages.length > 0)
{
    let new_message_added_first = document.getElementById('messages').children;
    new_message_added_first[new_message_added_first.length - 1].scrollIntoView();
}


let previos_data = null;

const feedback_xhr = new XMLHttpRequest();
feedback_xhr.onload = function()
{
    let response_data = this.responseText;
    let recent_message = '';
    if(individual_messages.length > 0)
    {
        recent_message = individual_messages[individual_messages.length - 1].textContent;
    }
    console.log(response_data);
    
    if( (response_data != recent_message) && (response_data != '') && (response_data != previos_data) )
    {
        // location.reload();
        let new_message = document.createElement('div');
        new_message.classList.add('user_type_0');
        new_message.textContent = response_data;
        // new_message.style = 'text-align: left;background-color: #F6F;color: #fff; padding: 2px;';
        messages_div.appendChild(new_message);
        previos_data = response_data;
        let new_message_added = document.getElementById('messages').children;
        new_message_added[new_message_added.length - 1].scrollIntoView();
    }
    // previos_data = response_data;
};

setInterval(function() {
    feedback_xhr.open('POST', 'admin_update_messages.php');
    feedback_xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    feedback_xhr.send(`feedback_message_update=${message_order_id.textContent}-${message_user_id.textContent}`);
}, 200);
