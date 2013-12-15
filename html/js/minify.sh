#!/bin/bash

#PATH=~/Sites/turmhof/site/fileadmin/schumacherfm/js/
COMPI=~/Sites/sources/ClosureCompiler/compiler.jar

java -jar $COMPI \
--js bootstrap.js \
--js socialshareprivacy/jquery.socialshareprivacy.js \
--js plugins.org.js \
--js script.org.js \
--js_output_file combined.min.js


cp authors.txt combined.min2.js
cat combined.min.js >> combined.min2.js
rm combined.min.js
mv combined.min2.js combined.min.js
