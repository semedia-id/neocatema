To make symbolic link from user/workspace/scss, please follow this example.

There to much error to handle if create automaticly inside php, so:

in windows:
-----------

mklink /D scss ..\..\..\workspace\scss


in linux/bash:
--------------

ln -s ../../../workspace/scss scss