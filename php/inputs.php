
<button naked
	title='Scheme' id="neoca_scheme_switch"
	onclick="neoca_switch_scheme(this);">
	<i class='ncico-toggle-left'></i>
	<span>Scheme</span>
	</button>


<div class='accordion'>
<input toggle id='item1' type='checkbox'>
<label for='item1'>Theme Colors</label>
<div toggle-content>

	<div class='color-input'>
	<div><?php color_input('background','bc'); ?></div>
	<div><?php color_input('text','bc'); ?></div>
	<div><?php color_input('surface','nc'); ?></div>
	<div><?php color_input('accent','nc'); ?></div>
	<div><?php color_input('button','nc'); ?></div>
	</div><!--eof chart -->
	
</div>
</div>

<div class='accordion'>
<input toggle id='item5' type='checkbox'>
<label for='item5'>System Colors</label>
<div toggle-content>

	<div class='color-input'>
	<div><?php color_input('warning','bc'); ?></div>
	<div><?php color_input('error','bc'); ?></div>
	<div><?php color_input('info','bc'); ?></div>
	<div><?php color_input('success','nc'); ?></div>
	</div><!--eof chart -->
	
</div>
</div>


<div class='accordion'>
<input toggle id='item2' type='checkbox'>
<label for='item2'>Typography</label>
<div toggle-content>

	<form class='inline-form'>

	<div class='form-field'>
	<div class='form-label'>Font Size</div>
	<div class='form-data'><input onchange="nc_cssvar('font-size','base',this.value)" type='text' value='' name='font-size'></div>
	</div>
	<div class='form-field'>
	<div class='form-label'>Line Height</div>
	<div class='form-data'><input onchange="nc_cssvar('line-height','base',this.value)" type='text' value='' name='line-height'></div>
	</div>

	<p class='sep'>Font</p>
	
	<div class='form-field'>
	<div class='form-label'>Base</div>
	<div class='form-data'><input onchange="nc_cssvar('font-base','base',this.value)"type='text' value='' name='font-base'></div>
	</div>

	<div class='form-field'>
	<div class='form-label'>Alternate</div>
	<div class='form-data'><input onchange="nc_cssvar('font-alter','base',this.value)"type='text' value='' name='font-alter'></div>
	</div>

	<div class='form-field'>
	<div class='form-label'>Heading</div>
	<div class='form-data'><input onchange="nc_cssvar('font-head','base',this.value)"type='text' value='' name='font-head'></div>
	</div>

	<div class='form-field'>
	<div class='form-label'>Monotype</div>
	<div class='form-data'><input onchange="nc_cssvar('font-mono','base',this.value)"type='text' value='' name='font-mono'></div>
	</div>

	</form>

</div>
</div>

<div class='accordion'>
<input toggle id='item3' type='checkbox'>
<label for='item3'>Page Setting</label>
<div toggle-content>


	<form class='inline-form'>

	<div class='form-field'>
	<div class='form-label'>Padding</div>
	<div class='form-data'><input onchange="nc_cssvar('padding','base',this.value)" type='text' value='' name='padding'></div>
	</div>
	
	<div class='form-field'>
	<div class='form-label'>Margin</div>
	<div class='form-data'><input onchange="nc_cssvar('margin','base',this.value)" type='text' value='' name='margin'></div>
	</div>

	</form>


</div>
</div>


<div class='accordion'>
<input toggle id='item4' type='checkbox'>
<label for='item4'>Decorant</label>
<div toggle-content>


	<form class='inline-form'>

	<div class='form-field'>
	<div class='form-label'>Shade</div>
	<div class='form-data'><input onchange="nc_cssvar('shade',false,this.value)" type='text' value='' name='shade'></div>
	</div>

	<div class='form-field'>
	<div class='form-label'>Border</div>
	<div class='form-data'><input onchange="nc_cssvar('border',false,this.value)" type='text' value='' name='border'></div>
	</div>
	
	<div class='form-field'>
	<div class='form-label'>Highlight</div>
	<div class='form-data'><input onchange="nc_cssvar('hilite',false,this.value)" type='text' value='' name='hilite'></div>
	</div>

	<div class='form-field'>
	<div class='form-label'>Shadow</div>
	<div class='form-data'><input onchange="nc_cssvar('shadow',false,this.value)" type='text' value='' name='shadow'></div>
	</div>

	<div class='form-field'>
	<div class='form-label'>B-Radius</div>
	<div class='form-data'><input onchange="nc_cssvar('bradius','base',this.value)" type='text' value='' name='bradius'></div>
	</div>

	<div class='form-field'>
	<div class='form-label'>Separator</div>
	<div class='form-data'><input onchange="nc_cssvar('hrsep','base',this.value)" type='text' value='' name='hrsep'></div>
	</div>

	</form>


</div>
</div>
