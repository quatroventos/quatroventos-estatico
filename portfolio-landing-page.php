<div class="portfolio">
    <div class="container d-flex gap-4 justify-content-center flex-wrap">

        <?php
        $directory = 'portfolio-landing-page';
        // Garante que o diretório existe antes de tentar lê-lo
        if (is_dir($directory)) {
            $images = array_diff(scandir($directory), array('..', '.'));

            foreach ($images as $image) {
                // Garante que estamos processando apenas arquivos de imagem
                $filePath = $directory . '/' . $image;
                $imageInfo = @getimagesize($filePath); // Usamos '@' para suprimir erros se não for imagem

                if ($imageInfo) {

                    //mockup macbook

                    ?>
                <div style="position: relative; width: 100%;">
                    <!-- Add or remove "with-glare" class. Removing the glare makes the screen interactive -->
                    <div class="css-mb with-glare">
                        <div class="mb-display-position">
                            <div class="mb-display">
                                <div class="mb-screen-position">
                                    <div class="mb-screen">

                                        <!-- Image (aspect 16:10 recommended, but not required). You might also want to change or remove the background color, in case the picture is not big enough, or rendering is off  -->
                                        <img src="https://unsplash.imgix.net/photo-1418479631014-8cbf89db3431?fit=crop&fm=jpg&h=500&q=75&w=800" alt="example image" />
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
                    echo '<div class="marvel-device iphone-14-pro-codepen">'; // Adicionei uma classe principal para o container
                    echo '    <div class="camera"></div>'; // A "Dynamic Island"
                    echo '    <div class="screen">';
                    echo '<div class="browser-header">';
                    echo '<div class="circle"></div>';
                    echo '<div class="circle"></div>';
                    echo '<div class="circle"></div>';
                    echo '</div>';
                    echo '        <img src="' . htmlspecialchars($filePath) . '" alt="Desenvolvimento de Landing Page: ' . htmlspecialchars($image) . '">';
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
