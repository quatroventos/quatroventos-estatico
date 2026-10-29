<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KJHN8M');</script>
<!-- End Google Tag Manager -->

<script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
<body class="signup-page sidebar-collapse">

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJHN8M"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white fixed-top navbar-transparent" color-on-scroll="3">
    <div class="container">
        <div class="navbar-translate">
            <a class="navbar-brand" href="index.php">
                <img src="assets/logo-quatroventos.svg" alt="Quatro Ventos - Desenvolvimento de Sites" width="180" height="28">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-bar top-bar"></span>
                <span class="navbar-toggler-bar middle-bar"></span>
                <span class="navbar-toggler-bar bottom-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" data-nav-image="./assets/img/blurred-image-1.jpg" data-color="orange">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="desenvolvimento-de-sites" class="nav-link">
                        <p>Desenvolvimento de sites</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manutencao-de-sites" class="nav-link">
                        <p>Manutenção de sites</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a  href="hospedagem-de-sites" class="nav-link">
                        <p>Hospedagem</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary btn-round" style="font-size:0.83em" href="https://wa.me/554130399593?text=Gostaria%20de%20um%20orçamento.">
                        <p><i class="now-ui-icons files_paper  "></i> <strong>Solicitar proposta</strong></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-info btn-round" style="font-size:0.83em" href="https://quatroventos.freshdesk.com/support/home" target="_blank">
                        <p><i class="now-ui-icons objects_support-17"></i> <strong>Suporte</strong></p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function(){
        var scroll_start = 0;
        var startchange = $('#startchange');
        var offset = startchange.offset();
        if (startchange.length){
            $(document).scroll(function() {
                scroll_start = $(this).scrollTop();
                if(scroll_start > offset.top) {
                    $(".navbar-default").css('background-color', '#f0f0f0');
                } else {
                    $('.navbar-default').css('background-color', 'transparent');
                }
            });
        }
    });
</script>

    <!-- Google tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6Y240LW004">

    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "es1l0rhzvx");
    </script>

<!-- End Navbar -->
