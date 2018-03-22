<?php
    ######################### Getdata function#########################
    function getdata($name, &$result){
        global $db, $bad;

        if(!isset ($_GET[$name])){
            echo "$name is Invalid input!";
            $bad = true;
            return;
        }

        $temp = $_GET[$name];

        if($_GET[$name] == ''){
            echo "$name is empty!";
            $bad = true;
            return;

        }

        $temp = mysqli_real_escape_string($db, $temp);
        $return = $temp;
    }

    #########################authorization function#########################

    function auth($User, $Pass){
        global $db;

        $pwd = sha1($Pass);

        $s = "select * from Accounts where User = '$User' and Pass = '$pwd'";
        echo "Authentication SQL is: $s<br>";

        $t = mysqli_query($db, $s);
        $num = mysqli_num_rows($t);

        if($num == 0) {
            echo "<br> <br> incorrect User <br> <br>";
            return false;
        }
        else{
            echo "<br> <br> correct User and Password<br> <br>";
            return true;
        }
    }

    #########################Deposit function#########################

    function deposit($User, $amount){
        global $db;

        $d = "UPDATE Accounts SET Current_balance = Current_balance + '$amount' WHERE User = '$User'";
        echo"<br> New Balance is $d <br>";
        mysqli_query($db,$d) or die( mysqli_error($db) );

        $insert = "insert into Transactions values('$User', 'D', '$amount' , NOW())";

        echo "<br> The new row is $insert <br>";
        mysqli_query ($db, $insert) or die( mysqli_error($db));

    }

########################## Withdraw Function########################## 

    function withdraw($User, $amount){
        global $db;

        $s = "select * from Accounts where User = '$User'";
        ($t = mysqli_query($db, $s)) or die(mysqli_error($db));

        while($r= mysqli_fetch_array($t, MYSQLI_ASSOC)){
            $Current_balance= $r["Current_balance"];
            echo("<br>Current balance is $$Current_balance. <br>");
        }

        if ($amount > $Current_balance){
            echo("<br>You are overdrafting you account!!<br>");
        }
        else{
            $w = "UPDATE Accounts SET Current_balance = Current_balance - '$amount' WHERE User = '$User'";
            echo"<br> New Balance is $w <br>";
            mysqli_query($db,$w) or die( mysqli_error($db) );

            $insert = "insert into Transactions values('$User', 'W', '$amount' , NOW())";

            echo "<br> The new row is $insert <br>";
            mysqli_query ($db, $insert) or die( mysqli_error($db));

        }
    }


#########################show function#########################
    function show($User, &$out){

        global $db;

        $a = "select * from Accounts where User = '$User'";
        $out .= "<br> SQL statement is: $a<br>";

        ($t = mysqli_query($db, $a )) or die(mysqli_error($db));

        while($r = mysqli_fetch_array($t, MYSQLI_ASSOC)){
            $User = $r["User"];
            $Current_balance = $r["Current_balance"];

            $out .="<br> <br> User is $User<br><br>";
            $out .= "<br>Current_Balance is $$Current_balance <br>";
        }


        $a = "select * from Transactions where User = '$User' ORDER BY Date DESC";
        $out .= "<br> SQL statement is: $a<br> <br>";

        ($t = mysqli_query($db, $a )) or die(mysqli_error($db));
        while($r = mysqli_fetch_array($t, MYSQLI_ASSOC)){

            $amount = $r["Amount"];
            $out .= "Amount is $$amount<br>";

            $type = $r["Type"];
            $out .= "Type of transaction is $type<br>";

            $date = $r["Date"];
            $out .= "Date & time for transaction: $date<br><br>";
        }

        echo $out;
    }


########################## Mail to admin Function#########################

    function adminmailer($User, $out){
        $to = "kmp59@mailinator.com";
        $subject = "Test case for $User ".date("F")." ".date("d").", ".date("Y").", ".date("h").":".date("s")." ".date("a");
        $message = $out;

        mail ($to, $subject, $message);
    }

########################## get User mail########################## 

    function get_email($User){
        global $db;

        $e = "SELECT Email FROM Accounts WHERE User = '$User'";
            ($fetch_email_query = mysqli_query($db, $e) or die(mysqli_error($db)));
            $row = mysqli_fetch_array($fetch_email_query);
            $email = $row["Email"];
            return $email;
    }

########################## Mail to User########################## 

    function usermailer($User, $out){
        $to = get_email($User);
        $subject = "Test case for $User ".date("F")." ".date("d").", ".date("Y").", ".date("h").":".date("s")." ".date("a");
        $message = $out;

        mail ($to, $subject, $message);
    }
?>