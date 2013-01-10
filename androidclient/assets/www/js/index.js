
var cachedData;
var currentItem = 0;

var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicity call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        app.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent: function(id) {
    /*
        var parentElement = document.getElementById(id);
        var listeningElement = parentElement.querySelector('.listening');
        var receivedElement = parentElement.querySelector('.received');

        listeningElement.setAttribute('style', 'display:none;');
        receivedElement.setAttribute('style', 'display:block;');
*/


        console.log('Received Event: ' + id);
        
		var onSuccess = function(position) {
        
			var lon = position.coords.latitude;
			var lat = position.coords.longitude;
			var ll = lat + "," + lon;
			
        	console.log('Received coordinates: ' + ll);
		
		       $.getJSON('http://server.neatocode.com/spaceneedle/explore-list.php',function(data,status) {
        			console.log('Received data: ' + data);


			        cachedData = data;

		        	//alert(data);
		        
		        	if ( data && data[0].image ) {
		        		$('#topImage').attr("src", data[0].image);
		        	
		        		//document.write('<img src="' + data.image + '" />');
		        	} else {
		        		document.write(data.name);
		        	}
		        
		        },'html'); 
		
		};

		function onError(error) {
		    alert("Couldn't get location.\n");
		}

		//navigator.geolocation.getCurrentPosition(onSuccess, onError);        
        
        navigator.geolocation.getCurrentPosition(onSuccess, onError, 
        { timeout: 5000, enableHighAccuracy: false });
        
        
    }
};

function nextButtonPressed() {
	$('#messageBox').html("Loading next...");
	
	currentItem++;
	
	if ( cachedData && cachedData[currentItem].image ) {
		$('#topImage').attr("src", cachedData[currentItem].image);
	} else {
		$('#messageBox').html("No more. Keep going!");
	}	
	
	
}
