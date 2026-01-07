<?php
// Configurações do cabeçalho HTTP para retornar JSON
header('Content-Type: application/json');
// Permite requisições de outras origens (importante para desenvolvimento local)
header('Access-Control-Allow-Origin: *'); 

// ----------------------------------------------------
// 1. CONFIGURAÇÃO - ALtere APENAS estas linhas
// ----------------------------------------------------
$recipient_email = 'comercial@metalotech.com'; // O seu e-mail para onde as mensagens serão enviadas
$recipient_name = 'Metalotech - Contato';    // Nome que aparecerá como destinatário
$subject_prefix = 'Novo Pedido de Orçamento'; // Prefixo do assunto do e-mail
// ----------------------------------------------------

$response = [];

// ----------------------------------------------------
// 2. VALIDAÇÃO E SANITIZAÇÃO
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['success'] = false;
    $response['message'] = 'Método de requisição inválido. Use POST.';
    echo json_encode($response);
    exit;
}

// Verifica se os campos obrigatórios estão presentes
if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['telefone']) || empty($_POST['mensagem'])) {
    $response['success'] = false;
    $response['message'] = 'Por favor, preencha todos os campos obrigatórios.';
    echo json_encode($response);
    exit;
}

// Sanitiza os dados
$nome = htmlspecialchars(trim($_POST['nome']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
//$servico = htmlspecialchars(trim($_POST['servico']));
$mensagem = htmlspecialchars(trim($_POST['mensagem']));
$telefone = isset($_POST['telefone']) ? htmlspecialchars(trim($_POST['telefone'])) : 'Não Fornecido';

// Validação de e-mail mais robusta
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['success'] = false;
    $response['message'] = 'O endereço de e-mail é inválido.';
    echo json_encode($response);
    exit;
}

// ----------------------------------------------------
// 3. CONSTRUÇÃO DO E-MAIL
// ----------------------------------------------------

// Corpo do e-mail em texto puro e HTML
$email_subject = $subject_prefix . ' - ' . $nome;

// Cria o corpo da mensagem com os dados formatados
$email_body = "
    <h2>Detalhes do Pedido de Orçamento</h2>
    <table border='1' cellpadding='10' cellspacing='0' width='100%'>
        <tr>
            <td style='background-color: #f2f2f2;'><strong>Nome Completo:</strong></td>
            <td>{$nome}</td>
        </tr>
        <tr>
            <td style='background-color: #f2f2f2;'><strong>E-mail:</strong></td>
            <td>{$email}</td>
        </tr>
        <tr>
            <td style='background-color: #f2f2f2;'><strong>Telefone:</strong></td>
            <td>{$telefone}</td>
        </tr>
    </table>
    
    <h3>Mensagem:</h3>
    <p style='white-space: pre-wrap; padding: 15px; background-color: #f8f8f8;'>{$mensagem}</p>
    
    <hr>
    <p>Esta mensagem foi enviada pelo formulário de contato do seu website.</p>
";

// Cabeçalhos do e-mail
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: {$recipient_name} <{$recipient_email}>" . "\r\n";
$headers .= "Reply-To: {$email}" . "\r\n"; // Para que você possa responder diretamente ao cliente

// ----------------------------------------------------
// 4. ENVIO DO E-MAIL
// ----------------------------------------------------
if (mail($recipient_email, $email_subject, $email_body, $headers)) {
    $response['success'] = true;
    $response['message'] = 'Mensagem enviada com sucesso!';
} else {
    // Falha no envio pelo servidor (pode ser problema de configuração do servidor)
    $response['success'] = false;
    $response['message'] = 'Erro interno do servidor ao enviar o e-mail.';
}

// Retorna a resposta ao JavaScript
echo json_encode($response);

?>