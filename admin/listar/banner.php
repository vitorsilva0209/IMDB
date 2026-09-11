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

    <title>Lista de Banners - IMDB</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Cadastro de Banners</h2>

        <a
            href="../cadastrar/banner.php"
            class="btn btn-primary"
        >
            Novo Banner
        </a>

    </div>

    <div class="card shadow">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Descrição</th>
                            <th>Ativo</th>
                            <th>Ações</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (count($banners) > 0): ?>

                        <?php foreach ($banners as $banner): ?>

                            <tr>

                                <td>
                                    <?= $banner["id"] ?>
                                </td>

                                <td>

                                    <img
                                        src="../../arquivos/banners/<?= htmlspecialchars($banner["banner"]) ?>"
                                        width="200"
                                        class="img-thumbnail"
                                        alt="<?= htmlspecialchars($banner["descricao"]) ?>"
                                    >

                                </td>

                                <td>
                                    <?= htmlspecialchars($banner["descricao"]) ?>
                                </td>

                                <td>

                                    <?php if ($banner["ativo"] === "S"): ?>

                                        <span class="badge bg-success">
                                            Ativo
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">
                                            Inativo
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <a
                                        href="../editar/banner.php?id=<?= $banner["id"] ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Editar
                                    </a>

                                    <a
                                        href="../salvar/banner.php?excluir=<?= $banner["id"] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Deseja realmente excluir este banner?')"
                                    >
                                        Excluir
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="5" class="text-center">

                                Nenhum banner cadastrado.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>

</html>