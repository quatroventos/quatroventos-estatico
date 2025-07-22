<div class="portfolio">
    <div style="max-width: 1300px;" class="container d-flex gap-4 justify-content-center flex-wrap">

        <?php
        $directory = 'portfolio-landing-page';
        $desktopDirectory = $directory . '/desktop';
        $mobileDirectory = $directory . '/mobile';
        // Garante que o diretório existe antes de tentar lê-lo
        if (is_dir($directory)) {
            $files = array_diff(scandir($desktopDirectory), array('..', '.'));

            // Filtra apenas os arquivos .png do diretório desktop
            $images = array_filter($files, function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'png';
            });

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
                if (file_exists($textFile)) {
                    $lines = file($textFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    if (count($lines) > 0) $title = $lines[0];
                    if (count($lines) > 1) $description = $lines[1];
                    if (count($lines) > 2) $link = $lines[2];
                }

                    //mockup macbook

                    ?>
                <div style="position: relative; width: 100%;" class="row ">
                    <!-- Add or remove "with-glare" class. Removing the glare makes the screen interactive -->
                    <div class="col-12 col-lg-3 pt-5">
                        <h3 class="fs-4 fw-bold mb-2"><?= htmlspecialchars($title) ?></h3>
                        <p class="fs-6"><?= htmlspecialchars($description) ?></p>
                        <p class="d-flex fs-6 justify-content-center justify-content-lg-start">
                            <a href=<?= htmlspecialchars($link) ?> class="btn btn-primary rounded-pill">Acesse aqui</a>
                        </p>
                    </div>
                    <div class="col-12 col-lg-6 align-self-end css-mb with-glare mx-0">
                        <div class="mb-display-position">
                            <div class="mb-display">
                                <div class="mb-screen-position">
                                    <div class="mb-screen">

                                        <img src="<?= htmlspecialchars($desktopImage) ?>" alt="Imagem desktop <?= htmlspecialchars($basename) ?>" />

                                        <!-- Image (aspect 16:10 recommended, but not required). You might also want to change or remove the background color, in case the picture is not big enough, or rendering is off  -->
                                        <!-- <img src="https://unsplash.imgix.net/photo-1418479631014-8cbf89db3431?fit=crop&fm=jpg&h=500&q=75&w=800" alt="example image" /> -->
                                        <!-- Image By André Freitas from unsplash.com -->

                                        <!-- Youtube - don't forget to remove glare -->
                                        <!-- <iframe width="960" height="480" src="https://www.youtube.com/embed/GUEZCxBcM78?rel=0&amp;showinfo=0&amp;iv_load_policy=3&amp;controls=2" frameborder="0"></iframe> -->
                                        <!-- Notice: Youtube has some toruble with embed players and gives consol errors -->

                                        <!-- Iframe, simple website - don't forget to remove glare -->
                                        <!-- <iframe src="http://example.com" frameborder="0"></iframe> -->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-body"></div>
                        <div class="mb-bottom-cover"></div>
                    </div>

                    <?php

                    // Nova estrutura HTML para o mockup do iPhone 14 Pro
                    echo '<div class="align-self-end d-none d-lg-block col-3 m-0 marvel-device iphone-14-pro-codepen">'; // Adicionei uma classe principal para o container
                    echo '    <div class="camera"></div>'; // A "Dynamic Island"
                    echo '    <div class="screen">';
                    echo '<div class="browser-header">';
                    echo '<div class="circle"></div>';
                    echo '<div class="circle"></div>';
                    echo '<div class="circle"></div>';
                    echo '</div>';
                    echo '        <img src="' . htmlspecialchars($mobileImage) . '" alt="Desenvolvimento de Landing Page: ' . htmlspecialchars($basename) . '">';
                    echo '    </div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        } else {
            echo '<p>O diretório de portfólio não foi encontrado.</p>';
        }
        ?>
    </div>
</div>
