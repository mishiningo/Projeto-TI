<!-- Verificar se o utilizador possui as devidas permissões para aceder a página - > TODO
Mensagem de criação bem sucedida -> TODO
-->
<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfis de MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="estiloLogin.css">
  </head>
  <body>
    <!-- Criação da navbar -> TODO -->
      <div class="container-sm">
        <div class ="myHomeStyle d-flex justify-content-center mt-3">
            <h2>Gestor de Perfis</h2>
        </div>
        <br>
        <br>
        <div class="row justify-content-evenly">
            <div class="col-md-4 mb-5">
                <div class="card text-center turquesa">
                    <div class = "card-header">
                        <b>Lista de perfis</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped-columns align-middle gelo rounded-table">
                            <thead>
                                <th>
                                    Perfis
                                </th>
                                <th>
                                    Nível
                                </th>
                            </thead>
                            <tbody>
                            <?php
                                // Função que devolve um array de todos os ficheiros com o seguinte caminho
                                // * -> Linux style
                                $ficheiros = glob("API/files/utilizadores/niveis/*.txt");
                                //Devolve falso em caso de erro
                                if ($ficheiros == false){
                                    echo "Houve um erro ao carregar os utilizadores";
                                } 
                                //Estrutura for each pra percorrer vetor $ficheiros
                                //Ou seja, para cada elemento em ficheiros teremos uma var caminho para ser utilizada dentro do for
                                foreach($ficheiros as $caminho){
                                    $nomeFicheiro = basename($caminho); //Basename exclui o caminho complet, devolvendo somente o nome do ficheiro
                                    //Ex: API/files/niveis/Leo.txt -> Leo.txt

                                    //Substr é uma função para criar uma sbstring com base nos parametros passados
                                    //Neste caso o $nomeFicheiro é a base, 0 é onde deve-se começar a string (caminho[0]), e o -strlen(".txt") é onde ela deve acabar
                                    //Ou seja, -4 iterações ( - ".txt") do fim, excluindo assim a extensão do ficheiro
                                    //Resultando na string username sem a extensão do ficheiro 
                                    $username = substr($nomeFicheiro, 0, -strlen(".txt"));
                                    $nivel = file_get_contents($caminho);

                                    //Agora com as variáveis definidas realiza-se com echo com a construção da tabela
                                    //htmlspecialchars transforma chars especiais no html (", <, >, &) em seus devidos escapes para serem lidos da forma correta
                                    //Neste caso não era explicitamente necessário, mas é boa pratica 
                                    echo "<tr>
                                            <td>" . htmlspecialchars($username) . "</td>
                                            <td>" . htmlspecialchars($nivel) . "</td>
                                            </tr>"; 
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center turquesa">
                    <div class="card-header">
                        <b>Criar novo usuário ou alterar passe de usuário existente</b> 
                    </div>
                    <div class="card-body">
                        <form method="post" action="API/userAPI.php">
                            <div class="mb-3 gelo rounded-border">
                                <label for="inputUsername" class="form-label">Utilizador</label>
                                <input type="text" class="inputs form-control" id="inputUsername" aria-describedby="emailHelp" placeholder="Seu nome de utilizador" name="username" required>
                            </div>
                            <div class="mb-3 gelo rounded-border">
                                <label for="inputPassword" class="form-label">Palavra-passe</label>
                                <input type="password" class="inputs form-control" id="inputPassword" placeholder="Sua palavra-passe" name="password" required>
                            </div>
                            <div class="gelo rounded-border">
                                <label for="selectNivel" class="form-label">Nivel de acesso</label>
                                <br>
                                <div class="inputs py-1">
                                    <select id="selectNivel" name="nivel">
                                        <option value="visitante" selected>Visitante</option>
                                        <option value="morador">Morador</option>
                                        <option value="admin">Administrador</option>
                                    </select>
                                </div>
                            </div>
                    </div>                        
                    <div class="card-footer">
                        <div class="container-button gelo rounded-border py-1">
                            <button type="submit" class="btn btn-myhome">Enviar</button>    
                        </div>
                    </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>