<?php 
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load environment variables from .env file
$dotenvPath = __DIR__ . '/.env';
if (file_exists($dotenvPath)) {
    $dotenv = parse_ini_file($dotenvPath);
    foreach ($dotenv as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// RabbitMQ connection parameters
$host = $_ENV['RABBITMQ_HOST'];
$port = $_ENV['RABBITMQ_PORT'];
$user = $_ENV['RABBITMQ_USER'];
$password = $_ENV['RABBITMQ_PASSWORD'];
$queueName = $_ENV['RABBITMQ_QUEUE'];

// Email configuration
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host       = $_ENV['MAIL_HOST'];
$mail->SMTPAuth   = true;
$mail->Username   = $_ENV['MAIL_USERNAME'];
$mail->Password   = $_ENV['MAIL_PASSWORD'];
$mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
$mail->Port       = $_ENV['MAIL_PORT'];
$mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);

try {
    // Create a connection to RabbitMQ
    $connection = new AMQPStreamConnection($host, $port, $user, $password);
    $channel = $connection->channel();

    // Declare a queue
    $channel->queue_declare($queueName, false, true, false, false);

    // Send three email requests asynchronously
    for ($i = 0; $i < 3; $i++) {
        // Email content
        $emailData = [
            'to' => 'mathewsagumbah@gmail.com', // DPO Group
            'subject' => 'Interview Follow-Up',
            'body' => 'Dear DPO Group,
                       I hope this email finds you well. Thank you for the opportunity to interview with DPO Group. I am enthusiastic about the prospect of joining your team and contributing to the company’s success.
                       Looking forward to the next steps in the hiring process.
                       Best regards,
                       Mathews Onyango'
        ];

        // Convert email data to JSON
        $emailMessage = new AMQPMessage(json_encode($emailData), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        // Publish the email message to the queue
        $channel->basic_publish($emailMessage, '', $queueName);

        // Send email using PHPMailer
        $mail->addAddress($emailData['to']);
        $mail->Subject = $emailData['subject'];
        $mail->Body    = $emailData['body'];
        $mail->send();
        $mail->clearAddresses();
    }

    echo 'Email requests have been sent to the message queue and emails have been sent successfully!';

    // Close the channel and the connection
    $channel->close();
    $connection->close();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
