	// bcz Internet Explorer does not support new URL()
	// https://stackoverflow.com/questions/48447629/new-urllocation-href-doesnt-work-in-ie
	function getQueryString() 
	{
          var key = false, res = {}, itm = null;
          // get the query string without the ?
          var qs = location.search.substring(1);
          // check for the key as an argument
          if (arguments.length > 0 && arguments[0].length > 1)
            key = arguments[0];
          // make a regex pattern to grab key/value
          var pattern = /([^&=]+)=([^&]*)/g;
          // loop the items in the query string, either
          // find a match to the argument, or build an object
          // with key/value pairs
          while (itm = pattern.exec(qs)) 
	  {
            if (key !== false && decodeURIComponent(itm[1]) === key)
              return decodeURIComponent(itm[2]);
            else if (key === false)
              res[decodeURIComponent(itm[1])] = decodeURIComponent(itm[2]);
          }

          return key === false ? res : null;
	}

	function hasGetUserMedia() 
	{
		return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
	}

	//https://github.com/peers/peerjs/issues/227
	// pass the peer instance, and it will start sending heartbeats

	// stop them later
	// heartbeater.stop();

	function makePeerHeartbeater ( peer ) {
	    var timeoutId = 0;
	    function heartbeat () {
        	timeoutId = setTimeout( heartbeat, 10000 );
	        if ( peer.socket._wsOpen() ) {
        	    peer.socket.send( {type:'HEARTBEAT'} );
	        }
	    }
	    // Start 
	    heartbeat();
	    // return
	    return {
	        start : function () {
	            if ( timeoutId === 0 ) { heartbeat(); }
	        },
	        stop : function () {
	            clearTimeout( timeoutId );
	            timeoutId = 0;
	        }
	    };
	}
