<footer>
        <section class="footer-section">
            <h2>Médicos del Mundo España</h2>
            <p>Conde de Vilches, 15 · 28028, Madrid</p>
            <p>Lunes a viernes: 8:00 - 20:00</p>
            <p>Tel: <a href="tel:+34915436033">91 543 60 33</a> ·
                Email: <a href="mailto:informacion@medicosdelmundo.org">informacion@medicosdelmundo.org</a>
            </p>
            <?php if(isset($_SESSION['usuaria'])):?>
            <p><a href="../controladores/cerrar_sesion.php">Cerrar Sesion</a></p>
            <?php else:?>
            <p><a href="login.php">Iniciar Sesion</a></p>
            <?php endif;?>
        </section>
    </footer>