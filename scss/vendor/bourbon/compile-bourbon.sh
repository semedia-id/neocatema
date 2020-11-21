echo "// Bourbon 4.2.7 - http://bourbon.io - MIT License" > _bourbon.scss
ls -1 files/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^files\/_/@import "files\//' \
	>> '_bourbon.scss'	
