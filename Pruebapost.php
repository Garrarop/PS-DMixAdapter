<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

$module_name = 'dmixadapter';

$token = pSQL(Tools::encrypt($module_name.'/ajax.php'));

$module = Module::getInstanceByName($module_name);



 ?>
 
 <form action="http://localhost/Presta/modules/dmixadapter/api.php" target="_blank" method="post">
	<button type="submit">Prueba</button><br/>
	<label for="table"> Tabla </label>
	<input type="text" name="table"/> <br/>
	<label for="id"> ID </label> 
	<input type="text" name="id"/><br/>
	<label for="token"> Token </label>
	<input type="text" name="token" value="<?php echo $token ?>">
 </form>