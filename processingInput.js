// Indeed job search and google map - 2017

// global variables
var map, places, place, mapProp, country, city, subdivision, autocomplete;
var hostnameRegexp = new RegExp('^https?://.+?/');
var posY=0;
var posX=0;
var myPlace;

// this load the googleAPI for the map. 
function loadScript(src){
	var script = document.createElement("script");
	script.type = "text/javascript";
	//if(callback)script.onload=callback;
	document.getElementsByTagName("head")[0].appendChild(script);
	script.src = src;
}
  
loadScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyBHTRx7tANW8qTjbVrDe5RKeY0EPg2LEEQ&libraries=places&callback=initMap&language=en-CA');

// AJAX CALL HERE - JOBS INDEED
$(document).ready(function(){
	console.log("This is working");
	$("#ourForm").submit(function(event){
		var urlIndeed = 'http://localhost/googleAPI_v01/version_12/JobInput/callToIndeed.php';
		//console.log("my place obj is "+ myPlace);
		//get result form the form
		var formObj = $('#ourForm').serializeArray();
		var myResult = formObj.concat(myPlace);

		ajaxCall(urlIndeed, myResult);/*.done(function(){
			console.log("done is called!");
		});*/
		event.preventDefault();
	});
});

function initMap() {
	mapProp= {
		center:new google.maps.LatLng(51.508742,-0.120850),
		zoom:5,
		mapTypeControl: false,
		panControl: false,
		zoomControl: false,
		streetViewControl: false
		};
	// Create the autocomplete object and associate it with the UI input control.
	// Restrict the search to the default country, and to place type "cities".
	autocomplete = new google.maps.places.Autocomplete((
      document.getElementById('autocomplete')), {
      types: ['(cities)'],
      });
	  
	 // create map with all components
	map = new google.maps.Map(document.getElementById("map"),mapProp);
	
	// SET
	places = new google.maps.places.PlacesService(map);
	autocomplete.addListener('place_changed', onPlaceChanged);
}
// placeholder function for later
function setAutocompleteCountry() {
    map.setCenter({lat: 15, lng: 0});
    map.setZoom(2);
}

//When a place is selected, parse the elements
function onPlaceChanged() {
  place = autocomplete.getPlace();
  city = 0;
  for(i=0; i< place.address_components.length; i++)
  {
	if(place.address_components[i].types[0] == "country")
	{
	country = place.address_components[i].short_name;
	console.log("Country : "+ place.address_components[i].short_name);
	}
	if(place.address_components[i].types[0] == "administrative_area_level_1")
	{
	//principal subdivision
	subdivision = place.address_components[i].short_name;
	console.log("administrative_area_level_1 : "+place.address_components[i].short_name);
	}
	if(place.address_components[i].types[0] == "locality")
	{
	//if locality is not there...check level_3...
	city = place.address_components[i].short_name;
	console.log("locality is (same as administrative_area_level 3) :"+ place.address_components[i].short_name);
	}
	if(city == 0)
	{
		if(place.address_components[i].types[0] == "administrative_area_level_3")
		{
		city = place.address_components[i].short_name;
		console.log("administrative_area_level_3 (same as locality..) : "+place.address_components[i].short_name);
		}
	}
  }
  //create object with all the elements of the search for indeed components
  //myPlace = { country: country, subdivision: subdivision, city:city };
  var co = { name: "country", value: country };
  var sub = { name: "subdivision", value: subdivision };
  var ci = { name: "city", value: city };
  
  var theResult= [co, sub, ci];
  setMyPlace(theResult);
} 
function setMyPlace(thePlace)
{
	this.myPlace = thePlace;
}



function ajaxCall(url, myData){
	$.ajax(url,{
		data: myData,
		method: "POST",
		contentType: "application/x-www-form-urlencoded; charset=UTF-8"
	}).done(function(data){
		//alert("HOLY SHIET");
		//alert("OMFGBBQ "+data);
			
				console.log("success! the data is : "+ data);
				responseJSON = JSON.parse(data);
				//console.log("responseJSON is "+ responseJSON);
				
				if (typeof responseJSON.results[0] !== 'undefined') {
					rescnt = responseJSON.results.length;
					posY = Math.round(responseJSON.results[0].longitude * 10 )/10 ;
					posX = Math.round(responseJSON.results[0].latitude * 10 )/10 ;
					console.log(posY+" booo!");
					console.log(posX+" booo!");
					//var obj_jobKey = [{ "name": "jobkey", "value": responseJSON.results[0].jobkey }];
					//setJobDetail(obj_jobKey);
					
					for(i=0; i<rescnt;i++){
						if(responseJSON.results[i].sponsored == "true"){
							//console.log("jobtitle: "+responseJSON.results[i].jobtitle); 
							//console.log("company: "+responseJSON.results[i].company); 
							//console.log("snippet: "+responseJSON.results[i].snippet); 
							//console.log("location: "+responseJSON.results[i].city); 
							//console.log("location: "+responseJSON.results[i].country); 
						}
						
					}
					
				}
			
		
		
	}).fail(function(){
		
		alert("FAKOFFMATE");
	});
}

