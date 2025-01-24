<?php
  require "dbconnect.php";
?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="box.css" />
</head>

<body>
  <br>
  <img src="image/id.jpeg" alt="Souhardo(22-49068-3)" width="200" height="50" style="float: right" />
  <br><br><br><br>
  <div class="border">
    <div class="box">
      <h2 style="color: gold; text-align: center; margin: 10px;">AVAILABLE BOOK INFORMATION</h2>
      <table>
        <tr>
          <th>Name</th>
          <th>ISBN</th>
          <th>Author</th>
          <th>Quantity</th>
          <th>Category</th>
        </tr>
        <?php
          $query = "SELECT * FROM book";
          $result = mysqli_query($conn, $query);
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['name'] . "</td>";
              echo "<td>" . $row['isbn'] . "</td>";
              echo "<td>" . $row['author'] . "</td>";
              echo "<td>" . $row['quantity'] . "</td>";
              echo "<td>" . $row['category'] . "</td>";
              echo "</tr>";
          }
        ?>
      </table>
    </div>
    <div class="box">
      <h2 style="color: gold; text-align: center; margin: 10px;">MODIFY BOOK INFORMATION</h2>
      <br><br>
      <form action="" method="POST">
        <label for="delete">Delete ISBN:</label>
        <input type="text" name="cisbn">
        <input type="submit" name="csubmit" value="Delete">
      </form>
      <?php
        if(isset($_POST['csubmit'])){
          $cisbn = trim($_POST['cisbn']);
          $deletequery = "DELETE FROM book WHERE isbn = '$cisbn'";
          if (mysqli_query($conn, $deletequery)) {
              echo "<p>Book with ISBN $cisbn deleted successfully.</p>";
              header("refresh:2;url=lab2.php");
          } else {
              echo "<p>Error deleting book: " . mysqli_error($conn) . "</p>";
          }
        }
      ?>

      <form action="" method="POST">
        <label for="update">Update ISBN:</label>
        <input type="text" name="uisbn"><br>
        <label for="title">Title:</label>
        <input type="text" name="utitle"><br>
        <label for="author">Author:</label>
        <input type="text" name="uauthor"><br>
        <label for="quantity">Quantity:</label>
        <input type="text" name="uquantity"><br>
        <label for="category">Category:</label>
        <input type="text" name="ucategory"><br>
        <input type="submit" name="usubmit" value="Update">
      </form>
      <?php
        if(isset($_POST['usubmit'])){
          $uisbn = trim($_POST['uisbn']);
          $utitle = trim($_POST['utitle']);
          $uauthor = trim($_POST['uauthor']);
          $uquantity = trim($_POST['uquantity']);
          $ucategory = trim($_POST['ucategory']);
          $updatequery = "UPDATE book SET name = '$utitle', author = '$uauthor', quantity = '$uquantity', category = '$ucategory' WHERE isbn = '$uisbn'";
          if (mysqli_query($conn, $updatequery)) {
              echo "<p>Book with ISBN $uisbn updated successfully.</p>";
              header("refresh:2;url=lab2.php");
          } else {
              echo "<p>Error updating book: " . mysqli_error($conn) . "</p>";
          }
        }
        ?>
    </div>
    <div class="box">
      <div class="boxleftside">
      <h2 style="color:gold; text-align: center; margin: 10px;">TOKENS</h2>
      <ul>
        <?php
          $data = json_decode(file_get_contents('token.json')); 
          foreach($data[0]->token as $token){
              echo "<li>" . $token . "</li>";
          }
        ?>
      </ul>
      </div>
      
      <div class="boxrightside">
      <h2 style="color:gold; text-align: center; margin: 10px;">USED TOKENS</h2>
      <ul>
        <?php
          $useddata = json_decode(file_get_contents('usedToken.json')); 
          foreach($useddata[0]->token as $token){
              echo "<li>" . $token . "</li>";
          }
        ?>
      </ul>
      </div>

    
    </div>

    <div class="b1">
      <img src="image/b1.jpg" width="100px" height="150px" alt="b1">
    </div>
    <div class="b2">
      <img src="image/b2.jpg" width="100px" height="150px" alt="b2">
    </div>
    <div class="b3">
      <img src="image/b3.jpg" width="100px" height="150px" alt="b3">
    </div>
    <div class="b4">
      <h2 style="color:gold; text-align: center; margin: 10px;">STUDENTS</h2>
      <br>      
      <form action="process.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" placeholder="Full Name"/>
        

        <label for="id">ID:</label>
        <input type="text" name="id" placeholder="AIUB ID"/>
        <br><br>

        <label for="email">Email:</label>
        <input type="text" name="email" placeholder="AIUB Mail"/>
        

        <label for="book_title">Book Title:</label>
        <select name="book_title">
          <?php
            $query = "SELECT name FROM book";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
          echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
            }
          ?>
        </select>
        <br><br>

        <label for="borrow_date">Borrow Date:</label>
        <input type="date" name="borrow_date" placeholder="dd-mm-yyyy"/>
        <br><br>

        <label for="return-date">Return Date:</label>
        <input type="date" name="return_date" placeholder="dd-mm-yyyy"/>
        <br><br>

        <label for="token">Token:</label>
        <input type="number" name="token" placeholder="Valid Code"/>
        <br><br>

        <label for="fees">Fees:</label>
        <input type="text" name="fees" placeholder="Amount"/>
        <br><br>

        <label for="paid">Paid:</label>
        <select name="paid">
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
        <br><br>

        <input type="submit" name="submit" value="Submit" />
      </form>
    </div>

    <div class="b5">
      <h2 style="color:gold; text-align: center; margin: 10px;">LIBRARIAN</h2>
      <br>
      <form action="submit.php" method="POST">
        <label for="book_title2">Book Title:</label>
        <input type="text" name="book_title2" placeholder="Name of Book">
        <br><br>
  
        <label for="Author">Author Name:</label>
        <input type="text" name="author" placeholder="Full Name">
        <br><br>
  
        <label for="ISBN_No">ISBN No:</label>
        <input type="text" name="isbn" placeholder="Valid Code">
        <br><br>
  
        <label for="Num_book">No. of Books:</label>
        <input type="text" name="num_book" placeholder="Total Amount">
        <br><br>

        <label for="category">Category:</label>
        <input type="text" name="category" placeholder="Insert Category">
  
        <!-- <label for="Category">Category:</label>
        <select name="category">
          <option value="Fiction">Fiction</option>
          <option value="Science">Science</option>
          <option value="Economy">Economy</option>
        </select> -->
        <br><br><br><br><br><br>
        <input type="submit" name="submit2" value="Submit" />
      </form>
    </div>
    
  </div>
</body>

</html>