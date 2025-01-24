<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="box.css">
</head>
<body>
    <div class="receipt">
        <h1 style="color: gold;">RECEIPT</h1>
        <div class="body">

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = trim($_POST['name']);
                $id = trim($_POST['id']);
                $email = trim($_POST['email']);
                $book_title = trim($_POST['book_title']);
                $borrow_date = trim($_POST['borrow_date']);
                $token = trim($_POST['token']);
                $return_date = trim($_POST['return_date']);
                $fees = trim($_POST['fees']);
                $paid = trim($_POST['paid']);
                $error = false;

                if (empty($name) || empty($id) || empty($book_title) || 
                    empty($borrow_date) /*|| empty($token)*/ || empty($return_date) || 
                    empty($fees) || empty($paid)) {
                        
                        echo "<p>Missing Field </p>";
                        $error = true;
                }

                // Validations
                if (!preg_match("/^[a-zA-Z.\s]+$/", $name)) {
                    echo "<p>Name cannot contain digits </p>";
                    $error = true;
                }
                if (!preg_match("/^(18|19|20|21|22|23|24)-\d{5}-(1|2|3)$/", $id)) {
                    echo "<p>Must be a valid ID </p>";
                    $error = true;
                }
                if (!preg_match("/^(18|19|20|21|22|23|24)-\d{5}-(1|2|3)+@+(student)+\.(aiub)+\.(edu)$/", $email)){
                    echo "<p>Must be valid Email </p>"; 
                }
                if (!preg_match("/\d+/", $fees)) {
                    echo "<p>Fees must be in digits </p>";
                    $error = true;
                }

                $borrow_time = strtotime($borrow_date);
                $return_time = strtotime($return_date);
                $date_diff = ($return_time-$borrow_time) / (60*60*24); //Calculate days

                if ($return_time < $borrow_time) {
                    echo "<p>Return date cannot be before borrow date.</p>";
                    return;
                }

                if ($date_diff > 10) {
                    $data = json_decode(file_get_contents('token.json'));   // Check if token inserted is valid
                    $validToken = false;
                    foreach($data[0]->token as $key => $value){
                        if($token == $value){
                            $validToken = true;
                            unset($data[0]->token[$key]); // Remove the used token
                            break;
                        }
                    }
                    if ($validToken) {
                        $usedData = json_decode(file_get_contents('usedToken.json'));   // Write valid tokens to usedToken.json
                        $usedData[0]->token[] = $token;
                        file_put_contents('usedToken.json', json_encode($usedData));
                        file_put_contents('token.json', json_encode($data)); // Update token.json
                    } else {
                        echo "<p>Invalid token.</p>";
                        $error = true;
                    }
                } else {
                    echo "<p> Days of lease = $date_diff </p>";
                    echo "<p> To borrow for more than 10 days a token is required </p>";
                    echo "<p><br><br> Do you have a token? </p>";
                    $error = true;
                    return;
                }


                
                $cookieName = preg_replace('/\s+/', '', $book_title); //remove space
                $cookieValue= preg_replace('/\s+/', '', $name); // remove space

                // Check if the cookie exists and matches the input data
                if (isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] == $cookieValue) {
                    echo "<p>Someone else leased this book (;__;). <br>Please try again after 10 days.</p>";
                    echo "<p><br><br>Redirecting...</p>";
                    header("refresh:5;url=lab2.html");
                    $error = true;
                } else {
                    // Set a new cookie with the name and book_title, expiring in 10 seconds
                    setcookie($cookieName, $cookieValue, time() + 10);
                    header("refresh:5;url=process.php");
                }
                

                // Display
                if (!$error) {
                    echo "<p>Name           : $name</p>";
                    echo "<p>ID             : $id</p>";
                    echo "<p>Email          : $email</p>";
                    echo "<p>Book Title     : $book_title</p>";
                    echo "<p>Borrow Date    : $borrow_date</p>";
                    echo "<p>Token          : $token</p>";
                    echo "<p>Return Date    : $return_date</p>";
                    echo "<p>Fees           : $fees</p>";
                    echo "<p>Paid           : $paid</p>";
                }
            } else {
                echo "<p>No data submitted.</p>";
            }
            ?>

        </div>
    </div>
</body>
</html>