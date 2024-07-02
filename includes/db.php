<?php
function db_connect()
{
    return pg_connect("host=" . DB_HOST . " dbname=" . DATABASE . " user=" . DB_ADMIN . " port=" . DB_PORT . " password=" . DB_PASSWORD);
}


$conn = db_connect();
$statement1 = pg_prepare($conn, 'user_retrieve', 'SELECT * FROM users WHERE emailaddress = $1');

function user_select($email_address){
    global $conn;
    $user= false;
    $result  = pg_execute($conn,'user_retrieve',array($email_address));
    if(pg_num_rows($result)==1){
        $user= pg_fetch_assoc($result,0);
    }
    return $user;
}

$insert_user = pg_prepare($conn, 'insert_user', 'INSERT INTO users(EmailAddress, Password, FirstName, LastName, CreatedTime, phoneExtension, UserType) VALUES($1,$2,$3,$4,$5,$6,$7)');

$insert_client = pg_prepare($conn, 'insert_client', 'INSERT INTO clients(EmailAddress, FirstName, LastName, PhoneNumber, LogoPath, Sales_id , Extension) VALUES($1, $2, $3, $4, $5, $6, $7)');



function insert_salesperson($email_address, $password1, $fName, $lName, $phone, $usertype) {
    global $conn;
    $now = date("Y-m-d G:i:s");
    return pg_execute($conn, 'insert_user', array($email_address, password_hash($password1, PASSWORD_BCRYPT), $fName, $lName, $now, $phone, $usertype));
}

function insert_client($email_address, $fName, $lName, $phone, $extension, $logo_destination, $sales_id)
{
    global $conn;
    return pg_execute($conn, 'insert_client', array($fName, $lName,$email_address,  $phone, $extension, $sales_id, $logo_destination,) );
}
 //lab3
 $salesperson_select_all = pg_prepare($conn,"salesperson_select_all","SELECT Id,EmailAddress, FirstName,LastName,phoneExtension, UserType FROM users WHERE UserType = 's'");
        
 function salesperson_select_all($page){
    global $conn;
    
    $result = pg_execute($conn, "salesperson_select_all", array());

    $count = pg_num_rows($result);
    $arr =array();
    for($i = ($page-1)*RECORDS; $i <$count && $i<$page*RECORDS; $i++){
        array_push($arr,pg_fetch_assoc($result,$i));
    }
    return $arr;
          
}


function  salesperson_count(){
    global $conn;
    $result =pg_execute($conn,"salesperson_select_all", array());
    
    return pg_num_rows($result);
}
salesperson_select_all(1);
salesperson_count();


$clients_select_all = pg_prepare($conn, "clients_select_all", "SELECT clients.ID, clients.EmailAddress, clients.FirstName, clients.LastName, clients.PhoneNumber, clients.Extension, clients.LogoPath, users.EmailAddress as SalesEmail
FROM clients
LEFT JOIN users ON clients.Sales_id = users.ID
WHERE users.UserType = 'a'");

function clients_select_all($page)
{
    global $conn;
    $result = pg_execute($conn, "clients_select_all", array());

    $count = pg_num_rows($result);

    $arr = array();

    if ($page < 1 || ($page - 1) * RECORDS >= $count) {
        return $arr;
    }

    for ($i = ($page - 1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++) {
        array_push($arr, pg_fetch_assoc($result, $i));
    }

    return $arr;
}

function clients_count()
{
    global $conn;

    $result = pg_execute($conn, "clients_select_all", array());

    return pg_num_rows($result);
}

clients_select_all(1); 
clients_count();

$client_select_by_salesperson = pg_prepare($conn, 'client_select_by_salesperson', 'SELECT clients.id, clients.EmailAddress, clients.FirstName, clients.LastName, clients.PhoneNumber, clients.Extension FROM clients INNER JOIN users ON clients.Sales_id = users.id WHERE users.EmailAddress = $1');


function clients_select_by_salesperson($page, $id)
{
    echo "Debug: ID = $id";

    global $conn;
    $result = pg_execute($conn, 'clients_select_by_salesperson', array($_SESSION['user']['email']));

    $count = pg_num_rows($result);


    $arr = array();
    if ($page < 1 || ($page - 1) * RECORDS >= $count) {
        return $arr; 
    }

    for ($i = ($page - 1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++) {
        array_push($arr, pg_fetch_assoc($result, $i));
    }

    return $arr;
}

$client_count_by_salesperson = pg_prepare($conn, 'client_count_by_salesperson', 'SELECT COUNT(clients.id) FROM clients INNER JOIN users ON clients.Sales_id = users.id WHERE users.EmailAddress = $1');

function clients_count_by_salesperson($id)
{
    global $conn;

    $result = pg_execute($conn, "clients_select_all_salesperson", array($id));

    return pg_num_rows($result);
}
?>
