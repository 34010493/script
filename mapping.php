<?php

$timeStart = microtime_float();

$officeRoute = '192.168.18.1';


$ipList = array(
  'data04' => '184.72.214.18',
  'data06' => '54.211.92.136',
  'data01' => '174.129.156.8',
);

$domainList = array(
  'adv.ynxs.io',
);

foreach ($domainList as $domain) {
  exec('dig '.$domain, $result);

  foreach ($result as $v) {
    if ($v == '') {
      continue;
    }
    
    if (preg_match('|^'.$domain.'|ism', $v)) {
      $ss = preg_split("|\t|", $v);
      $ip = array_pop($ss);
      $ipList[$domain] = $ip;
    }
  }
}

$outputRedirect = ' 2>&1';
$i = 0;
foreach ($ipList as $ip) {
  exec('sudo route delete '.$ip.$outputRedirect);
  exec('sudo route add '.$ip.' '.$officeRoute.$outputRedirect);
  $i++;
}

$timeEnd = microtime_float();
$time = $timeEnd - $timeStart;

echo "Updated $i ip in $time seconds.".PHP_EOL;


function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}