<script src="../assets/js/core/jquery.min.js" type="text/javascript"></script>
<body class="signup-page sidebar-collapse">
	
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJHN8M"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white fixed-top navbar-transparent" color-on-scroll="10">
    <div class="container">
        <div class="navbar-translate">
            <a class="navbar-brand" href="index.php">
                <img src="../assets/logo-quatroventos.svg" alt="Quatro Ventos - Website Development" style="max-width:180px;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-bar top-bar"></span>
                <span class="navbar-toggler-bar middle-bar"></span>
                <span class="navbar-toggler-bar bottom-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" data-nav-image="../assets/img/blurred-image-1.jpg" data-color="orange">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index" >
                        <p>Home</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="desenvolvimento-de-sites"class="nav-link">
                        <p>Webdesign</p>
                    </a>
                </li>
<!--                <li class="nav-item">-->
<!--                    <a href="website-maintenance" class="nav-link">-->
<!--                        <p>Website Maintenance</p>-->
<!--                    </a>-->
<!--                </li>-->
                <li class="nav-item">
                    <a href="full-stack-development" class="nav-link">
                        <p>Full Stack Development</p>
                    </a>
                </li>
<!--                <li class="nav-item">-->
<!--                    <a  href="hosting" class="nav-link">-->
<!--                        <p>Hosting</p>-->
<!--                    </a>-->
<!--                </li>-->
                <li class="nav-item">
                    <a  href="#contato" class="nav-link">
                        <p>Contact</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-white tn-primary btn--suporte" href="https://quatroventos.freshdesk.com/support/home" target="_blank">
                        <p>Customer Login</p>
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

<!-- End Navbar -->