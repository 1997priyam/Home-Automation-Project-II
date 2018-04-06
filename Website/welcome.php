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
	echo $_SESSION['username'];
	echo "<br/><a href='logout.php'>Logout</a>";
	 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Control</title>
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
    
</head>
<body>
    <h2> Switch 1 </h2> <br>
	<button type="button" onClick="publishOne()">ON</button>
	<button type="button" onClick="publishZero()">OFF</button>
	<br>
	<h2> Switch 2 </h2> <br>
	<button type="button" onClick="publishOne2()">ON</button>
	<button type="button" onClick="publishZero2()">OFF</button>
	<br>
	<h2>Temperature : </h2> <input type="text" name="temperature" id="temp" readonly="true" /> <br>
	<h2> Humidity : </h2> <input type="text" name="humidity" id="humidity" readonly="true" />
	
	
</body>
</html>