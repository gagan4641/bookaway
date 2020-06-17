<?php
return array(
/** set your paypal credential **/
// 'client_id' => 'Aa-gucfb3VBwUvLQtpAwnlEGqKWIsdzWdJkb5Lx01J3ETe_rZPs3c1825dG9hAEUkWvX1F1BeT0mGWYi',
// 'secret' => 'EGuM0LUE05CFCOu401MxWIYnPNSCb3aciR0Ll2cQJc-olqyT6uCc4BnLIpNecOFiomObYCtFgcUGLRUc',

'client_id' => 'AWyuR5kMMYNHMOYHi3sxbvIx_ivkXzA3uqsp3FLQ4lqE8zqIyhxEheupyUdotfuaDD2zXGA2UVW0z_zh',
'secret' => 'ECQkAaREAafpl31GHRivaBM_rVPqDu9tQG29lwtUtWmax1CrkZlIEZlu-BEjwXpo_LvwaXtzYXE7YeIi',
/**
* SDK configuration 
*/
'settings' => array(
    /**
    * Available option 'sandbox' or 'live'
    */
    'mode' => 'live',
    /**
    * Specify the max request time in seconds
    */
    'http.ConnectionTimeOut' => 1000,
    /**
    * Whether want to log to a file
    */
    'log.LogEnabled' => true,
    /**
    * Specify the file that want to write on
    */
    'log.FileName' => storage_path() . '/logs/paypal.log',
    /**
    * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
    *
    * Logging is most verbose in the 'FINE' level and decreases as you
    * proceed towards ERROR
    */
    'log.LogLevel' => 'FINE'
    ),
);