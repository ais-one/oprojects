phpais
======

PHP AIS Encoder Decoder v2.0b
-----------------------------

This is a working library for both encoding and decoding AIS sentences using PHP.

### ais.2.php (Version 2)
+ Decoding:
  + Sample Demo File: ais.2.decode_sample.php
    + call method process_ais_buf(...) and pass in AIS data from a serial or IP source or a test AIS string
    + override method process_ais_itu(...) to process the data the way you want to handle it
+ Encoding: see the example in the file to form an AIS message, uses the function mk_ais(...)
  + Sample Demo File: ais.2.decode_sample.php
    + form up the sentence, determine the Channel, Sequence, etc.
    + call method mk_ais(...) to form the AIS packet
    + send it out

### v1/decode.ais.php & v1/base.ais.php (Older version 1)

The files remain here only for reference. It is superceded by Version 2 above which is more maintainable and neat.

To try, just do the following
+ copy all **php** files to webserver
+ call via web-browser and view the result
+ testing code is towards the end

### feedback and fixes

Please feedback any bugs you may find and be patient with updates and fixes, as I don't even have time to launch my own personal website.

Enjoy this PHP people!



Aaron Gong

