<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$nom = isset($_POST['Nom']) ? trim($_POST['Nom']) : '';
$email = isset($_POST['Email']) ? trim($_POST['Email']) : '';
$telephone = isset($_POST['T-l-phone']) ? trim($_POST['T-l-phone']) : '';
$message = isset($_POST['Message']) ? trim($_POST['Message']) : '';
$checkbox = isset($_POST['Checkbox']) ? 'Oui' : 'Non';

if (empty($nom) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
    exit;
}

$to = 'julien@vert-menthe.fr';
$subject = 'Nouveau message depuis le formulaire de contact - Vert Menthe';

$emailContent = "
Nouveau message depuis le formulaire de contact

==========================================
INFORMATIONS DU VISITEUR
==========================================

Nom/Société: $nom
Email: $email
Téléphone: " . ($telephone ? $telephone : 'Non fourni') . "

Message:
----------
$message
----------

Consentement RGPD: $checkbox
Date: " . date('d/m/Y à H:i:s') . "
";

$headers = "From: noreply@vert-menthe.fr\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($to, $subject, $emailContent, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Votre message a été envoyé avec succès ! Je vous répondrai sous 24h.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi du message. Veuillez réessayer ou me contacter directement par email.']);
}
