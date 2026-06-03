<?php
session_start();
session_unset(); //Limpie las variables
session_destroy(); //Destruya la sesion en el servidor
header("Location: login.php");
exit();