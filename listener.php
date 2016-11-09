<?php
error_reporting(E_ALL & ~E_NOTICE);

include 'config.inc.php';

//Confirm this is still correct.
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


function givePoints()
{
    $getPoints = new DBConnection();
    $getPoints->query("SELECT dPoints FROM users WHERE uid = :uid");
    $getPoints->bind(':uid', $userId);
    $row = $getPoints->single();

    $currentDPoints = $row['dPoints'];

    $newDPoints = $current_dpoints + $credits;

    $upatePoints = new DBConnection();
    $updatePoints->query("UPDATE users SET dPoints = :newDP WHERE uid = :uid");
    $updatePoints->bind(':newDP', $newDPoints);
    $updatePoints->bind(':uid', $userId);
    $updatePoints->execute();
}


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

//Give Points
givePoints();



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