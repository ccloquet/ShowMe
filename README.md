# showme
Let a citizen send a picture to the 112 dispatching, on invitation - Lightweight solution

Proof of concept, quick and dirty (really...), with a bit of security

1. The dispatcher connects to the interface of its organization
2. The dispatcher sends a link by SMS to the caller.
3. The caller clicks on the link and take a picture.
4. The picture is sent to the server.
5. The server validates the data received (image and key).
6. The dispatcher refresh the page and see the pictures in reverse chronolocical order

If you are looking for a more complete solution for dispatchings, a project like Geoloc18_112 might be worth trying


**Configuration**
- in html/params.js : upload_path should be set to the path & filename of the upload API
- in shared/params.php : 
  - BASE_URL should be set to the page the the caller will see (html/index.html)
  - $params contains the userids, usernames, secrets and API key for the SMS API -> in the future, this might move to a database

**How does the security part work**

- Each organization gets a userid
- The user opens https://***/received/index.html?userid=USERID
- This queries the API returning a key
- This key is composed of a timestamp, a random hash and a validation hash
- The validation hash takes into account the timestamp, the random hash and a user-related secret

- When someone uploads a picture, it must send the key
- The key is validated against the secret
- The image can only be uploaded if the key is less than 6 hours old

- Ideally, the secret could change periodically, so that if an attacker finds it, it would not be useful for a long time

We could also think to a password, but this would be to the detriment of the UX, is it worth here ?
