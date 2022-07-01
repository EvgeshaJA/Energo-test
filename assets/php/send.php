<?php
// Файлы phpmailer
require './PHPMailer.php';
require './SMTP.php';
require './Exception.php';

// Переменные, которые отправляет пользователь, они равны инпутам из формы
$name = $_POST['name'];
$phone = $_POST['phone'];
$messege = $_POST['messege'];
$email = $_POST['email'];


// Формирование самого письма
$title = "Заголовок письма";
$body = "
<h2>Новое письмо</h2>
<b>Имя:</b> $name<br>
<b>Имя:</b> $phone<br>
<b>Почта:</b> $email<br><br>
<b>Вопрос:</b> $messege<br>

";

// Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    // Настройки вашей почты
    $mail->Host       = 'smtp.mail.ru'; // SMTP сервера вашей почты
    $mail->Username   = 'atmosferavoda@mail.ru'; // Логин на почте
    $mail->Password   = 'password'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->setFrom('atmosferavoda@mail.ru', 'Заявка'); // Адрес самой почты (сайта) и имя отправителя

    // Получатель письма
    $mail->addAddress('atmosferavoda@mail.ru'); //Ваш email


// Прикрипление файлов к письму
if (!empty($_FILES['userfile']['name'][0])) {
    for ($ct = 0; $ct < count($_FILES['userfile']['tmp_name']); $ct++) {
        $uploadfile = tempnam(sys_get_temp_dir(), sha1($_FILES['userfile']['name'][$ct]));
        $filename = $_FILES['userfile']['name'][$ct];
        if (move_uploaded_file($_FILES['userfile']['tmp_name'][$ct], $uploadfile)) {
            $mail->addAttachment($uploadfile, $filename);
        } else {
            $msg .= 'Не удалось прикрепить файл ' . $uploadfile;
        }
    }   
}


    // Отправка сообщения
$mail->isHTML(true);
$mail->Subject = $title;
$mail->Body = $body;

// Проверяем отравленность сообщения
if ($mail->send()) {$result = "success";}
else {
echo 'error'
$result = "error";
}

} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}

// Отображение результата
echo json_encode(["result" => $result, "resultfile" => $rfile, "status" => $status]);

?>