<!doctype html>
<html>
<head>
<script>
function addresscoder()
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
</script>

<?php
ob_start();
// define variables and set to empty values
$phone=$pwd1=$pwd2=$college_name=$s_time=$l_name=$f_name = $email = $gender = $address = $vehicle = "";
$flag=false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $f_name = test_input($_POST["f_name"]);
   $email = test_input($_POST["email"]);
   $vehicle = test_input($_POST["vehicle"]);
   $address = test_input($_POST["address"]);
   $gender = test_input($_POST["gender"]);
   $l_name = test_input($_POST["l_name"]);
   $s_time = test_input($_POST["s_time"]);
   $college_name = test_input($_POST["college_name"]);
   $pwd1 = test_input($_POST["pwd1"]);
   $pwd2 = test_input($_POST["pwd2"]);
   $phone=test_input($_POST["num"]);
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
function is_valid_password($pawd){
// accepted password length between 5 and 20, start with character.
    if (preg_match("/^[a-zA-Z][0-9a-zA-Z_!$@#^&]{5,20}$/", $pawd))
        return true;
    else
        return false;
}
ob_end_flush();
?>
<?php 
class Geocode
{
    /**
     * API URL through which the address will be obtained.
     */
    private $service_url = "://maps.googleapis.com/maps/api/geocode/json?sensor=false";
    /**
     * Array containing the query results
     */
    private $service_results;
    
    /**
     * Address chunks
     */
    protected $address = '';
    protected $latitude = '';
    protected $longitude = '';
    protected $country = '';
    protected $locality = '';
    protected $district = '';
    protected $postcode = '';
    protected $town = '';
    protected $streetNumber = '';
    protected $streetAddress = '';
    /**
     * Constructor
     *
     * @param string $address The address that is to be parsed
     * @param boolean $secure_protocol true if you need to use HTTPS and false otherwise (Defaults to false)
     */
    public function __construct($address, $secure_protocol = false)
    {
        $this->service_url = $secure_protocol ? 'https' . $this->service_url : 'http' . $this->service_url;
        $this->fetchAddressLatLng($address);
        
        $url = $this->getServiceUrl() . '&latlng='.$this->latitude.','.$this->longitude;
        $this->service_results = $this->fetchServiceDetails($url);
        $this->populateAddressVars();
    }
    /**
     * Returns the private $service_url
     * 
     * @return string The service URL
     */
    public function getServiceUrl()
    {
        return $this->service_url;
    }
    /**
     * fetchServiceDetails
     * 
     * Sends request to the passed Google Geocode API URL and fetches the address details and returns them
     * 
     * @param  string $url Google geocode API URL containing the address or latitude/longitude
     * @return bool|object false if no data is returned by URL and the detail otherwise
     */
    private function fetchServiceDetails($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $service_results = json_decode(curl_exec($ch));
        if ($service_results && $service_results->status === 'OK') {
            return $service_results;
        }
        return false;
    }
    /**
     * populateAddressVars
     * 
     * Populates the address chunks inside the object using the details returned by the service request
     * 
     */
    private function populateAddressVars()
    {
        if (!$this->service_results || !$this->service_results->results[0]) {
            return false;
        }
        foreach ($this->service_results->results[0]->address_components as $component) {
            if (in_array('street_number', $component->types)) {
                $this->streetNumber = $component->long_name;
            } elseif (in_array('locality', $component->types)) {
                $this->locality = $component->long_name;
            } elseif (in_array('postal_town', $component->types)) {
                $this->town = $component->long_name;
            } elseif (in_array('administrative_area_level_2', $component->types)) {
                $this->country = $component->long_name;
            } elseif (in_array('country', $component->types)) {
                $this->country = $component->long_name;
            } elseif (in_array('administrative_area_level_1', $component->types)) {
                $this->district = $component->long_name;
            } elseif (in_array('postal_code', $component->types)) {
                $this->postcode = $component->long_name;
            } elseif (in_array('route', $component->types)) {
                $this->streetAddress = $component->long_name;
            }
        }
    }
    /**
     * fetchAddressLatLng
     *
     * Fetches the latitude and longitude for the address
     * 
     * @param string $address Address whose latitude and longitudes are required
     * @return mixed false if there is no address found otherwise populates the latitude and longitude for the address 
     * 
     */
    public function fetchAddressLatLng($address)
    {
        $this->address = $address;
        if (!empty($address)) {
            $tempAddress = $this->getServiceUrl() . "&address=" . urlencode($address);
            $this->service_results = $this->fetchServiceDetails($tempAddress);
            if ($this->service_results !== false) {
                $this->latitude = $this->service_results->results[0]->geometry->location->lat;
                $this->longitude = $this->service_results->results[0]->geometry->location->lng;
            }
        } else {
            return false;
        }
    }
    /**
     * getAddress
     * 
     * Returns the address if found and the default value otherwise
     * 
     * @param string $default Default address that is to be returned if the address is not found
     * @return string Default address string if no address found and the address otherwise
     */
    public function getAddress($default = '')
    {
        return $this->address ? $this->address : $default;
    }
    /**
     * getLatitude
     * 
     * Returns the latitude if found and the default value otherwise
     * 
     * @param string $default Default latitude that is to be returned if the latitude is not found
     * @return string|float Default latitude if no latitude found and the latitude otherwise
     */
    public function getLatitude($default = '')
    {
        return $this->latitude ? $this->latitude : $default;
    }
    /**
     * getLongitude
     * 
     * Returns the longitude if found and the default value otherwise
     * 
     * @param string $default Default longitude that is to be returned if the longitude is not found
     * @return string|float Default longitude if no longitude found and the longitude otherwise
     */
    public function getLongitude($default = '')
    {
        return $this->longitude ? $this->longitude : $default;
    }
    /**
     * getCountry
     * 
     * Returns the country if found and the default value otherwise
     * 
     * @param string $default Default country that is to be returned if the country is not found
     * @return string Default country string if no country found and the country otherwise
     */
    public function getCountry($default = '')
    {
        return $this->country ? $this->country : $default;
    }
    /**
     * getLocality
     * 
     * Returns the locality/country if found and the default value otherwise
     * 
     * @param string $default Default locality/country that is to be returned if the locality/country is not found
     * @return string Default locality/country string if no locality/country found and the locality/country otherwise
     */
    public function getLocality($default = '')
    {
        return $this->locality ? $this->locality : $default;
    }
    /**
     * getDistrict
     * 
     * Returns the district if found and the default value otherwise
     * 
     * @param string $default Default district that is to be returned if the district is not found
     * @return string Default district string if no district found and the district otherwise
     */
    public function getDistrict($default = '')
    {
        return $this->district ? $this->district : $default;
    }
    /**
     * getPostcode
     * 
     * Returns the postcode if found and the default value otherwise
     * 
     * @param string $default Default postcode that is to be returned if the postcode is not found
     * @return string Default postcode string if no postcode found and the postcode otherwise
     */
    public function getPostcode($default = '')
    {
        return $this->postcode ? $this->postcode : $default;
    }
    /**
     * getTown
     * 
     * Returns the town if found and the default value otherwise
     * 
     * @param string $default Default town that is to be returned if the town is not found
     * @return string Default town string if no town found and the town otherwise
     */
    public function getTown($default = '')
    {
        return $this->town ? $this->town : $default;
    }
    /**
     * getStreetNumber
     * 
     * Returns the street number if found and the default value otherwise
     * 
     * @param string $default Default street number that is to be returned if the street number is not found
     * @return string Default street number if no street number found and the actual street number otherwise
     */
    public function getStreetNumber($default = '')
    {
        return $this->streetNumber ? $this->streetNumber : $default;
    }
    /**
     * getStreetAddress
     * 
     * Returns the address if found and the default value otherwise
     * 
     * @param string $default Default address that is to be returned if the address is not found
     * @return string Default address string if no address found and the address otherwise
     */
    public function getStreetAddress($default = '')
    {
        return $this->streetAddress ? $this->streetAddress : $default;
    }
    /**
     * @return string the object in string format
     */
    public function __toString()
    {
        $methods = array(
            'getAddress' => 'Address',
            'getLatitude' => 'Latitude',
            'getLongitude' => 'Longitude',
            'getCountry' => 'Country',
            'getLocality' => 'Locality',
            'getDistrict' => 'District',
            'getPostcode' => 'Postal Code',
            'getStreetAddress' => 'Street Address',
            'getStreetNumber' => 'Street Number'
        );
        $formattedString = '';
        foreach ($methods as $method => $label) {
            $formattedString .= $label.' =>'.$method.'<br/>';
        }
        return $formattedString;
    }
}
if($f_name!="")
{

$geocode = new Geocode($address);
$lat= $geocode->getLatitude();
$lng= $geocode->getLongitude();
echo $lat ;
echo $lng ;
}
 ?>
<?php 
ob_start();
/*echo "<h2>Your Input:</h2>";
echo $f_name;
echo "<br>";
echo $email;
echo "<br>";
echo $address;
echo "<br>";
echo $vehicle;
echo "<br>";
echo $gender;
echo "<br>";
echo $l_name;
echo "<br>";
echo $s_time;
echo "<br>";
echo $college_name;
echo "<br>";
echo $pwd1; */
$servername = "localhost";
$username = "root";
$password = "";
$dbname="cp_users";
$flag=0;
$seats=0;
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$sql="";
if($f_name!= "")
{
	if($vehicle=="car")
	{
		$seats=4;
	}
	else if($vehicle=="bike")
	{
		$seats=1;
	}
$sql = "INSERT INTO userdata (first_name, last_name, gender, ride_opts, address, start_time, email, college_name, password, lat, lng,phone,seats)
VALUES ('$f_name', '$l_name', '$gender', '$vehicle', '$address', '$s_time', '$email','$college_name','$pwd1','$lat','$lng','$phone','$seats');";
if ($conn->query($sql) === TRUE) {
   $flag=1;
} else {
  //  echo "Error: " . $sql . "<br>" . $conn->error;
}
}


$conn->close();
if($flag==1)
{
header("location:http://localhost/homepage.php") ; 
}
ob_end_flush();
?>
<title>
Sign up form
</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="w3-theme-indigo" >
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

@media screen and (min-width: 1000px) {
    .wrapper .column {
        width: 30%;
       
    }
}
</style>
<body>
<header id="fc" class="w3-container indigo">
  <i class="material-icons w3-opennav w3-xlarge">menu</i> 
  <a href="homepage.php">
  <h1 id="su">OUR FIRST ENDEAVOUR</h1>
  </a>
  </header>
<div class="w3-topnav w3-padding indigo">
 <a href="whycarpooling.php">What is Carpooling ?</a>

  <a href="#link1">How to</a>
   <a href="http://localhost/newaboutus.php">About Us</a>
  <a href="#link3">Contact Us</a>
</div>

<br>
<br>	
<div class="w3-container w3-card-4 wrapper" >
<div class=" w3-padding-16">
<h1 class="w3-text-indigo" > Sign Up : </h1>
</div>
<form method="post" action="http://localhost/signup.php?">
  <div class="w3-group">      
    <input class="w3-input " type="text" name="f_name" required>
    <label class="w3-label">First Name</label>
  </div>
  <div class="w3-group">      
    <input class="w3-input " type="text" name="l_name" required>
    <label class="w3-label">Last Name</label>
  </div>
<br> 
<h3 class="w3-text-indigo"> Gender : </h3>
 <label class="w3-checkbox">
    <input type="radio" name="gender" value="male">
    <div class="w3-checkmark"></div> Male
  </label>
<label class="w3-checkbox">
    <input type="radio" name="gender" value="female">
    <div class="w3-checkmark"></div> Female
  </label>
  <br> <br>
  <div class="w3-group">      
    <input class="w3-input " type="password" name="pwd1" id="p1" required>
    <label class="w3-label">Password</label>
  </div>
  <div class="w3-group">      
    <input class="w3-input " type="password" name="pwd2" id="p2" required>
    <label class="w3-label">Password Again</label>
  </div>
  <div class="w3-group">      
    <input class="w3-input " type="text" name="num" id="n1" required>
    <label class="w3-label">Mobile Number</label>
  </div>
  <br><br>
  <h3 class="w3-text-indigo"> Ride options : </h3>

 <label class="w3-checkbox">
    <input type="radio" name="vehicle" value="car">
    <div class="w3-checkmark"></div> I have a car 
  </label>
  <br>
  <label class="w3-checkbox">
    <input type="radio" name="vehicle" value="bike">
    <div class="w3-checkmark"></div> I have a two-wheeler
  </label>
  <br>
  <label class="w3-checkbox">
    <input type="radio" name="vehicle" value="no_vehicle">
    <div class="w3-checkmark"></div> Looking for a ride 
  </label>
  <div class="w3-group">      
    <input class="w3-input " type="text" name="college_name" required>
    <label class="w3-label">College Name </label>
  </div>
  <br><br>
  <div class="w3-group">      
    <input class="w3-input " type="text" name="address" required>
    <label class="w3-label">Address</label>
  </div>
<br> 
<p class="w3-text-indigo">
College Starting time : 
</p>
<label class="w3-border">
<input type="time" name="s_time" min="8" max="12">
<br>
</label>
  <div class="w3-group">      
    <input class="w3-input " type="email" name="email" required>
    <label class="w3-label">E-mail</label>
  </div>
  
<input class="w3-btn" type="submit" value="Submit">
<br>
<br>
<br>
<br>
<br>
<br>
<br>
</form>
</div>

<div class="w3-container w3-theme-light w3-text-indigo" style="position:relative">
<a href="#su" class="w3-text-indigo w3-right" style="font-size:2em"> ^ </a>
 <p class="w3-text-indigo">Le Gang</p>
</div>
<script>
function pwdwrng()
{
	document.getElementById("p1").style.borderColor="red";
    document.getElementById("p2").style.borderColor="red";
}

</body>
<html>