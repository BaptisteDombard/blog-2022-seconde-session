<?php
if (isset($_GET['api_token'])){
    header('Content-Type: application/json');
    echo json_encode($data['posts']);
}
