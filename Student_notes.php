<?php
require 'classes/Teacher.php';
require 'classes/Student.php';
require 'classes/Admin.php';
require 'check_privilege.php';
require 'head.php';


echo "<br><br><br>";

      if($_SESSION['privilege'] == 'teacher')
  {

      $idnum = " ";
       $userpa = $_SESSION['username'];
      
    $sql2 = "SELECT CWID FROM person WHERE email LIKE '%$userpa%'";

    $result = mysqli_query($con, $sql2);

    if (mysqli_num_rows($result) > 0) {
    // output data of each row
  
    while($row = mysqli_fetch_assoc($result)) {
        $idnum = $row["CWID"];
        
    }
    } else {
    echo "No Notes";
    }

$sql1 = "SELECT * FROM course WHERE Teacher_CWID= $idnum";

$result = mysqli_query($con, $sql1);

if (mysqli_num_rows($result) >= 0) {
    // output data of each row
  
    
       while($row = mysqli_fetch_assoc($result)) {
        echo "<br><br>Class: ". $row["Course_ID"]." " . $row["Prefix"]. " " . $row["Number"]. "<br> Current Note To Class: <br> " . $row["Notes"]. "<br>" ;
        $boom=$row["Course_ID"];
        echo "<form action='push_Notes.php' method='post'>
            <input type='text' name='Notes'></input>
            <input type='hidden' name='LastName' value='$boom'></input>
            <input type='submit' name='submit' value='Update Note'></input>
            </form>";
    }
    
} else {
    echo "No Notes <br/>";
}

}
elseif($_SESSION['privilege'] == 'admin'){



}
else{

 $idnum = " ";
       $userpa = $_SESSION['username'];
      
    $sql2 = "SELECT CWID FROM person WHERE email LIKE '%$userpa%'";

    $result = mysqli_query($con, $sql2);

    if (mysqli_num_rows($result) > 0) {
    // output data of each row
  
    while($row = mysqli_fetch_assoc($result)) {
        $idnum = $row["CWID"];
        
    }
    } else {
    echo "No Notes";
    }
$sql3 = "SELECT Course_ID FROM registered WHERE CWID = $idnum";


$result1 = mysqli_query($con, $sql3);

if (mysqli_num_rows($result1) >= 0) {
    // output data of each row
      while($row1 = mysqli_fetch_assoc($result1)) {
        

          $cwidstudent = $row1['Course_ID'];
          $sql4 = "SELECT * FROM course WHERE Course_ID = $cwidstudent";

          $result2 = mysqli_query($con, $sql4);

        if (mysqli_num_rows($result2) >= 0) {
            // output data of each row
           while($row2 = mysqli_fetch_assoc($result2)) {
            echo "<table class = 'tbl1'>";
            echo "<tr><th>". $row2["Prefix"]." " . $row2["Number"]. " " . $row2["Title"]. "<br> </th></tr>";
            echo "<tr><td> Teacher's Notes: " . $row2["Notes"]. "<br> </td></tr>";
            echo "</table>";
          }
        }
      }   
    
} else {
    echo "No Notes <br/>";
}



}
echo "<br><br><br>";

require 'footer.php';
?>

<style>
  .tbl1{
  width: 65%;
  border: 2px solid #6f0029;
    margin: auto;
    text-align: left;
    float: left;
}

.tbl1 td{
  line-height: 1.5;
    display: inline-block;
    vertical-align: middle;
}

</style>