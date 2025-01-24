<?php
    require "dbconnect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="box.css">
    <title>Librarian</title>
</head>
<body>
    <div class="receipt">
        <h1>Receipt</h1>
        <div class="body">
            <?php
            $book_title2 = trim($_POST['book_title2']);
            $author = trim($_POST['author']);
            $isbn = trim($_POST['isbn']);
            $num_book = trim($_POST['num_book']);
            $category = $_POST['category'];

            $error = false;

            if (empty($book_title2) || empty($author) || empty($isbn) || empty($num_book)) {
                echo "<p>Missing Fields </p>";
                $error = true;
            }

            if (!preg_match("/[a-zA-Z\s]/", $author)) {
                echo "<p>Author Name cannot contain digits </p>";
                $error = true;
            }
            if (!preg_match("/[0-9]/", $isbn)) {
                echo "<p>ISBN No. must be in digits </p>";
                $error = true;
            }
            if (!preg_match("/[0-9]/", $num_book)) {
                echo "<p>No. of Books must be in digits </p>";
                $error = true;
            }
            if (isset($_POST['submit2'])){
                $checkQuery = "SELECT * FROM book WHERE isbn='$isbn'";
                $result = mysqli_query($conn, $checkQuery);

                if(mysqli_num_rows($result) > 0){
                    echo "<p>ISBN exists, cannot add</p>";
                    $error = true;
                }
            } 

            if(!$error) {
                echo "<p>Book Title: $book_title2 </p>";
                echo "<p>Author Name: $author </p>";
                echo "<p>ISBN No: $isbn </p>";
                echo "<p>Num. of Books: $num_book </p>";
                echo "<p>Category: $category </p>";
            }

            if(!$error){
                try{
                    if(isset($_POST['submit2'])){ {
                            $query = "INSERT INTO book(name, isbn, author, quantity, category) 
                                    VALUES ('$book_title2', '$isbn', '$author', '$num_book', '$category')";
    
                            mysqli_query($conn, $query);
                            header("refresh:3;url=lab2.php");
                        }
                    }
                } catch (mysqli_sql_exception $ex){
                    echo "Error ".$ex->getMessage();
                }
            }
            

            ?>
        </div>
    </div>
</body>
</html>