date +"/* Compile Time: %D %T */" > module.js

cat 'gas/gas.js' >> module.js
cat 'w3color/w3color.js' >> module.js
cat 'requirejs/require.js' >> module.js

# cat 'requirejs/domready.js' >> module.js
	
for f in module/*
do
	echo "" >> module.js
	echo "/* ------ $f ------*/" >> module.js
	echo "" >> module.js
	cat $f >> module.js
done;

sed '/^$/d' module.js > temp
sed 's/^ *//g' temp > temp2
sed 's/[[:space:]]*$//g' temp > temp2
sed 's/^[[:space:]]*//g' temp2 > module.min.js

rm -f temp
rm -f temp2
