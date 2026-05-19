<?php

class Dono {
    private $id;
    private $nome;
    private $fone;

    public function __construct($nome, $fone, $id = null) {
        $this->nome = $nome;
        $this->fone = $fone;
        $this->id = $id;
    }

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getFone() { return $this->fone; }
}

class Animal {
    private $id;
    private $nome_animal;
    private $especie;

    public function __construct($nome_animal, $especie, $id = null) {
        $this->nome_animal = $nome_animal;
        $this->especie = $especie;
        $this->id = $id;
    }

    public function getId() { return $this->id; }

    public function getNomeAnimal() { return $this->nome_animal; }

    public function getEspecie() { return $this->especie; }
}

$host = "localhost";
$porta = "5432";
$database = "petShop";
$usuario = "postgres";
$senha = "postgres";

$dsn = "pgsql:host=$host;port=$porta;dbname=$database";

$conexao = new PDO($dsn, $usuario, $senha);

if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
    if (isset($_POST['salvar_dono'])) {
        $dono = new Dono($_POST['nome'], $_POST['fone']);
        $sql = "INSERT INTO dono(nome, fone) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$dono->getNome(), $dono->getFone()]);
    }

    if (isset($_POST['salvar_animal'])) {
        $animal = new Animal($_POST['nome_animal'], $_POST['especie']);
        $sql = "INSERT INTO animal(nome_animal, especie) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$animal->getNomeAnimal(), $animal->getEspecie()]);
    }
}

$donos = [];
if (isset($_GET['ver_donos'])) 
    {
    $sqlDonos = "SELECT * FROM dono";
    $resultado = $conexao->query($sqlDonos);
    $rows = $resultado->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $donos[] = new Dono($row['nome'], $row['fone'], $row['id']);
    }
}

$animais = [];
if (isset($_GET['ver_animais'])) 
    {
    $sqlAnimais = "SELECT * FROM animal";
    $resultado = $conexao->query($sqlAnimais);
    $rows = $resultado->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $animais[] = new Animal($row['nome_animal'], $row['especie'], $row['id']);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pet Shop</title>
    <style>
        body { 
        font-family: Georgia; 
        margin: 40px; 
        text-align: center;}
        
        .container { 
        display: flex; 
        justify-content: center; 
        gap: 60px;}
        
        .box { 
        width: 400px; 
        border: 2px solid black; 
        padding: 15px; 
        text-align: left;}

        label { 
        display: block; 
        margin-top: 10px;}

        input { 
        width: 100%; 
        border: none; 
        border-bottom: 2px solid black; 
        padding: 6px; 
        margin-bottom: 15px; 
        outline: none; }

        button, .btn-link { 
        width: 100%; 
        padding: 10px; 
        border: 2px solid black; 
        background: none; 
        display: block; 
        cursor: pointer; 
        text-decoration: none; 
        color: black; 
        text-align: center; 
        box-sizing: border-box; 
        margin-top: 5px; }

        button:hover, .btn-link:hover {
        background: #3f95f1;}

        table { 
        width: 100%; 
        margin-top: 15px; 
        border-collapse: collapse; }

        th, td {
         border: 1px solid black; 
         padding: 6px; 
         text-align: center; }

         h1 {
        color: #2a7659;
        font-size: 80px; 
        font-family: Georgia; }

        h2 {
        color: #2b2a76;
        font-size: 30px; 
        font-family: Georgia; }
    </style>
</head>
<body>

    <h1>Pet Shop</h1>

    <div class="container">
        <div class="box">
            <h2>Dono</h2>
            <form method="post">
                <label>Nome</label>
                <input type="text" name="nome" required>
                <label>Celular</label>
                <input type="text" name="fone" required>
                <button type="submit" name="salvar_dono">Cadastrar</button>
            </form>

            <a href="?ver_donos=1" class="btn-link">Lista de Donos</a>

            <?php if (!empty($donos)): ?>

            <table>

                <tr><th>ID</th><th>Nome</th><th>Fone</th></tr>
                <?php foreach ($donos as $d): ?>
                <tr>
                    <td><?= $d->getId() ?></td>
                    <td><?= $d->getNome() ?></td>
                    <td><?= $d->getFone() ?></td>
                </tr>

                <?php endforeach; ?>

            </table>
            <?php endif; ?>
        </div>

        <div class="box">
            <h2>Animal</h2>
            <form method="post">
                <label>Nome</label>
                <input type="text" name="nome_animal" required>
                <label>Espécie</label>
                <input type="text" name="especie" required>
                <button type="submit" name="salvar_animal">Cadastrar</button>
            </form>

         <a href="?ver_animais=1" class="btn-link">Lista de Animais</a>

         <?php if (!empty($animais)): ?>
         <table>
         <tr><th>ID</th><th>Nome</th><th>Espécie</th></tr>
         <?php foreach ($animais as $a): ?>
        <tr>
            <td><?= $a->getId() ?></td>
            <td><?= $a->getNomeAnimal() ?></td>
            <td><?= $a->getEspecie() ?></td>
         </tr>
        <?php endforeach; ?>
      </table>
     <?php endif; ?>
    </div>
</div>

</body>

</html>
