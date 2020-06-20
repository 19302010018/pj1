<?php
require_once ('config.php');
//根据js传入的City，获取对应的城市
$db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
$country = $_GET['country'];
$sql = 'SELECT GeoNameID,AsciiName FROM geocities WHERE CountryCodeISO = "'.$country.'"';
$result = $db -> prepare($sql);
$result -> execute();
for ($i = 1; $i <= $result->rowCount(); $i++) {
    $row = $result->fetch();
    if (isset($row['GeoNameID'])) {
        echo $row['GeoNameID'] . '&' . $row['AsciiName'];
    } else echo 'null';
    if ($i != $result->rowCount()) echo '|';
}
?>