
var cachedData;
var currentItem = 0;

function onShake() {

	$('#messageBox').html("Shaken! Next!");
	nextButtonPressed();
}

var shake = (function () {
	var shake = {},
		watchId = null,
		options = { frequency: 300 },
		previousAcceleration = { x: null, y: null, z: null },
		shakeCallBack = onShake;
	
	// Start watching the accelerometer for a shake gesture
	shake.startWatch = function (onShake) {
		shakeCallBack = onShake;
		watchId = navigator.accelerometer.watchAcceleration(getAccelerationSnapshot, handleError, options);
	};
	
	// Stop watching the accelerometer for a shake gesture
	shake.stopWatch = function () {
		if (watchId !== null) {
			navigator.accelerometer.clearWatch(watchId);
			watchId = null;
		}
	};
	
	// Gets the current acceleration snapshot from the last accelerometer watch
	function getAccelerationSnapshot() {
		navigator.accelerometer.getCurrentAcceleration(assessCurrentAcceleration, handleError);
	}
	
	// Assess the current acceleration parameters to determine a shake
	function assessCurrentAcceleration(acceleration) {
		var accelerationChange = {};
		if (previousAcceleration.x !== null) {
			accelerationChange.x = Math.abs(previousAcceleration.x, acceleration.x);
			accelerationChange.y = Math.abs(previousAcceleration.y, acceleration.y);
			accelerationChange.z = Math.abs(previousAcceleration.z, acceleration.z);
		}
		if (accelerationChange.x + accelerationChange.y + accelerationChange.z > 30) {
			// Shake detected
			if (typeof (shakeCallBack) === "function") {
				shakeCallBack();
			}
			shake.stopWatch();
			setTimeout(shake.startWatch, 1000);
			previousAcceleration = { 
				x: null, 
				y: null, 
				z: null
			}
		} else {
			previousAcceleration = {
				x: acceleration.x,
				y: acceleration.y,
				z: acceleration.z
			}
		}
	}

	// Handle errors here
	function handleError() {
	}
	
	return shake;
})();


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
						$('#messageBox').html("No more. Keep going!");
		        	}
		        	
		        	$(".grid").hide();
		        	
		        
		        },'html'); 
		        
		        shake.startWatch();
		
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
	
	if (!cachedData) return;
	
	currentItem++;
	
	if ( cachedData && cachedData[currentItem] && cachedData[currentItem].image ) {
		$('#topImage').attr("src", cachedData[currentItem].image);
	} else {
		$('#messageBox').html("No more. Keep going!");
	}	
	
	
}
