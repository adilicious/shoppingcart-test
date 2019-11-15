<?php
$post = file_get_contents('php://input');
$obj = json_decode($post);

// validate
function anti_xss_high($data){
    if (get_magic_quotes_gpc()){
        return htmlentities(strip_tags(stripslashes($data)));
    } else {
        return htmlentities(strip_tags($data));
    }
}

function letters_only($string){
    if (!preg_match("/^[a-zA-Z]*$/",$string)) {
        return false;
    }else {
        return true;
    }
}

// get vars
$params = new stdClass();
$params->fname = anti_xss_high($obj->customer->firstname);
$params->lname = anti_xss_high($obj->customer->lastname);
$params->email = anti_xss_high($obj->customer->emailaddress);
$params->order_list = json_encode($obj->order->list);

// declare response
$response = new stdClass();
if(!letters_only($params->fname) || !letters_only($params->lname)){
    $response->error = true;
    $response->message = "Firstname/Lastname letters only allowed.";
    print json_encode($response);
    return false;
}

if (!filter_var($params->email, FILTER_VALIDATE_EMAIL)) {
    // invalid emailaddress
    $response->error = true;
    $response->message = "Email Address is invalid.";
    print json_encode($response);
    return false;
}

// process
// ideally would like to save to a database, but will just create a text file for now
$order_num = uniqid(); 
$fp = fopen("./orderlist.csv", 'a');  
fputcsv($fp, array($order_num, time(), $params->fname, $params->lname, $params->email, $params->order_list)); 
fclose($fp);

$response->success = true;
$response->order_num = $order_num;
print json_encode($response);
