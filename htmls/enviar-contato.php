<?php

declare(strict_types=1);

function redirectToContact(string $status): void
{
    header('Location: contato.html?status=' . rawurlencode($status) . '#contact-form-title');
    exit;
}

function postString(string $key): string
{
    $value = $_POST[$key] ?? '';

    return is_string($value) ? trim($value) : '';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToContact('invalido');
}

if (postString('empresa_site') !== '') {
    redirectToContact('enviado');
}

$name = postString('nome');
$email = postString('email');
$phone = postString('telefone');
$destination = postString('destino');
$message = postString('mensagem');
$privacyAccepted = isset($_POST['lgpd']);

$allowedDestinations = ['Comercial', 'Jurídico', 'Fale conosco'];
$hasHeaderInjection = preg_match('/[\r\n]/', $email) === 1;
$isValid =
    $name !== '' && strlen($name) <= 120 &&
    filter_var($email, FILTER_VALIDATE_EMAIL) !== false &&
    strlen($email) <= 254 &&
    !$hasHeaderInjection &&
    $phone !== '' && strlen($phone) <= 40 &&
    in_array($destination, $allowedDestinations, true) &&
    $message !== '' && strlen($message) <= 5000 &&
    $privacyAccepted;

if (!$isValid) {
    redirectToContact('invalido');
}

$safeName = preg_replace('/[\r\n]+/', ' ', $name) ?? $name;
$safePhone = preg_replace('/[\r\n]+/', ' ', $phone) ?? $phone;
$recipient = 'adv@ramosbenedetti.adv.br';
$subject = 'Novo contato pelo site - ' . $destination;
$encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
$body = implode("\r\n", [
    'Nova mensagem recebida pelo formulário do site.',
    '',
    'Nome: ' . $safeName,
    'E-mail: ' . $email,
    'Telefone: ' . $safePhone,
    'Destino: ' . $destination,
    '',
    'Mensagem:',
    $message,
    '',
    'Consentimento para contato e armazenamento dos dados: Sim',
]);
$headers = implode("\r\n", [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: Site Ramos Benedetti <no-reply@ramosbenedetti.adv.br>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
]);

$sent = mail($recipient, $encodedSubject, $body, $headers);

redirectToContact($sent ? 'enviado' : 'erro');
