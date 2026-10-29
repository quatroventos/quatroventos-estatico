<div class="portfolio">
    <div  class="container d-flex justify-content-center flex-wrap">
        <div style="position: relative; width: 100%; <?php echo $css; ?>" class="row mt-5" data-aos="fade-up" data-aos-duration="300">
        <?php
            $directory = 'portfolio-new';
            $desktopDirectory = $directory . '/desktop';
            $mobileDirectory = $directory . '/mobile';
            // Garante que o diretório existe antes de tentar lê-lo
            if (is_dir($directory)) {
            $files = array_diff(scandir($desktopDirectory), array('..', '.'));

            // Filtra apenas os arquivos .png do diretório desktop
            $images = array_filter($files, function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'png';
            });
            $i = 1;

            foreach ($images as $image) {
            $basename = pathinfo($image, PATHINFO_FILENAME);
            $desktopImage = "$desktopDirectory/$basename.png";
            $textFile = "$desktopDirectory/$basename.txt";
            $mobileImage = "$mobileDirectory/$basename.png";

            $imageInfo = @getimagesize($desktopImage); // Usamos '@' para suprimir erros se não for imagem

            if ($imageInfo) {

            // Lê conteúdo do .txt
            $title = "Título não encontrado";
            $description = "Descrição não disponível";
            $link = "#";

            $css = "";
            if($i > 1){
                $css =  "";
            }

            if (file_exists($textFile)) {
                $lines = file($textFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (count($lines) > 0) $title = $lines[0];
                if (count($lines) > 1) $description = $lines[1];
                if (count($lines) > 2) $link = $lines[2];
            }

            //mockup macbook

        ?>

            <div class="col-12 col-lg-6 mt-5">
                <h3 class="fs-4 fw-bold mb-2" style="font-weight: 700;"><?php echo htmlspecialchars($title) ?></h3>

                <div style="border-radius:20px; overflow: hidden; max-height: 300px;">
                    <a href=<?php echo htmlspecialchars($link) ?> target="_blank">
                        <img src="<?php echo htmlspecialchars($desktopImage) ?>" alt="Desenvolvimento de Landing Page: <?php echo htmlspecialchars($title) ?>" title="Desenvolvimento de site: <?php echo htmlspecialchars($title) ?>"  loading="lazy"/>
                    </a>
                </div>

<!--                <p class="mt-3 mb-2" style="color: #555; height: 200px;">--><?php //echo htmlspecialchars($description) ?><!--</p>-->

                <p class="d-flex fs-6 justify-content-center justify-content-lg-start">
                    <a href=<?php echo htmlspecialchars($link) ?> class="btn btn-sm btn-secondary rounded-pill" target="_blank">Ver ao vivo</a>&nbsp;<a href="https://api.whatsapp.com/send?l=pt-BR&phone=554130399593" class="btn btn-sm btn-primary rounded-pill" target="_blank">Quero uma página assim!</a>
                </p>
            </div>


            <?php

            }
            $i++;
            }
            } else {
                echo '<p>O diretório de portfólio não foi encontrado.</p>';
            }
            ?>
        </div>
    </div>
