<!-- Cabecera con logo y barra de búsqueda -->
    <header>
        <ul class="lista-nav">
            <li class="linea-nav">
                <article class="logo">
                    <a href="https://www.medicosdelmundo.org/" class="enlace-medicos">
                        <img src="/Medicas-del-Mundo/styles/img/logo.png" alt="logo">
                    </a>
                </article>
            </li>
            <?php if(isset($_SESSION['usuaria'])) { ?>
            <li class="linea-nav">
                <?php if (($_SESSION['usuaria'])->ControlUsuarioAdmin()):?>
                <a href="/Medicas-del-Mundo/admin/ver_editoras.php">Ver editoras</a>
                <?php elseif(($_SESSION['usuaria'])->ControlUsuarioEditora()):?>
                <a href="/Medicas-del-Mundo/editora/perfil.php">Ver mi perfil</a>
                <?php endif;?>
            </li>
            <?php } ?>
            <li class="linea-nav">
                <h1>Bienvenida</h1>
            </li>
            <li class="linea-nav">
                <form method="GET" action="index.php" class="form-cabecera">
                    <input type="text" name="buscar" class="input-nav" placeholder="Buscar" value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                    <input type="submit" class="boton-invisible" value="Busca">
                </form>
            </li>
        </ul>
    </header>