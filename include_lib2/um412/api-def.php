<?php
cekvar("o");
switch ($o) {
	case "cses":
		$c=cekTimeoutSesi();
		echo ($c?0:1);
		exit;
	break;
	default:
}
