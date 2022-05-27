<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once 'vendor/autoload.php';
require_once 'init.php';

$date = date('Y-m-d');
$users = get_users_for_mailing($con, $date);

if (!$users) {
    exit();
}

$dsn = 'smtp://user:pass@smtp.example.com:25';
$transport = Transport::fromDsn($dsn);

foreach ($users as $user) {
    $message = new Email();
    $message->to($user['email']);
    $message->from("keks@phpdemo.ru");
    $message->subject("Уведомление от сервиса «Дела в порядке»");

    $tasks = '';

    foreach ($user['tasks'] as $task) {
        $date = strtotime($task['deadline']);
        $tasks .= $task['name'] . ' на ' . date('d', $date) . ' ' . date('M', $date) . '<br>';
    }

    if (count($user['tasks']) === 1) {
        $text = 'У вас запланирована задача ' . $tasks;
    } else {
        $text = 'У вас запланированы задачи ' . $tasks;
    }

    $message->text(' Уважаемый, ' . $user['login'] . '.<br>' . $text);

    $mailer = new Mailer($transport);
    $mailer->send($message);
}
