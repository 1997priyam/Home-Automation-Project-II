<?php
// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
	header("location: login.php");
	
  exit;
}
else {
	require_once 'config.php';
	 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Control</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript">
	</script>
<script type="text/javascript">

  // Create a client instance
  client = new Paho.MQTT.Client("bitvolt.tk", 3033,"<?php echo $_SESSION['username']?>");
  
  // set callback handlers
  client.onConnectionLost = onConnectionLost;
  client.onMessageArrived = onMessageArrived;
  var options = {
    useSSL: false,
    userName: "<?php echo $_SESSION['username']?>",
    password: "<?php echo $_SESSION['password']?>",
    onSuccess:onConnect,
    onFailure:doFail
  }

  // connect the client
  client.connect(options);

  // called when the client connects
  function onConnect() {
    // Once a connection has been made, make a subscription and send a message.
    console.log("onConnect : Connected");
client.subscribe("/<?php echo $_SESSION['username']?>/temp");
client.subscribe("/<?php echo $_SESSION['username']?>/humidity");
    
  }

function publishOne()
{
message = new Paho.MQTT.Message("1");
    message.destinationName = "/<?php echo $_SESSION['username']?>/light";
    client.send(message);
  }
function publishOne2()
{
message = new Paho.MQTT.Message("1");
    message.destinationName = "/<?php echo $_SESSION['username']?>/light2";
    client.send(message);
  }
function publishZero()
{
message = new Paho.MQTT.Message("0");
    message.destinationName = "/<?php echo $_SESSION['username']?>/light";
    client.send(message);
  }
function publishZero2()
{
message = new Paho.MQTT.Message("0");
    message.destinationName = "/<?php echo $_SESSION['username']?>/light2";
    client.send(message);
  }
  function doFail(e){
    console.log(e);
  }

  // called when the client loses its connection
  function onConnectionLost(responseObject) {
    if (responseObject.errorCode !== 0) {
      console.log("onConnectionLost:"+responseObject.errorMessage);
    }
  }

  // called when a message arrives
  function onMessageArrived(message) {
    console.log("onMessageArrived:"+message.payloadString);
console.log("onMessageArrived:"+message.destinationName);
if(message.destinationName == "/<?php echo $_SESSION['username']?>/temp")
{
var temp = document.getElementById('temp');
temp.value= message.payloadString+" *C";
}
else
{
var humidity = document.getElementById('humidity');
humidity.value= message.payloadString+"%";
}
	
  }
</script>
    <style type="text/css">
        body{ font: 14px sans-serif;
		padding-top: 60px;
        padding-bottom: 40px;
		}
        .wrapper{ width: 350px; padding: 20px; padding-top: 120px; padding-bottom: 100px; }
    </style>
</head>
<?php include("header.html");?>
<body>
<div class="wrapper">
<div><h3>Welcome <?php echo $_SESSION['username'] ?> </h3></div>
<div>
<a href="logout.php" class="btn btn-primary" role="button">Logout</a>
  </div>
<div class="panel panel-default">
<div class="panel-body">
    <h2> Switch 1 </h2> <br>
	<div class="form-group">
	<button type="button" class="btn btn-success" onClick="publishOne()">ON</button>
	<button type="button" class="btn btn-danger" onClick="publishZero()">OFF</button>
	</div></div>
	<br>
	<div class="panel-body">
	<h2> Switch 2 </h2> <br>
	<div class="form-group">
	<button type="button" class="btn btn-success" onClick="publishOne2()">ON</button>
	<button type="button" class="btn btn-danger" onClick="publishZero2()">OFF</button>
	</div>
	</div>
	</div>
	<br>
	<div class="form-group">
	<label>Temperature</label>
	<input type="text" class="form-control input-sm" name="temperature" id="temp" readonly="true" />
</div>
	<br>
	<div class="form-group">
	<label>Humidity</label>
	 <input type="text" class="form-control input-sm" name="humidity" id="humidity" readonly="true" />
	 </div>
	</div>
	
</body>
</html>
<?php include("footer.html");?>