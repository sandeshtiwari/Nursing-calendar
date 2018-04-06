<html>
<body>
    
   <ul>
 
  <a class="navbar-brand" rel="home" href="#" title="University of Louisiana at Monroe">      
  
  <h4 class= "usrname" align = "center" style = "color: #ffffff"> <?php 
  echo "Welcome ". $_SESSION['username']." ";
  ?>
  </h4>
   
    </a>
 
 


  <li><a href = "http://www.ulm.edu/">News</a></li>
  <li><a href = "https://moodle.ulm.edu/" title="Moodle">Moodle@ULM</a></li>
  
    
  
  <li style = "float:right"><a href = "logout.php">Logout</a></li>
  <li style = "float:right"><a href = "calendar.php">  Calendar  </a></li>
  <li style = "float:right"></li>
  
  


  
</ul>
</div>

</body>
</html>

<style>

html{

  margin: 0;
  padding :0;
}

 body {

  margin: 0;
  padding: 0;
  
  text-align: center;

  font-size: 14px;

  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;

  background-color: #F5F5F5;
  }

  

  .usrname { 
    display: block;
    font-size: 1em;
    margin-top: 1.0em;
    margin-bottom: 1.0em;
    text-align: center;
    font-weight: bold;
  }

  ul {

    list-style-type: none;
    padding: 0;
    overflow: hidden;
    background-color: #6f0029;
  }

  li {
    float: left;
    margin-top: 1.0em;
    margin-bottom: 1.0em;
    border-right:1px solid #bbb;
  }

li:last-child {
   margin-top: 1.0em;
    margin-bottom: 1.0em;
    border-left: 1px solid #bbb;
  }

li a {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
  }

li a:hover:not(.active) {
    background-color: #EBAF0F;
  }

.active {
    background-color: #870505;
    
  } 

 

</style>


