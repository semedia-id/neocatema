echo "/* "> __note.scss
echo " * neoca.css - https://github.com/tacoen/neocatema">> __note.scss
echo " * ">> __note.scss
date +" * Compiled Time Stamp: %D %T" >> __note.scss
echo " * ">> __note.scss
echo " */ ">> __note.scss
echo " ">> __note.scss

date +"// %D %T" > _function.scss
echo " ">> _function.scss

ls -1 function/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^function\/_/@import "function\//' \
	>> '_function.scss'


date +"// %D %T" > _component.scss
echo " ">> _component.scss

ls -1 component/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^component\/_/@import "component\//' \
	>> '_component.scss'

date +"// %D %T" > _element.scss
echo " ">> _element.scss

ls -1 element/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^element\/_/@import "element\//' \
	>> '_element.scss'

date +"// %D %T" > _combine.scss
echo " ">> _combine.scss

ls -1 combine/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^combine\/_/@import "combine\//' \
	>> '_combine.scss'


date +"// %D %T" > _configuration.scss
echo " ">> _configuration.scss

ls -1 configuration/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^configuration\/_/@import "configuration\//' \
	>> '_configuration.scss'
	

date +"// %D %T" > _utilities.scss
echo " ">> _utilities.scss

ls -1 utilities/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^utilities\/_/@import "utilities\//' \
	>> '_utilities.scss'
	
echo " "> _dev.scss
ls -1 dev/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^dev\/_/@import "dev\//' \
	>> '_dev.scss'	
	
	
echo "// Bourbon 4.2.7 - http://bourbon.io - MIT License" > vendor/_bourbon.scss
ls -1 vendor/bourbon/* \
	| sed -e 's/\.scss$/";/' \
	| sed -e 's/^vendor\/bourbon\/_/@import "bourbon\//' \
	>> 'vendor/_bourbon.scss'	