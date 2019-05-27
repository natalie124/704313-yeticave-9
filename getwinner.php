<?php

require_once('init.php');
require_once('helpers.php');
require_once('functions.php');
require_once('vendor/autoload.php');

$sql_lots = "SELECT id, title, dt_end, win_id FROM lots AS l WHERE NOW() >= dt_end AND win_id IS NULl";
$closed_lots = get_rows_from_mysql($con, $sql_lots);

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

if (empty($closed_lots)) {
    die();
}

foreach ($closed_lots as $lot) {
    $lot_id = (int)$lot['id'];
    $lot_title = $lot['title'];

    $sql_max_bet = "SELECT b.id, b.bet_price, b.user_id, b.lot_id, u.name, u.email FROM bets AS b
                    JOIN users AS u ON b.user_id = u.id
                    WHERE b.lot_id = $lot_id
                    ORDER BY b.bet_price DESC LIMIT 1";
    $max_bet = get_row_from_mysql($con, $sql_max_bet);
    $user = $max_bet['name'];
    $email = $max_bet['email'];

    if (!empty($max_bet)) {

        $sql_update = "UPDATE lots SET win_id = (?) WHERE id = (?)";
        $stmt = db_get_prepare_stmt($con, $sql_update, [$max_bet['user_id'], $lot['id']]);
        $res = mysqli_stmt_execute($stmt);

        $mailer = new Swift_Mailer($transport);
        $logger = new Swift_Plugins_Loggers_ArrayLogger();
        $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
        $message->setTo([$email]);

        $msg_content = include_template('email.php', [
            'closed_lots' => $closed_lots,
            'lot_id' => $lot_id,
            'lot_title' => $lot_title,
            'user' => $user
        ]);

        $message->setBody($msg_content, 'text/html');

        $result = $mailer->send($message);

        if ($result) {

            print("Рассылка успешно отправлена");

        } else {

            print("Не удалось отправить рассылку: " . $logger->dump());

        }
    }
}
