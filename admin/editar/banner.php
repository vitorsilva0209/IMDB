<?php

session_start();

include "../../config.php";
include "../functions.php";

$sql = "SELECT * FROM banner ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Banner</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-dark text-white">

            <h3>Editar Banner</h3>

        </div>

        <div class="card-body">

            <form
                action="../salvar/banner.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= $banners["id"] ?>"
                >

                <div class="mb-3">

                    <label class="form-label">
                        Descrição
                    </label>

                    <input
                        type="text"
                        name="descricao"
                        class="form-control"
                        maxlength="100"
                        value="<?= htmlspecialchars($banners["descricao"]) ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Imagem atual
                    </label>

                    <br>

                    <img
                        src="../../arquivos/banners/<?= htmlspecialchars($banners["banner"]) ?>"
                        width="400"
                        class="img-thumbnail"
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Trocar imagem
                    </label>

                    <input
                        type="file"
                        name="banner"
                        class="form-control"
                        accept="image/*"
                    >

                    <small class="text-muted">
                        Deixe vazio para manter a imagem atual.
                    </small>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Ativo
                    </label>

                    <select
                        name="ativo"
                        class="form-select"
                    >

                        <option
                            value="S"
                            <?= $banners["ativo"] === "S" ? "selected" : "" ?>
                        >
                            Sim
                        </option>

                        <option
                            value="N"
                            <?= $banners["ativo"] === "N" ? "selected" : "" ?>
                        >
                            Não
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-success"
                >
                    Atualizar
                </button>

                <a
                    href="../listar/banner.php"
                    class="btn btn-secondary"
                >
                    Voltar
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>