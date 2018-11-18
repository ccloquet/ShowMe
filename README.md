# showme
Let a citizen send a picture or a live video to a 112 / 911 dispatching, on invitation from the dispatcher - Lightweight solution

Kinda photowall, for emergency services

Proof of concept, quick and dirty (really...), with a bit of security

1. The dispatcher connects to the interface of its organization
2. The dispatcher sends a link by SMS to the citizen.

__either: sending a picture__
3. The citizen clicks on the link and take a picture.
4. The picture is sent to the server.
5. The server validates the data received (image and key).
6. The dispatcher sees the pictures in reverse chronolocical order after a couple of seconds.

__or: live streaming__
3'. The citizen allows for cam and mic use.
4'. The P2P stream is sent to the dispatching

If you are looking for a more complete solution for dispatchings, a project like Geoloc18_112 (Twitter: @geoloc18_112) might be worth trying.

**Installation**
- copy the code in a folder of your web server
- a Peer Server is needed to use the video set up. You can for instance deploy yours on Heroku using : https://elements.heroku.com/buttons/peers/peerjs-server. The cloud server provided by peerJS is not suitable as it does not support https and there is a risk of identifiers collision


**Configuration**
- in html/params.js: 
  - upload_path should be set to the path & filename of the upload API
  - peerjs_url: fully qualified domain name of your PeerJS server without preceding https and without trailing '/' (eg : mypeerjs-server.example.com, but not https://mypeerjs-server.example.com/)

- in config/params.php: 
  - BASE_URL should be set to the page the the citizen will see (html/index.html)
  - $params contains the userids, usernames, secrets and API key for the SMS API -> in the future, this might move to a database

- in received/params.js:
  - base_url should be set to the page the the citizen will see (html/index.html)
  - peerjs_url: fully qualified domain name of your PeerJS server without preceding https and without trailing '/'  

**How does the security part works**

- Each organization gets a userid
- The user opens https://***/received/index.html?userid=USERID
- This queries the API (query.php), which returns a key
- This key is composed of a timestamp, a random hash and a validation hash
- The validation hash takes into account the timestamp, the random hash and a user-related secret

- When someone uploads a picture (upload.php), it must send the key
- The key is validated against the secret
- The image can only be uploaded if the key is less than 6 hours old

- Ideally, the secret should change periodically, so that if an attacker finds it, it would not be useful for a long time

We could also think to a password, but this would be to the detriment of the UX, is it worth here ?

-> Does all this make sense ?

**Libraries used**
- Leaflet [licence: https://github.com/Leaflet/Leaflet/blob/master/LICENSE] (c) 2010-2018, Vladimir Agafonkin, 2010-2011, CloudMade
- jQuery [licence: https://github.com/jquery/jquery/blob/master/LICENSE.txt] (c) JS Foundation and other contributors, https://js.foundation/
- peerJS [licence: https://github.com/peers/peerjs/blob/master/LICENSE] (c) 2015 Michelle Bu and Eric Zhang, http://peerjs.com
- fontAwesome [licence: https://fontawesome.com/v4.7.0/license/]
