<?php
    require_once 'connection.php';

    // Recebe os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $id = $_POST['id'] ?? null; // O ID pode estar presente para edição ou exclusão
    $acao = $_POST['acao'] ?? '';

    switch ($acao) {
        case '1':
            try {
                $stmt = $pdo->prepare("INSERT INTO sua_tabela (nome, email) VALUES (:nome, :email)");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                echo "Registro inserido com sucesso!";
            } catch (PDOException $e) {
                echo "Erro ao inserir registro: " . $e->getMessage();
            } finally {
                $pdo = null; // Adicionando a desconexão explícita
            }
            break;

        case '2':
            if ($id) {
                try {
                    $stmt = $pdo->prepare("UPDATE sua_tabela SET nome = :nome, email = :email WHERE id = :id");
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    echo "Registro atualizado com sucesso!";
                } catch (PDOException $e) {
                    echo "Erro ao atualizar registro: " . $e->getMessage();
                } finally {
                    $pdo = null; // Adicionando a desconexão explícita
                }
            } else {
                echo "ID do registro para edição não fornecido.";
                $pdo = null; // Mesmo em caso de erro, você pode querer "desconectar" (embora não haja uma conexão ativa aqui)
            }
            break;

        case '3':
            if ($id) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM sua_tabela WHERE id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    echo "Registro excluído com sucesso!";
                } catch (PDOException $e) {
                    echo "Erro ao excluir registro: " . $e->getMessage();
                } finally {
                    $pdo = null; // Adicionando a desconexão explícita
                }
            } else {
                echo "ID do registro para exclusão não fornecido.";
                $pdo = null; // Mesmo em caso de erro...
            }
            break;

        default:
            echo "Ação inválida!";
            // Não há conexão para desconectar aqui, mas por consistência, você poderia adicionar $pdo = null;
    }

    // Não precisamos mais do $pdo = null; aqui no final do script, pois já foi adicionado em cada ação.
?>