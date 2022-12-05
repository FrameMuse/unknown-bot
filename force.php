<?php

$hn = "jora13y6.beget.tech";
$un = "jora13y6_eup";
$pw = "3EuvV*nS";
$bn = "jora13y6_eup";

$conn = new mysqli($hn, $un, $pw, $bn);
    $result = $conn->query("SELECT * FROM payments WHERE test = '4'");

$date = date("Y-m-d");

while ($row = $result->fetch_assoc()) {
    $chs = $row['choice'];
    if ($chs == "КиберСпорт") $chnl = "1172111578"; else $chnl = "1395872617";
    $starts = new DateTime($date);
    $ends = $starts->modify("+{$row['Name']} days")->format('Y-m-d');
    
    print "OK\r\n\r\n";
    
    if ($conn->query("INSERT INTO for_add (order_id, user_id, phone, channel, starts, ends, status) values ('{$row['OrderId']}', '{$row['tgi']}', '{$row['tg']}', '$chnl', '$date', '$ends', '600')")) {
        #$conn->query("UPDATE payments SET test = '4' WHERE id = '{$row['id']}'");
        print "OK\r\n\r\n";
    } else {
        print $conn->error;
    }
}

$result->close();
$conn->close();

?>