<footer>
        <div class="footer-content">
            <div class="about-section">
                <h3>Sobre Fatgram</h3>
                <p><?php bloginfo('description'); ?></p>
            </div>
            <div class="about-section">
                <h3>Contacto</h3>
                <p>Email: contacto@fatgram.com</p>
            </div>
        </div>
        <center style="margin-top: 2rem; font-size: 0.8rem; opacity: 0.6;">
            © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos los derechos reservados.
        </center>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>