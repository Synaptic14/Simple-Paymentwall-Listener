<?php
error_reporting(E_ALL & ~E_NOTICE);
    define('MSSQL_DSN', '');
    define('MSSQL_USER', '');
    define('MSSQL_PASS', '');
    // Fix deprecated mssql connection construct
    $mssql = odbc_connect('Driver={SQL Server};Server='.MSSQL_DSN.';', MSSQL_USER, MSSQL_PASS);
    odbc_exec($mssql, 'USE [WEBSITE_DBF]');
define('SECRET', ''); // secret key of application
define('IP_WHITELIST_CHECK_ACTIVE', true);

define('CHARGEBACK', 2);

//Make sure whitelist is the same

$ipsWhitelist = array(
    '174.36.92.186',
    '174.36.96.66',
    '174.36.92.187',
    '174.36.92.192',
    '174.37.14.28',
    '72.193.119.51'
);
$userId = isset($_GET['uid']) ? $_GET['uid'] : null;
$credits = isset($_GET['currency']) ? $_GET['currency'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;
$refId = isset($_GET['ref']) ? $_GET['ref'] : null;
$signature = isset($_GET['sig']) ? $_GET['sig'] : null;
$sign_version = isset($_GET['sign_version']) ? $_GET['sign_version'] : null;
$reason = isset($_GET['reason']) ? $_GET['reason'] : null;

$result = false;

$errors = array ();
if (!empty($userId) && !empty($credits) && isset($type) && !empty($refId) && !empty($signature)) {
    $signatureParams = array();
    
    /**
     *  version 1 signature
     */
    if (empty($sign_version) || $sign_version <= 1) {
         $signatureParams = array(
            'uid' => $userId,
            'currency' => $credits,
            'type' => $type,
            'ref' => $refId,
            'reason' => $reason
        );
    }
    /**
     *  version 2+ signature
     */
    else {
        $signatureParams = array();
        foreach ($_GET as $param => $value) {    
            $signatureParams[$param] = $value;
        }
        unset($signatureParams['sig']);
    }
    

    $signatureCalculated = calculatePingbackSignature($signatureParams, SECRET, $sign_version);
    
    if (!IP_WHITELIST_CHECK_ACTIVE || in_array($_SERVER['REMOTE_ADDR'], $ipsWhitelist)) {
        if ($signature == $signatureCalculated) {
            $result = true;
        } else {
            $errors['signature'] = 'Signature is not valid!';    
        }
    } else {
        $errors['whitelist'] = 'IP not in whitelist!';
    }
} else {
    $errors['params'] = 'Missing parameters!';
}
if($_GET['reason'] >= 0) {
    $reason = $_GET['reason'];
} else {
    $reason = 0;
}
if(!isset($reason)) $reason = 0;
if ($type == 2) {
    $cb = 1;
} elseif($type < 2) {
    $cb = 0;
}
$date = date("Y-m-d");
$query = "INSERT INTO [dbo].[web_paymentwall] (dp, type, ref, date, uid, cb, reason) VALUES (".$credits.", ".$type.", '".$refId."', ".$date.", ".$userId.", ".$cb.", ".$reason.")";
$result = odbc_exec($mssql, $query);

if ($result) {
    echo 'OK';
} else {
    echo implode(' ', $errors);
}

//Fix deprecation
//Give Points
odbc_exec($mssql, 'USE [ACCOUNT_DBF]');
$q1 = "SELECT * FROM [dbo].[ACCOUNT_TBL] WHERE uid = ".$userId;
$result1 = odbc_exec($mssql, $q1);
$data1 = odbc_result($result1, 'donate');

$new_data = $data1 + $credits;

$q2 = "UPDATE [dbo].[ACCOUNT_TBL] SET donate = ".$new_data." WHERE uid = ".$userId;
$result2 = odbc_exec($mssql, $q2);



/**  
 *  Signature calculation function
 */
function calculatePingbackSignature($params, $secret, $version) {
    $str = '';
    if ($version == 2) {
        ksort($params);
    }
    foreach ($params as $k=>$v) {
        $str .= "$k=$v";
    }
    $str .= $secret;
    return md5($str);
}