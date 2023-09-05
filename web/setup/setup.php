<?php

session_start();

$setupPassword = 'cheekybert1';
$postPassword = isset($_POST['password']) ? $_POST['password'] : '';

if ($setupPassword == $postPassword) {
    $_SESSION['setup'] = [];
    $_SESSION['setup']['status'] = ['active'];
}


if (isset($_POST['done-setup'])) {
    unset($_SESSION['setup']);
}

$setupActive = isset($_SESSION['setup']['status']) && isset($_SESSION['setup']['status']) == 'active';

?>

<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>
    <link rel="stylesheet" href="/setup/setup.css">
</head>
<body>
    <div class="center-div">
        <div class="form-wrap">
            <?php
            if (isset($_POST['start-setup']) || isset($_SESSION['setup']['process_array'])) {
            ?>
                <div id="result-div">
                    <?php
                    setup();
                    ?>
                </div>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                    <input name="done-setup" type="hidden">
                    <button type="submit">Done</button>
                </form>
            <?php
            }
            ?>


            <?php if ($setupActive) { ?>
                <?php if (!isset($_SESSION['setup']['process_array'])) { ?>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="start-setup">
                        <input type="hidden" name="start-setup" value="1">
                        <button type="submit">Start setup</button>
                    </form>
                <?php } ?>
            <?php } else { ?>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="setup-password">
                    <input name="password" type="password" placeholder="Enter setup password">
                    <button type="submit">Submit</button>
                </form>
            <?php } ?>


        </div>
    </div>
</body>
</html>


<?php

function setup() {
    if (!isset($_POST['start-setup'])) {
        printProcess();
        return;
    }

    addProcess('', 'Starting Setup');

    dbSetup();
    adminSetup();

    addProcess('', 'Finish Setup');

    printProcess();
}

function dbConnect($hasDb = true) {
    $host = 'localhost';
    $dbname = 'test2';
    $username = 'root';
    $password = '';
    $databaseName = $hasDb == true ? 'dbname=' . $dbname : '';
    $conn = new PDO('mysql:host=' . $host . ';' . $databaseName, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

function dbSetup() {
    $sql = 'CREATE database IF NOT EXISTS `test2`';

    addProcess('running', 'Creating `test2` database', $sql);
    dbProcess($sql, false);
}

function adminSetup() {
    $sql = 'CREATE table IF NOT EXISTS `admin` (
                `admin_id` int NOT NULL AUTO_INCREMENT,
                `username` varchar(100) NOT NULL,
                `password` varchar(100) NOT NULL,
                PRIMARY KEY (`admin_id`)
            )';

    addProcess('running','Creating admin table', $sql);
    dbProcess($sql);

    insertDefaultAdmin();
}

function checkDefaultAdmin() {
    $sql = 'SELECT `admin_id` FROM `admin` WHERE `admin_id` = 1';

    addProcess('running', 'Checking if default admin exists', $sql);
    return dbProcess($sql)->fetchAll();
}

function insertDefaultAdmin() {
    if (!empty(checkDefaultAdmin())) {
        addProcess('Default admin exists');
        return;
    }

    addProcess('Default admin not found');
    $sql = 'INSERT INTO `admin` SET `username` = "admin", ';
    $password = password_hash('admin', PASSWORD_BCRYPT, ['cost' => 12]);
    $sql .= '`password` = "' . $password . '"';

    addProcess('running', 'Creating default admin', $sql);
    dbProcess($sql);
}

function stopSetup() {
    addProcess('Stopping setup', 'stop');
    exit;
}

function dbProcess($sql, $hasDb = true) {
    $conn = dbConnect($hasDb);

    if (stripos($sql, strtolower('select')) !== false) {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    if ($conn->exec($sql) === false) {
        addProcess('failed');
        stopSetup();
    }
    $conn = null;
    addProcess('success');
    echo "\n\n";
}


function addProcess($status = '', $title = '', $msg = '') {
    $dataArray['title'] = $title;
    $dataArray['msg'] = $msg;
    $dataArray['status'] = $status;
    $_SESSION['setup']['process_array'][] = $dataArray;

    if (!empty('status') && !empty('title')) {
        $msgContent = !empty($msg) ? '' : 'RESULT: ' . strtoupper($status);
        if (empty($msgContent)) {
            $msgContent = 'PROCESS: ' . $title . PHP_EOL . strtoupper($status) . ': ' . $msg;
        }
        if (!empty($status)) {
            logProcess($msgContent);
        }
    }
}

function printProcess() {
    echo '<table class="result-table">';

    foreach ($_SESSION['setup']['process_array'] as $item) {
        if (!empty($title = $item['title'])) {
            $titleLbl = 'Process';
            echo '<tr>
                    <td class="td-process">' . $titleLbl . '</td>
                    <td>' . $item['title'] . '</td>
                  </tr>';
        }

        $statContent = !empty($item['msg']) ? ucfirst($item['status']) : '';
        $msgContent = $item['msg'] ?: strtoupper($item['status']);
        $mrgnClass = empty($item['msg']) ? 'extra-height' : $item['msg'];

        $resultClass = '';
        if ('failed' == strtolower($msgContent)) {
            $resultClass  = 'failed';
        } elseif ('success' == strtolower($msgContent)) {
            $resultClass  = 'success';
        }

        echo '<tr>
                <td class="td-status">' . $statContent . '</td>
                <td class="td-msg ' . $mrgnClass . ' ' . $resultClass .'">' . $msgContent . '</td>
              </tr>';
    }

    echo '</table>';
}

function logProcess($msg) {
    $tz = 'Asia/Manila';
    $dt = new \DateTime("now", new \DateTimeZone($tz));

    $timestamp = time();
    $dt->setTimestamp($timestamp);
    $dateNow = $dt->format('Y-m-d');
    $timeNow = $dt->format('H:i:s');

    $msg = $dateNow . ' ' . $timeNow . ': ' . "\n" . $msg . "\n\n";
    file_put_contents('setup.log', $msg, FILE_APPEND);
}