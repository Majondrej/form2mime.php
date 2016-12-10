# form2mime.php

This form mailer supports file attachments (see [RFC-1867](https://www.ietf.org/rfc/rfc1867.txt)).

It does not do any auth, spam recognition etc.
Inteded use: Add a fixed FROM and TO address etc and make your PHP server send you MIME emails when it receives an HTTP POST request containing form fields as well as files.

Warning: Do not change the code to parametrize the `TO` address by user input or you will have created an open mail relay. ☠

My use case: I implemented a "get support" feature using this and this [Java Code snippet to upload files by sending multipart request programmatically](http://www.codejava.net/java-se/networking/upload-files-by-sending-multipart-request-programmatically).
I was looking for a simple form mailer for the backend and couldn't find any.
Hence I release this code into the wild.
Use it as you like.

Tested on PHP 5.2 and 5.5. Should easily run on many more versions.

