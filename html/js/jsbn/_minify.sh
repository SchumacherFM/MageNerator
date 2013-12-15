#!/bin/bash

cat jsbn.js jsbn2.js prng4.js rng.js rsa.js base64.js > rsa-max.js

COMPI=/Users/kiri/Sites/sources/ClosureCompiler/compiler.jar
java -jar $COMPI \
--compilation_level SIMPLE_OPTIMIZATIONS \
--js rsa-max.js \
--js_output_file rsa-min.js

cat authors.txt rsa-min.js > rsa-min2.js
rm rsa-min.js
mv rsa-min2.js rsa-min.js

COMPI=/Users/kiri/Sites/sources/ClosureCompiler/compiler.jar
java -jar $COMPI \
--js rsa-max.js \
--js_output_file rsa-min2.js
