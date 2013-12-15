#!/bin/bash

#PATH=/Users/kiri/Sites/turmhof/site/fileadmin/schumacherfm/js/

#java -jar /Users/kiri/Sites/sources/ClosureCompiler/compiler.jar --js $PATH/grid.org.kiri.js --js_output_file $PATH/grid-min.js --compilation_level ADVANCED_OPTIMIZATIONS
java -jar /Users/kiri/Sites/sources/ClosureCompiler/compiler.jar --js grid.org.kiri.js --js_output_file grid.js
java -jar /Users/kiri/Sites/sources/ClosureCompiler/compiler.jar --js script.org.js --js_output_file script.js

cp grid.author.txt grid2.js
cat grid.js >> grid2.js
rm grid.js
mv grid2.js grid.js
