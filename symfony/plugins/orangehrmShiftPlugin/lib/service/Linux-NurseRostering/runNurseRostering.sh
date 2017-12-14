#!/bin/sh

# Change directory to the directory of the script
# cd `dirname $0`

# Most examples run (potentially slower) with max heap of 128 MB (so -Xmx128m), but 1 example's dataset requires 1.5 GB

if [ -f /Library/Java/Home/bin/java ]; then
    
    /Library/Java/Home//bin/java -jar ./NurseRostering.jar $1
    echo "Starting examples app with JDK from environment variable JAVA_HOME ($JAVA_HOME)..."
    echo $0
    echo $1
    
else
    echo "Please install the JDK environment."
fi
