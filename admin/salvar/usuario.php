<?php
if (!isset($pagina)) exit;

if ($_POST){
    //recuperar os dados digitados
    $id = htmlspecialchars(trim($_POST["id"] ?? NULL));
    $nome = htmlspecialchars(trim($_POST["nome"] ?? NULL));
    $email = htmlspecialchars(trim($_POST["email"] ?? NULL));
    $senha = htmlspecialchars(trim($_POST["senha"] ?? NULL));
    $datanascimento = htmlspecialchars(trim($_POST["datanascimento"] ?? NULL));
    $salario = htmlspecialchars(trim($_POST["salario"] ?? NULL));
    $cpf = htmlspecialchars(trim($_POST["cpf"] ?? NULL));
    $ativo = htmlspecialchars(trim($_POST["ativo"] ?? NULL));


    //validar os dados
    if (empty($nome)) {
        mensagem("Erro", "Preencha o nome do usuário", "error");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        mensagem("Erro", " Digite um E-mail inválido", "error");
    } else if (empty($id) and empty($senha)) {
        mensagem("Erro", "Preencha a senha do usuário", "error");
    } else if (!empty($senha) and strlen($senha) < 6) {
        mensagem("Erro", "A senha deve ter no mínimo 6 caracteres", "error");

        //23/09/2010 - 2010-09-23
    } else if (!empty($datanascimento)) {
        $data = explode("/", $datanascimento);
        if (!checkdate($data[1], $data[0], $data[2])) {
            mensagem("Erro", "Data de nascimento inválida", "error");
        }

        //salario 7.000,00 -> 7000.00
    } else if (!empty($salario)) {
        $salario = str_replace(".", "", $salario);
        $salario = str_replace(",", ".", $salario);
        if (!is_numeric($salario)) {
            mensagem("Erro", "Salário inválido", "error");
        }
        //Validar ativo
        $ativo = ($ativo == "1") ? 1 : 0;
    }


    //inserir - atualizar sem senha - atualizar com senha
    if (empty($id)) {
        //inserir os dados no banco
        $senha = password_hash($senha, PASSWORD_BCRYPT);
    } else if (!empty($senha)) {
        //atualizar com senha
        $senha = password_hash($senha, PASSWORD_BCRYPT);
    } else {
        //atualizar sem senha
        $senha = NULL;
    }
        
        $sql = "UPDATE usuario set
            nome = :nome,
            email = :email,
            senha = coalesce(:senha, senha),
            datanascimento = :datanascimento,
            salario = :salario,
            cpf = :cpf,
            ativo = :ativo
            where id = :id ";

            $consulta = $pdo->prepare($sql);
            $consulta->bindParam(":senha", $senha);
            $consulta->bindParam(":nome", $nome);
            $consulta->bindParam(":email", $email);
            $consulta->bindParam(":datanascimento", $datanascimento);
            $consulta->bindParam(":salario", $salario);
            $consulta->bindParam(":cpf", $cpf);
            $consulta->bindParam(":ativo", $ativo);
            $consulta->bindParam(":id", $id);

        $consulta->execute();
        mensagem("Sucesso", "Registro salvo com sucesso", "success");

    
} else {
    mensagem("Erro", "Requisição inválida", "error");
}