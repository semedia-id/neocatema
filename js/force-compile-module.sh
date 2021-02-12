date +"/* Compile Time: %D %T */" > module.js

cat 'core/gas.js' >> module.js
cat 'core/w3color.js' >> module.js

# cat 'requirejs/require.js' >> module.js
# cat 'requirejs/domready.js' >> module.js
	
for f in module/*
do
	echo "" >> module.js
	echo "/* ------ $f ------*/" >> module.js
	echo "" >> module.js
	cat $f >> module.js
done;

sed -E 's/\t//g' module.js > temp

sed -E 's/(\(|\)|=|\}|\{|,|\:|>|<|\+)\s+/\1/g' temp > temp2
sed -E 's/\s+(\(|\)|=|\}|\{|,|\:|>|<|\+)/\1/g' temp2 > temp
sed 's/[[:space:]]*$//g' temp > temp2
sed 's/^[[:space:]]*//g' temp2 > temp
sed '/^$/d' temp > module.min.js

rm -f module.js
rm -f temp
rm -f temp2