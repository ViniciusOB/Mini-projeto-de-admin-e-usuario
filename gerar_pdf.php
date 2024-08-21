<?php
require 'vendor/autoload.php'; // Caminho para o arquivo de autoload do Dompdf
include('conexao.php');

use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);

    $sql = "SELECT * FROM informacoes";
    $result = $conn->query($sql);

    $html = '<h2>Relatório</h2><table border="1"><tr><th>id</th><th>titulo</th><th>descricao</th></tr>';

    while ($row = $result->fetch()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['titulo'] . '</td>';
        $html .= '<td>' . $row['descricao'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('relatorio.pdf', array('Attachment' => 0));
}
?>

