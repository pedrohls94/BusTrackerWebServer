<?php
require 'DatabaseController.php';

$db = new DatabaseController();

if ($_GET['action'] == 'createLine' && $_GET['name'] != null) {
  echo json_encode($db->createLine($_GET['name']));
}
else if ($_GET['action'] == 'createBus') {
  $db->createBus($_GET['lineId'], $_GET['eposId']);
}
else if ($_GET['action'] == 'fetchAllLines') {
  echo json_encode($db->fetchAllLines());
}
else if ($_GET['action'] == 'findLine') {
  echo json_encode($db->findLine($_GET['id']));
}
else if ($_GET['action'] == 'fetchAllLines') {
  echo json_encode($db->fetchAllLines());
}
else if ($_GET['action'] == 'deleteBusStop') {
  $db->deleteStop($_GET['lineId'], $_GET['location']); //TODO
}
else if ($_GET['action'] == 'createBusStop') {
  $db->createStop($_GET['lineId'], $_GET['location']); //TODO
}
else if ($_GET['action'] == 'fetchStopsByLine') {
  $db->fetchStopsByLine($_GET['lineId']); //TODO
}

?>
