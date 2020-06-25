<?php
function tipoNavegador ($user_agent) {
    if      (strpos($user_agent, 'MSIE') !== FALSE) { return 'Internet explorer'; }
    else if (strpos($user_agent, 'Edge') !== FALSE) { return 'Microsoft Edge'; }
    else if (strpos($user_agent, 'Trident') !== FALSE) {  return 'Internet explorer'; }
    else if (strpos($user_agent, 'Opera Mini') !== FALSE) { return "Opera Mini"; }
    else if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE) { return "Opera"; }
    else if (strpos($user_agent, 'Firefox') !== FALSE) { return 'Mozilla Firefox'; }
    else if (strpos($user_agent, 'Chrome') !== FALSE) { return 'Google Chrome'; }
    else if (strpos($user_agent, 'Safari') !== FALSE) { return "Safari"; }
    else    { return 'Otros'; }
}