# showme
Let a citizen send a live video or a picture to a 112 / 911 public safety answering point (PSAP), on invitation from the dispatcher - Lightweight solution

Kinda photowall, for emergency services.

 
<img src="https://raw.githubusercontent.com/ccloquet/showme/master/screenshot.png" width="350" title="screenshot">

Ask for a demo via https://showme.my-poppy.eu

Proof of concept, quick and dirty (really...), with a bit of security

1. The dispatcher connects to the interface of its organization
2. The dispatcher sends a link by SMS to the citizen.

__either: live streaming__

3. The citizen turns mobile data on, clicks on the link and allows for cam and mic use
4. The P2P stream is sent to the dispatching
5. The server validates the remote peerid and answers the call
6. The stream is displayed in the browser

__or: sending a picture__

3. The citizen turns mobile data on, clicks on the link and takes a picture
4. The picture is sent to the server
5. The server validates the data received (image and key)
6. The dispatcher sees the picture in reverse chronolocical order after a couple of seconds.


If you are looking for a geolocation solution, a project like Geoloc18_112 (Twitter: @geoloc18_112) might be worth trying.


**Installation**
- copy the code in a folder of your web server
- a Peer Server is needed to use the video set up. You can deploy yours on Heroku using : https://elements.heroku.com/buttons/peers/peerjs-server. The cloud server provided by peerJS is not suitable as it does not support https and there is a risk of identifiers collision. The Heroku server should be dimensioned taking into account the forecasted usage.
- for real world use cases, a STUN & a TURN server is needed. This example uses Twilio's. See eg: https://peerjs.com/docs/#api, https://www.avaya.com/blogs/archives/2014/08/understanding-webrtc-media-connections-ice-stun-and-turn.html &  https://www.html5rocks.com/en/tutorials/webrtc/infrastructure, https://www.twilio.com/stun-turn. STUN Server usage is free, but TURN is not.
- An account on a SMS provider is needed. Clickatell has been temporarily chosen.

**Structure**
 - /html : html5 apps for the PSAP & the citizen
 - /php : user facing scripts
 - /config : php config
 - /src : php libraries

**Configuration**
- in html/params.js: 
  - __client_path__: should be set to the path of the upload API (normally, ending with /php/)
  - __peerjs_url__: fully qualified domain name of your PeerJS server without preceding https and without trailing '/' (eg : mypeerjs-server.example.com, but not https://mypeerjs-server.example.com/)

- in config/params.php: 
  - __TWILIO_SID__ & __TWILIO_APIKEY__ are the Twilio credentials to het the STUN (free) and TURN (paying) servers, and optionally to access their SMS API if chosen below
  - __TWILIO_FROM__ is the originating SMS number
  - __SMS_API__ = {'TWILIO'|'CLICKATELL'}: which API to choose
  - __BASE_URL__ should be set to the page the citizen will see (https://.../html/showme.html)
  - __$params__ contains the userids, usernames, secrets and API key for the SMS API -> in the future, this might move to a database
  - __BASE_FOLDER__ points to the folder where the received images will be collected. This folder will be created automatically at runtime
  - __USAGE_FOLDER__ points to the folder where the usage & credit data  will be stored. This folder will be created automatically at runtime
  - __INITIAL_TOPUP__ defines how many free credits someone receives when using the service the first time
  - the __$params__ array contains an array of [USERID =>	['secret'=> SECRETTKEY, 'name'=> USERNAME, 	'sms_apikey' => CLICKATELL_APIKEY],
 

**Troubleshooting**
- P2P webRTC connexions might be unstable. See eg: https://peerjs.com/docs/#api
- It may **fail behind a firewall** (a TURN server is configured, but port blocking / adress blocking might still be an issue)
- According to the cases, one SMS provider might be more reliant / performant than the other. We have good experiences with Twilio.

**How does the security part works**
- Each organization gets a userid
- The user opens https://***/html/index.html?userid=USERID
- This queries the API (query.php), which returns a key
- This key is composed of a timestamp, a random hash and a validation hash
- The validation hash takes into account the timestamp, the random hash and a user-related secret

- When someone uploads a picture (upload.php), it must send the key
- The key is validated against the secret
- The image can only be uploaded if the key is less than 6 hours old

- Ideally, the secret should change periodically, so that if an attacker finds it, it would not be useful for a long time

We could also think to a password, but this would be to the detriment of the UX, is it worth here ?

**Note**
- the capture API is still used as the capture from the stream is not (yet?) available eg. on iOS. For it to work, when a user clicks on the camera button, the stream is interrupted. It is resumed when the user clicks on the video, or automatically after the sending of the picture

**Handling of stability issues**
- If the remote peer cannot connect after 5 seconds, the stream is destroyed and the citizen can still send a picture
- should elaborate more cases

**Libraries used**
- Leaflet [licence: https://github.com/Leaflet/Leaflet/blob/master/LICENSE] (c) 2010-2018, Vladimir Agafonkin, 2010-2011, CloudMade
- jQuery [licence: https://github.com/jquery/jquery/blob/master/LICENSE.txt] (c) JS Foundation and other contributors, https://js.foundation/
- peerJS [licence: https://github.com/peers/peerjs/blob/master/LICENSE] (c) 2015 Michelle Bu and Eric Zhang, http://peerjs.com
- fontAwesome [licence: https://fontawesome.com/v4.7.0/license/]
