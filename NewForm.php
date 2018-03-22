<?php
##########################error report#########################

    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    ini_set('display_erros', 1);

#########################includes PHP all files#########################


    include ("account.php") ;
    include ("functions.php") ;

#########################connect to the database#########################

    $db = mysqli_connect ( $hostname, $username, $password, $project );
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: ". mysqli_connect_error();
        exit();
    }


    print "Hello";
    print "<br> <br> Successfully connected to MySQL.<br>";
    mysqli_select_db($db, $project);
    
    $bad = false;

#########################Get data from the user#########################

    $User = $_GET["User"]; 
    $Pass = $_GET["Pass"];
    $amount = $_GET["Amount"];
    $option = $_GET["option"];

    print "<br> <br>User is: $User";
    print "<br>Pass is: $Pass <br> <br>"; 

    if($bad){
        exit("bad Credentials input!");
    }

////////if auth fails program fails//////
    if(!auth($User, $Pass)){
        exit("Invalid Credentials inserted! <br> <br>");
    }

#########################deposit amount#########################
    if($option == 'deposit'){
        deposit($User, $amount);
    }

######################### withdraw amount#########################
    if($option == 'withdraw'){
        withdraw($User, $amount);
    }

#########################Show statement#########################
    if($option == 'show'){
        show($User, $out);
    }

#########################mail is not checked#########################
    if(!isset($_GET["mail"])){
        die ("<br><br> Mail copy was not requested!.");

#########################Statement is not selected, no mail the copy#########################
    
    } else if($option != 'show') {
        die ("<br><br> Mail copy was not requested!.");

#########################Statement is selected, so mail the copy#########################
    } else{
        adminmailer($User, $out);
        usermailer($User, $out);
        die ("<br><br> Mail copy was requested!.");
    }

    print "<br>Bye";
    print "<br>Interaction is completed. " ;
?>