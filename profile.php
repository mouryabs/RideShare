<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="w3.css">

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="w3-theme-indigo" >
<head>
<?php 
session_start();
$name="..";
$abc="";
$usrride="";
if(isset($_SESSION['name']))
{
	$name=$_SESSION['name'];
	$college=$_SESSION['college'];
	$usrride=$_SESSION['usrride'];
}
else
{
	
	header("location: homepage.php");
}
?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname="cp_users";
$i=0;
$check=0;
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$sql= " SELECT first_name,lat,lng,college_name,email,phone,seats,ride_opts FROM userdata WHERE first_name != '$name'  " ;
//AND ride_opts != 'no_vehicle'
$result= $conn->query($sql);
if($result->num_rows>0)
{
	while($row = $result->fetch_assoc()) {
		$others[$i]= $row['first_name'];
		$lats[$i]=$row['lat'];
		$lngs[$i]=$row['lng'];
		$colls[$i]=$row['college_name'];
		$emails[$i]=$row['email'];
		$phone[$i]=$row['phone'];
		$seats[$i]=$row['seats'];
		$ride[$i]=$row['ride_opts'];
		$i++;
	}
}
if($usrride=="car" || $usrride=="bike")
{
	$check=1;
}
else
{
	$check=0;
}
$j=$i;
$i=0;
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js"></script>

<script>
var myCenter=new google.maps.LatLng(51.508742,-0.120850);
var initialLocation;
var map;
var collegelat= new google.maps.LatLng();
function initialize()
{
	 // Try W3C Geolocation (Preferred)
  if(navigator.geolocation) {
    browserSupportFlag = true;
    navigator.geolocation.getCurrentPosition(function(position) {
      initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
      map.setCenter(initialLocation);
	  marker.setPosition(initialLocation);
    }, function() {
      handleNoGeolocation(browserSupportFlag);
    });
  }
  // Browser doesn't support Geolocation
  else {
    browserSupportFlag = false;
    handleNoGeolocation(browserSupportFlag);
  }
var mapProp = {
  center:initialLocation,
  zoom:15,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };
map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
var marker=new google.maps.Marker({
  position:initialLocation,
  animation:google.maps.Animation.BOUNCE
  }); 


 var infowindow = new google.maps.InfoWindow({
  content:"<i class=\"material-icons w3-xlarge \">home</i>"+" You are here" 
  }); 

google.maps.event.addListener(marker, 'click', function() {
  infowindow.open(map,marker);
  })
  google.maps.event.addListener(marker,'click',function() {
  map.setZoom(17);
  map.setCenter(marker.getPosition());
  }); 

infowindow.open(map,marker);
marker.setMap(map);
}
function UpdateMap( )
	{
		var geocoder = new google.maps.Geocoder();    // instantiate a geocoder object
		
		// Get the user's inputted address
		var address = "<?php echo $college ?> " ;
	
		// Make asynchronous call to Google geocoding API
		geocoder.geocode( { 'address': address }, function(results, status) {
			var addr_type = results[0].types[0];	// type of address inputted that was geocoded
			if ( status == google.maps.GeocoderStatus.OK ) 
				ShowLocation( results[0].geometry.location, address, addr_type );
			else     
				alert("Geocode was not successful for the following reason: " + status);        
		});
	}
	
	// Show the location (address) on the map.
	function ShowLocation( latlng, address, addr_type )
	{
		

		// Center the map at the specified location
		map.setCenter( latlng );
        collegelat= latlng;

		// Set the zoom level according to the address level of detail the user specified
		var zoom = 12;
		switch ( addr_type )
		{
		case "administrative_area_level_1"	: zoom = 6; break;		// user specified a state
		case "locality"						: zoom = 10; break;		// user specified a city/town
		case "street_address"				: zoom = 15; break;		// user specified a street address
		}
		map.setZoom( zoom );
		
		// Place a Google Marker at the same location as the map center 
		// When you hover over the marker, it will display the title
		var marker = new google.maps.Marker( { 
			position: latlng,     
			map: map,    
            icon: {
                     path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
                     strokeColor: "red",
                     scale: 5
                  },			
			title: address
		});
		
		// Create an InfoWindow for the marker
		var contentString = "<i class=\"material-icons w3-xlarge \">account_balance</i> " + address + "";	// HTML text to display in the InfoWindow
		var infowindow = new google.maps.InfoWindow( { content: contentString } );
		
		// Set event to display the InfoWindow anchored to the marker when the marker is clicked.
		google.maps.event.addListener( marker, 'click', function() { infowindow.open( map, marker ); });
		getusers();
		setusers();
	}
	
google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>
 <style>

a:link    {color:white; background-color:transparent; text-decoration:none}
a:visited {color:white; background-color:transparent; text-decoration:none}
a:hover   {color:white; background-color:transparent; text-decoration:none}
a:active  {color:white; background-color:transparent; text-decoration:none}
         
.wrapper {
    /* Ensure wrapper contains the columns, even when they are floated, by
       creating a new Block formatting context */
    overflow: hidden;
	

}

.column {
    /* Ensure we have some margins when the columns are collapsed, or 
       other content is displayed below. */
    margin-bottom: 2em;
	margin-left: 1%;
	margin-right: 1% ;
	 float: left ;

}

@media screen and (min-width:1000px )  {
    .wrapper .column {
        width: 30%;
        margin:1%;
    }

}


</style>
<body>

<header id="fc" class="w3-container indigo">
  <i class="material-icons w3-opennav w3-xlarge">menu</i> 
  <a href="homepage.php">
  <h1>OUR FIRST ENDEAVOUR</h1>
  </a>
  </header> 
<div class="w3-topnav w3-padding indigo">
  <a href="whycarpooling.php">What is Carpooling ?</a>
  <a href="#link1">How to</a>
   <a href="http://localhost/newaboutus.php">About Us</a>
  <a href="#link3">Contact Us</a>

   <a href="logout.php"> Logout </a>
</div>
<header class="w3-container indigo-l5">
<h2 class="w3-text-indigo"><?php echo " &nbsp &nbsp Welcome Back ".$name ?></h2>
</header>
<div class=" w3-row w3-container">

<div class=" w3-col l2 w3-container">
<button class="w3-btn w3-indigo w3-col" onclick="UpdateMap()"> Update </button>
</div>
<div id="googleMap" style="height:600px;" class=" w3-col l10 w3-container "></div>
</div>
<div class="w3-container w3-theme-light w3-text-indigo" style="position:relative">
<a href="#fc" class="w3-text-indigo w3-right" style="font-size:2em"> ^ </a>
  <p class="w3-text-indigo">Le Gang</p>
</div>
<div id="id01" class="w3-modal">
  <div class="w3-modal-dialog">
    <div class="w3-modal-content w3-card-2">
	<header class="w3-container indigo ">
	 <a href="#" class="w3-closebtn">&times;</a>
	 <h2>User Profile</h2>

	</header>
	<div class="w3-row w3-container">
      <div  class="w3-container  w3-col l4 "> 
	  <i class="material-icons w3-xxxlarge ">person</i></a>
	  <ul class="w3-ul">
       <li> <h3 id="mdlhdr"></h3></li>
		<li><h3 id="mdlcar"></h3></li>
		</ul>
      </div>
      <div class="w3-container  w3-col l4">
	  <i class="material-icons w3-xxxlarge ">account_balance</i>
	  <ul class="w3-ul">
        <li><h3 id="mdlname"></h3></li>
       </ul>
      </div>
	  <div class="w3-container  w3-col l4  ">
	  <i class="material-icons w3-xxxlarge ">description</i>
	  <ul class="w3-ul">
       <li> <h4 id="mdlcol"></h4> </li>
       <li> <h4 id="mdlphone"></h4> </li>
      </ul>
      </div>
    </div>
	<div class="w3-container w3-center" id="buthide">
		  <button class="w3-btn "> Request Ride </button>
    </div>
	
	<div class="w3-container w3-center" id="buthide2">
		 <button class="w3-btn "> Offer Ride </button>
    </div>

	</div>
  </div>
</div>
</body>
<script>
function getusers()
{
	var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix({
        origins: [initialLocation],
        destinations: [collegelat],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false
    }, function (response, status) {
        if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
            var distance = response.rows[0].elements[0].distance.text;
            var duration = response.rows[0].elements[0].duration.text;
            //alert(distance);
 
        } else {
            alert("Unable to find the distance via road.");
        }
    });
}
function addresscoder(usaddress)
	{
		var geocoder = new google.maps.Geocoder();    // instantiate a geocoder object
		
		// Get the user's inputted address
		var address = usaddress ;
	    var lat=new google.maps.LatLng();

		// Make asynchronous call to Google geocoding API
		geocoder.geocode( { 'address': address }, function(results, status) {
			var addr_type = results[0].types[0];	// type of address inputted that was geocoded
			if ( status == google.maps.GeocoderStatus.OK ) 
				lat=results[0].geometry.location
			else     
				alert("Geocode was not successful for the following reason: " + status);        
		});
		return lat;
	}
	function setusers()
	{
		var markers = [];
		var i= <?php echo $j ; ?> ;
		
		 var bounds = new google.maps.LatLngBounds();
		 var infoWindow = new google.maps.InfoWindow(), marker;
		 var names = <?php echo json_encode($others); ?> ;
		 var lats = <?php echo json_encode($lats); ?> ;
		 var lngs = <?php echo json_encode($lngs); ?> ;
		 var cols= <?php echo json_encode($colls);?>;
		 var email=<?php echo json_encode($emails);?>;
		 var phone=<?php echo json_encode($phone);?>;
		 var ride=<?php echo json_encode($ride); ?>;
		 var seats=<?php echo json_encode($seats); ?> ;
		 var check= <?php echo $check ; ?>;
		for (var j=0; j< i ; j++)
		{
			markers[j]=[];
			markers[j][0]= names[j] ; 
			markers[j][1]= lats[j];
			markers[j][2]= lngs[j];
			markers[j][3]=cols[j];
			markers[j][4]=email[j];
			markers[j][5]=phone[j];
			markers[j][6]=ride[j];
			markers[j][7]=seats[j];
		}
        //alert(markers[0][0]+markers[1][0]);
		for( i = 0; i < <?php echo $j ; ?>; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
			 icon: {
                        path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
                        strokeColor: "green",
                        scale: 5
                   },
            title: markers[i][0]
        });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent('<a href="#id01" class="w3-text-indigo"><i class=\"material-icons w3-xlarge \">person</i></a> '+markers[i][0]);
			    document.getElementById("mdlname").innerHTML=markers[i][3];
				document.getElementById("mdlhdr").innerHTML=markers[i][0];
			    document.getElementById("mdlcol").innerHTML=" <i class=\"material-icons w3-xlarge \">email</i> "+markers[i][4];
				document.getElementById("mdlphone").innerHTML=" <i class=\"material-icons w3-xlarge \">smartphone</i> "+markers[i][5];
				if(markers[i][6]=="car")
				{
					document.getElementById("mdlcar").innerHTML=" <i class=\"material-icons w3-xxlarge \">directions_car</i> "+"<span class=\"w3-badge green w3-text-white\">"+markers[i][7]+"</span>";
				}
				else if(markers[i][6]=="bike")
				{
					document.getElementById("mdlcar").innerHTML=" <i class=\"material-icons w3-xxlarge \">directions_bike</i> "+"<span class=\"w3-badge green w3-text-white\">"+markers[i][7]+"</span>";
				}
				else
				{
					document.getElementById("mdlcar").innerHTML="<span class=\"w3-badge red w3-text-white\">"+"Looking for a ride!"+"</span>";
				}
				/*if(check==1 && markers[i][6]!="no_vehicle")
				{
					document.getElementById("buthide2").style.visibility = "hidden";
				}
				else if(check==0 && markers[i][6]!="no_vehicle")
				{
				 document.getElementById("buthide").style.visibility = "hidden";

				}
				else
				{
					document.getElementById("buthide2").style.visibility = "hidden";
					 document.getElementById("buthide").style.visibility = "hidden";
				} */

                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(13);
        google.maps.event.removeListener(boundsListener);
    });
    
		
}

</script>
</html> 