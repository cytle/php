<?php

if (!function_exists('get_service_api_env_name')) {
    function get_service_api_env_name($name)
    {
        return 'SERVICE_API_' . strtoupper($name);
    }
}

?>
