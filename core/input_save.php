<?php
/*---------------------------------------------------------------------------------------------
	Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['input_meta_box'] == 'true')
{
	foreach($_POST['acf'] as $key => $value)
	{
		if(is_array($value))
		{
			$value = implode(',',$value);
		}
		add_post_meta($post_id, '_acf_'.$key, $value);
	}
}

?>