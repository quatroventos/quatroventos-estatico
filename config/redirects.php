<?php

// Redirects 301 do site antigo para a estrutura nova (.impeccable/briefs/mapa-do-site.md).
// As páginas de origem ficam em rascunho no CMS; o redirect é registrado antes da rota catch-all.

return [
    '/criacao-de-sites' => '/desenvolvimento-de-sites',
    '/desenvolvimento-de-landing-page' => '/landing-pages',
    '/criar-landing-page' => '/landing-pages',
    '/criar-landing-pages' => '/landing-pages',
    '/criacao-de-landing-pages' => '/landing-pages',
    '/desenvolvimento-de-sites-wordpress' => '/sites-wordpress',
    '/desenvolvimento-full-stack' => '/software-sob-medida',
    '/manutencao-de-sites-wordpress' => '/manutencao-de-sites',
    '/porque-meu-site-nao-vende' => '/diagnostico-de-site',
];
