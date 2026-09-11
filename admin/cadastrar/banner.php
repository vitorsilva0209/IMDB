<?php

session_start();

include "../../config.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Banner - IMDB</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-dark text-white">

            <h3 class="mb-0">
                Cadastrar Banner
            </h3>

        </div>

        <div class="card-body">

            <form
                action="../salvar/banner.php"
                method="POST"
                enctype="multipart/form-data"
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
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Imagem do Banner
                    </label>

                    <input
                        type="file"
                        name="banner"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="ativo"
                        class="form-select"
                        required
                    >

                        <option value="S">
                            Ativo
                        </option>

                        <option value="N">
                            Inativo
                        </option>

                    </select>

                </div>

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Cadastrar Banner
                    </button>

                    <a
                        href="../listar/banner.php"
                        class="btn btn-secondary"
                    >
                        Voltar
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>

</html>