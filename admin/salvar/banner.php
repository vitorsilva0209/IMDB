<?php

session_start();

include "../../config.php";

function mensagemBanner($titulo, $texto, $tipo = "success")
{
    echo "
    <!DOCTYPE html>
    <html lang='pt-br'>
    <head>
        <meta charset='UTF-8'>
        <title>$titulo</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>

    <script>
        Swal.fire({
            title: " . json_encode($titulo) . ",
            text: " . json_encode($texto) . ",
            icon: " . json_encode($tipo) . ",
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = '../listar/banner.php';
        });
    </script>

    </body>
    </html>
    ";

    exit;
}

if (isset($_GET["excluir"])) {

    $id = intval($_GET["excluir"]);

    $sql = "SELECT banner FROM banner WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    $stmt->execute();

    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registro) {

        mensagemBanner(
            "Erro",
            "Banner não encontrado.",
            "error"
        );
    }

    $sql = "DELETE FROM banner WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {

        $arquivo = "../../arquivos/banners/" . $registro["banner"];

        if (file_exists($arquivo)) {
            unlink($arquivo);
        }

        mensagemBanner(
            "Sucesso",
            "Banner excluído com sucesso!",
            "success"
        );

    } else {

        mensagemBanner(
            "Erro",
            "Não foi possível excluir o banner.",
            "error"
        );
    }
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../listar/banner.php");

    exit;
}

$id = $_POST["id"] ?? "";

$descricao = trim($_POST["descricao"] ?? "");

$ativo = $_POST["ativo"] ?? "N";

if ($descricao === "") {

    mensagemBanner(
        "Erro",
        "Informe a descrição do banner.",
        "error"
    );
}

if (strlen($descricao) > 100) {

    mensagemBanner(
        "Erro",
        "A descrição deve ter no máximo 100 caracteres.",
        "error"
    );
}

if ($ativo !== "S" && $ativo !== "N") {

    mensagemBanner(
        "Erro",
        "Status inválido.",
        "error"
    );
}

$pasta = "../../arquivos/banners/";

if (!is_dir($pasta)) {
    mkdir($pasta, 0777, true);
}

$extensoesPermitidas = [
    "jpg",
    "jpeg",
    "png",
    "webp"
];

if (!empty($id)) {

    $id = intval($id);

    $sql = "SELECT * FROM banner WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    $stmt->execute();

    $bannerAtual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bannerAtual) {

        mensagemBanner(
            "Erro",
            "Banner não encontrado.",
            "error"
        );
    }

    $temNovaImagem = (
        isset($_FILES["banner"]) &&
        $_FILES["banner"]["error"] === UPLOAD_ERR_OK
    );

    if ($temNovaImagem) {

        $arquivo = $_FILES["banner"];

        $extensao = strtolower(
            pathinfo(
                $arquivo["name"],
                PATHINFO_EXTENSION
            )
        );

        if (!in_array($extensao, $extensoesPermitidas)) {

            mensagemBanner(
                "Erro",
                "Formato de imagem inválido.",
                "error"
            );
        }

        if (getimagesize($arquivo["tmp_name"]) === false) {

            mensagemBanner(
                "Erro",
                "O arquivo enviado não é uma imagem válida.",
                "error"
            );
        }

        $nomeArquivo = uniqid(
            "banner_",
            true
        ) . "." . $extensao;

        $caminhoNovo = $pasta . $nomeArquivo;

        if (!move_uploaded_file(
            $arquivo["tmp_name"],
            $caminhoNovo
        )) {

            mensagemBanner(
                "Erro",
                "Não foi possível salvar a nova imagem.",
                "error"
            );
        }

        $imagemAntiga = $pasta . $bannerAtual["banner"];

        if (
            !empty($bannerAtual["banner"]) &&
            file_exists($imagemAntiga)
        ) {
            unlink($imagemAntiga);
        }

        $sql = "UPDATE banner SET
                    descricao = :descricao,
                    banner = :banner,
                    ativo = :ativo
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":banner", $nomeArquivo);
        $stmt->bindParam(":ativo", $ativo);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    } else {

        $sql = "UPDATE banner SET
                    descricao = :descricao,
                    ativo = :ativo
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":ativo", $ativo);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    }

    if ($stmt->execute()) {

        mensagemBanner(
            "Sucesso",
            "Banner atualizado com sucesso!",
            "success"
        );

    } else {

        mensagemBanner(
            "Erro",
            "Não foi possível atualizar o banner.",
            "error"
        );
    }
}

if (
    !isset($_FILES["banner"]) ||
    $_FILES["banner"]["error"] !== UPLOAD_ERR_OK
) {

    mensagemBanner(
        "Erro",
        "Selecione uma imagem para o banner.",
        "error"
    );
}

$arquivo = $_FILES["banner"];

$extensao = strtolower(
    pathinfo(
        $arquivo["name"],
        PATHINFO_EXTENSION
    )
);

if (!in_array($extensao, $extensoesPermitidas)) {

    mensagemBanner(
        "Erro",
        "Formato de imagem inválido.",
        "error"
    );
}

if (getimagesize($arquivo["tmp_name"]) === false) {

    mensagemBanner(
        "Erro",
        "O arquivo enviado não é uma imagem válida.",
        "error"
    );
}

$nomeArquivo = uniqid(
    "banner_",
    true
) . "." . $extensao;

$caminhoArquivo = $pasta . $nomeArquivo;

if (!move_uploaded_file(
    $arquivo["tmp_name"],
    $caminhoArquivo
)) {

    mensagemBanner(
        "Erro",
        "Não foi possível salvar a imagem.",
        "error"
    );
}

$sql = "INSERT INTO banner
        (descricao, banner, ativo)
        VALUES
        (:descricao, :banner, :ativo)";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(":descricao", $descricao);
$stmt->bindParam(":banner", $nomeArquivo);
$stmt->bindParam(":ativo", $ativo);

if ($stmt->execute()) {

    mensagemBanner(
        "Sucesso",
        "Banner cadastrado com sucesso!",
        "success"
    );

} else {

    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }

    mensagemBanner(
        "Erro",
        "Não foi possível cadastrar o banner.",
        "error"
    );
}