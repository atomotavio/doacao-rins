<?php

class Pessoa
{
    public $nome;
    public $genero;
    public $idade;
    public $tipoSanguineo;
    public $peso;
    public $altura;

    function __construct($nome, $genero, $idade, $tipoSanguineo, $peso, $altura)
    {
        $this->nome = $nome;
        $this->genero = $genero;
        $this->idade = $idade;
        $this->tipoSanguineo = $tipoSanguineo;
        $this->peso = $peso;
        $this->altura = $altura;
    }

    function calcularIMC()
    {
        $alturaMetros = $this->altura / 100; // Convertendo altura para metros
        return $this->peso / ($alturaMetros * $alturaMetros);
    }

    function podeDoarRins()
    {
        if ($this->idade < 18) {
            return false;
        }

        if ($this->tipoSanguineo !== "A+") {
            return false;
        }

        $imc = $this->calcularIMC();
        if ($imc < 18.5 || $imc >= 30) { // Abaixo do peso ou obeso
            return false;
        }

        return true;
    }

    function __toString()
    {
        return "Nome: " . $this->nome . "\n" .
            "Gênero: " . $this->genero . "\n" .
            "Idade: " . $this->idade . "\n" .
            "Tipo Sanguíneo: " . $this->tipoSanguineo . "\n" .
            "Peso: " . $this->peso . "kg\n" .
            "Altura: " . $this->altura . "cm\n";
    }
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nomes = $_POST["nome"];
    $generos = $_POST["genero"];
    $idades = $_POST["idade"];
    $tiposSanguineos = $_POST["tipo_sanguineo"];
    $pesos = $_POST["peso"];
    $alturas = $_POST["altura"];

    $candidatos = array();

    // Itera sobre os dados recebidos e cria objetos Pessoa para cada candidato
    for ($i = 0; $i < count($nomes); $i++) {
        $candidato = new Pessoa(
            $nomes[$i],
            $generos[$i],
            $idades[$i],
            $tiposSanguineos[$i],
            $pesos[$i],
            $alturas[$i]
        );

        $candidatos[] = $candidato;
    }
} else {
    header("Location: formulario.html");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Resultado do Processamento</title>
</head>

<body>
    <h1>Resultado do Processamento</h1>
    <?php
    foreach ($candidatos as $candidato) {
        echo "<p>Informações do Candidato:</p>";
        echo $candidato;

        if ($candidato->podeDoarRins()) {
            echo "<p>Aprovado para o procedimento.</p>";
        } else {
            echo "<p>Reprovado para o procedimento.</p>";
            echo "<p>Motivo da Reprovação: ";
            if ($candidato->idade < 18) {
                echo "Menor de idade.</p>";
            } elseif ($candidato->tipoSanguineo !== "A+") {
                echo "Tipo sanguineo incompativel.</p>";
            } else {
                $imc = $candidato->calcularIMC();
                if ($imc < 18.5) {
                    echo "Abaixo do peso.</p>";
                } else {
                    echo "Obeso.</p>";
                }
            }
        }

        echo "<hr>";
    }
    ?>
</body>

</html>