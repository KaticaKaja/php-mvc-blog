<?php

function redirect($url, $statusCode = 303)
{
   header("Location: ".URLROOT.$url, false, $statusCode);
   exit();
}