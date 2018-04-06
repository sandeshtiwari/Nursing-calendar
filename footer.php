<html>
<body>
  <div id = "wrapper">
  	<div>
  	</div>
  </div>


<div id ="footer">
  <p>&copy;  © TeamGamma<?php echo @date("Y"); ?> </p>

  <a class = "backbtn" href = "javascript:history.go(-1)"onMouseOver"self.status.referrer;return true">
    <span>   Back   </span>
  </a>
    
  <br><br><br>  
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


   #footer{
    height: 0px;
    width: 100%;
    background-color: #003399:
    position: absolute;
    left: 0px;
    bottom: 0px; 
  }

  #footer p{
    text-align: center;
    color: #6f0029;
    font-size: 15px;
    font-weight: bold;
    padding: 13px 0px 0 0;
  }

  

  .backbtn{
    letter-spacing: 2px;
    text-align: center;
    color: #6f0029;

    font-size: 16px;
    font-family: "Nunito", sans-serif;
    font-weight: 300;
    
    margin: 15em auto;
    
     
    
    
    padding: 8px 8px;
    width: 220px;
    height:30px;
    border-radius: 10px;
    background: #6f0029;
    border: 1px solid #6f0029;
    color: #FFF;
    overflow: hidden;
    
    transition: all 0.5s;
  } 

  .backbtn:hover, .backbtn:active 
  {
    text-decoration: none;
    color: #6f0029;
    border-color: #6f0029;
    background: #FFF;
  }

  .backbtn span 
  {
    display: inline-block;
    position: relative;
    padding-right: 0;
    
    transition: padding-right 0.5s;
  }

  .backbtn span:after 
  {
    content: ' ';  
    position: absolute;
    top: 0;
    right: -18px;
    opacity: 0;
    width: 10px;
    height: 10px;
    margin-top: -10px;

    background: rgba(0, 0, 0, 0);
    border: 3px solid #FFF;
    border-top: none;
    border-right: none;

    transition: opacity 0.5s, top 0.5s, right 0.5s;
    transform: rotate(-45deg);
  }

  .backbtn:hover span, .backbtn:active span 
  {
    padding-right: 30px;
  }

  .backbtn:hover span:after, .backbtn:active span:after 
  {
    transition: opacity 0.5s, top 0.5s, right 0.5s;
    opacity: 1;
    border-color: #6f0029;
    right: 0;
    top: 50%;
  }



</style>