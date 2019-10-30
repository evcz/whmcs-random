<?PHP
require('../configuration.php');

// connect and select db
$db = new mysqli($db_host, $db_username, $db_password, $db_name);

// cleanup anonymous closed tickets older then 4 months (and all their replies)
// within whmcs we are deleting attachments older then 3 months already
$query = "DELETE tbltickets, tblticketreplies FROM tbltickets RIGHT OUTER JOIN tblticketreplies ON tbltickets.id = tblticketreplies.tid WHERE tbltickets.userid = '0' AND tbltickets.status = 'Closed' AND tbltickets.date <  DATE_SUB(NOW(), INTERVAL 4 MONTH)";
$result = $db->query($query);

// cleanup orphaned ticket replies
$query = "DELETE tblticketreplies FROM tbltickets RIGHT OUTER JOIN tblticketreplies ON tbltickets.id = tblticketreplies.tid WHERE tbltickets.id IS NULL";
$result = $db->query($query);

// cleaup anonymous tickets leftovers (the ones without replies)
$query = "DELETE FROM tbltickets WHERE tbltickets.userid = '0' AND tbltickets.status = 'Closed' AND tbltickets.date <  DATE_SUB(NOW(), INTERVAL 4 MONTH)";
$result = $db->query($query);

// cleanup notes related to deleted tickets
$query = "DELETE tblticketnotes FROM tbltickets RIGHT OUTER JOIN tblticketnotes ON tbltickets.id = tblticketnotes.ticketid WHERE tbltickets.id IS NULL";
$result = $db->query($query);

echo "done";

// disconnect
mysqli_close($db);

?>