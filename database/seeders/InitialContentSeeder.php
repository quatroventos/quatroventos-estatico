<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Certification;
use App\Models\FormOption;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class InitialContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->copyAssets();
        $this->rolesAndAdmin();
        $this->settings();
        $this->menu();
        $this->certifications();
        $this->team();
        $this->branches();
        $this->formOptions();
        $this->pages();
    }

    private function copyAssets(): void
    {
        $source = database_path('seed-assets');
        $target = storage_path('app/public/assets');

        if (! is_dir($source)) {
            return;
        }

        File::ensureDirectoryExists($target);
        File::copyDirectory($source, $target);
    }

    private function rolesAndAdmin(): void
    {
        Role::findOrCreate('root', 'web');
        Role::findOrCreate('user', 'web');

        $user = User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'adv@ramosbenedetti.adv.br')],
            [
                'name' => env('ADMIN_NAME', 'Administrador'),
                'password' => env('ADMIN_PASSWORD', 'RbaAdmin2026!'),
            ]
        );

        $user->syncRoles(['root']);

        $gabriel = User::query()->updateOrCreate(
            ['email' => 'gabriel@quatroventos.com.br'],
            [
                'name' => 'Gabriel',
                'password' => '12345678',
            ]
        );

        $gabriel->syncRoles(['root']);
    }

    private function settings(): void
    {
        SiteSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Ramos Benedetti Advogados',
                'tagline' => 'Parceria, inteligência e resultados',
                'logo_path' => '/storage/assets/9709068974938b6b09487e27a1b27e381a79a512.png',
                'phone' => '(16) 3456-2300',
                'phone_href' => 'tel:+551634562300',
                'email' => 'adv@ramosbenedetti.adv.br',
                'whatsapp_url' => 'https://wa.link/1hmwk3',
                'address' => "R. Paschoal Bardaro, 1235\nJardim São Luiz, Ribeirão Preto - SP\n14021-655",
                'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d119025.07199148051!2d-47.798478!3d-21.210683!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94b9b8d6ab6e4b8f%3A0xc7fdb0a59cbd4926!2sR.%20Paschoal%20Bardaro%2C%201235%20-%20Jardim%20Sao%20Luiz%2C%20Ribeir%C3%A3o%20Preto%20-%20SP%2C%2014021-655%2C%20Brasil!5e0!3m2!1spt-BR!2sus!4v1781276854765!5m2!1spt-BR!2sus',
                'instagram_url' => '',
                'linkedin_url' => '',
                'facebook_url' => '',
                'og_site_name' => 'Ramos Benedetti Advogados',
                'og_locale' => 'pt_BR',
                'twitter_card' => 'summary_large_image',
                'seo_separator' => '|',
                'seo_title_template' => '%page% %sep% %site%',
                'seo_description' => 'Parceria, inteligência e resultados. Escritório Ramos Benedetti Advogados.',
                'seo_og_image' => '/storage/assets/9709068974938b6b09487e27a1b27e381a79a512.png',
                'seo_og_image_alt' => 'Ramos Benedetti Advogados',
                'seo_robots' => 'index, follow, max-image-preview:large',
            ]
        );
    }

    private function menu(): void
    {
        $items = [
            ['label' => 'Home', 'href' => '/', 'group' => 'header', 'sort' => 1],
            ['label' => 'Quem somos', 'href' => '/quem-somos', 'group' => 'header', 'sort' => 2],
            ['label' => 'Expertise', 'href' => '/expertise', 'group' => 'header', 'sort' => 3],
            ['label' => 'Estrutura', 'href' => '/estrutura', 'group' => 'header', 'sort' => 4],
            ['label' => 'Inovação', 'href' => '/ia', 'group' => 'header', 'sort' => 5],
            ['label' => 'ESG', 'href' => '/esg', 'group' => 'header', 'sort' => 6],
            ['label' => 'Trabalhe Conosco', 'href' => '/carreiras', 'group' => 'header', 'sort' => 7],
            ['label' => 'Contato', 'href' => '/contato', 'group' => 'header', 'sort' => 8],
            ['label' => 'Home', 'href' => '/', 'group' => 'footer_1', 'sort' => 1],
            ['label' => 'Quem somos', 'href' => '/quem-somos', 'group' => 'footer_1', 'sort' => 2],
            ['label' => 'Expertise', 'href' => '/expertise', 'group' => 'footer_1', 'sort' => 3],
            ['label' => 'Estrutura', 'href' => '/estrutura', 'group' => 'footer_1', 'sort' => 4],
            ['label' => 'Inovação', 'href' => '/ia', 'group' => 'footer_2', 'sort' => 1],
            ['label' => 'ESG', 'href' => '/esg', 'group' => 'footer_2', 'sort' => 2],
            ['label' => 'Trabalhe Conosco', 'href' => '/carreiras', 'group' => 'footer_2', 'sort' => 3],
            ['label' => 'Contato', 'href' => '/contato', 'group' => 'footer_2', 'sort' => 4],
        ];

        foreach ($items as $item) {
            MenuItem::query()->updateOrCreate(
                ['group' => $item['group'], 'href' => $item['href']],
                $item + ['is_active' => true]
            );
        }
    }

    private function certifications(): void
    {
        $items = [
            ['label' => 'Selo Bronze Compliance', 'type' => 'badge', 'image_path' => '/storage/assets/selo-bronze-compliance.png', 'alt' => 'Selo Bronze Compliance', 'sort' => 1],
            ['label' => 'ISO 9001', 'type' => 'placeholder', 'image_path' => null, 'alt' => 'Espaço reservado para certificação ISO 9001', 'sort' => 2],
            ['label' => 'ISO 37001', 'type' => 'placeholder', 'image_path' => null, 'alt' => 'Espaço reservado para certificação ISO 37001', 'sort' => 3],
        ];

        foreach ($items as $item) {
            Certification::query()->updateOrCreate(['label' => $item['label']], $item);
        }
    }

    private function team(): void
    {
        $members = [
            [
                'name' => 'Ricardo Ramos Benedetti',
                'role' => 'Advogado, sócio-fundador e diretor.',
                'bio' => 'Possui formação pelo Legal English Institute, da Fordham School of Law (Nova York) e Master of Laws pela Cornell University (Ithaca, NY).',
                'photo_path' => '/storage/assets/05ec6d83619fcbd7da8c1feba12d07a3c2aaf3f5.png',
                'sort' => 1,
            ],
            [
                'name' => 'Thalita Gomes Carvalho',
                'role' => 'Advogada, sócia e superintendente administrativa.',
                'bio' => 'Especialista em Gestão Empresarial com MBA pela Fundação Getulio Vargas.',
                'photo_path' => '/storage/assets/82bd745049f9e44f009d29ab67d1f188ce88b0e1.png',
                'sort' => 2,
            ],
            [
                'name' => 'Eduardo Roque',
                'role' => 'Superintendente de Operações.',
                'bio' => '',
                'photo_path' => '/storage/assets/550315fe13df9bbd2058aa55ce251ca7739b41e3.png',
                'sort' => 3,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::query()->updateOrCreate(['name' => $member['name']], $member);
        }
    }

    private function branches(): void
    {
        $branches = [
            ['slug' => 'ribeirao-preto', 'city' => 'Ribeirão Preto', 'state' => 'SP', 'select_label' => 'Ribeirão Preto - SP - Sede', 'eyebrow' => 'Endereço - Sede', 'address' => "R. Paschoal Bardaro, 1235 - Jardim São Luiz,\nRibeirão Preto - SP, 14021-655", 'is_headquarters' => true, 'sort' => 1],
            ['slug' => 'belo-horizonte', 'city' => 'Belo Horizonte', 'state' => 'MG', 'select_label' => 'Belo Horizonte - MG', 'eyebrow' => 'Endereço', 'address' => "R. Rio Grande do Norte, 1435 - Sala 708 - Savassi,\nBelo Horizonte - MG, 30130-131", 'is_headquarters' => false, 'sort' => 2],
            ['slug' => 'aracaju', 'city' => 'Aracaju', 'state' => 'SE', 'select_label' => 'Aracaju - SE', 'eyebrow' => 'Endereço', 'address' => "R. Riachuelo, 1200 - São José,\nAracaju - SE, 49015-160", 'is_headquarters' => false, 'sort' => 3],
            ['slug' => 'maceio', 'city' => 'Maceió', 'state' => 'AL', 'select_label' => 'Maceió - AL', 'eyebrow' => 'Endereço', 'address' => "Av Fernandes Lima, nº 8, Andar 1, Sala 102,\nFarol, Maceió (AL) - 57055-000", 'is_headquarters' => false, 'sort' => 4],
            ['slug' => 'joao-pessoa', 'city' => 'João Pessoa', 'state' => 'PB', 'select_label' => 'João Pessoa - PB', 'eyebrow' => 'Endereço', 'address' => "Avenida Dom Pedro I, nº 719, sala 104, Tambiá,\nJoão Pessoa, Paraíba (PB) - 58020-514", 'is_headquarters' => false, 'sort' => 5],
            ['slug' => 'sao-paulo', 'city' => 'São Paulo', 'state' => 'SP', 'select_label' => 'São Paulo - SP', 'eyebrow' => 'Endereço', 'address' => "Av. Paulista, 1636 - Cerqueira César,\nSão Paulo - SP, 01310-200", 'is_headquarters' => false, 'sort' => 6],
            ['slug' => 'florianopolis', 'city' => 'Florianópolis', 'state' => 'SC', 'select_label' => 'Florianópolis - SC', 'eyebrow' => 'Endereço', 'address' => "Esquina com Av Rio Branco, Av. Pref. Osmar Cunha,\n416 - Centro, Florianópolis - SC, 88015-200", 'is_headquarters' => false, 'sort' => 7],
            ['slug' => 'curitiba', 'city' => 'Curitiba', 'state' => 'PR', 'select_label' => 'Curitiba - PR', 'eyebrow' => 'Endereço', 'address' => "R. Bom Jesus, 212 - Juvevê,\nCuritiba - PR, 80035-010", 'is_headquarters' => false, 'sort' => 8],
            ['slug' => 'porto-alegre', 'city' => 'Porto Alegre', 'state' => 'RS', 'select_label' => 'Porto Alegre - RS', 'eyebrow' => 'Endereço', 'address' => "Av. Carlos Gomes, 700 Edifício Platinum Tower,\nSala 606 - Boa Vista, Porto Alegre - RS, 90480-000", 'is_headquarters' => false, 'sort' => 9],
            ['slug' => 'campo-grande', 'city' => 'Campo Grande', 'state' => 'MS', 'select_label' => 'Campo Grande - MS', 'eyebrow' => 'Endereço', 'address' => "Av. Afonso Pena, 4785 - sala 701 - Santa Fe,\nCampo Grande - MG, 79031-01", 'is_headquarters' => false, 'sort' => 10],
            ['slug' => 'recife', 'city' => 'Recife', 'state' => 'PE', 'select_label' => 'Recife - PE', 'eyebrow' => 'Endereço', 'address' => "Av. República do Líbano, nº 251, sala 2205 - Torre A -\nEmpresarial RioMar Trade Center, Pina, Recife-PE.\nCEP: 51110-160", 'is_headquarters' => false, 'sort' => 11],
        ];

        foreach ($branches as $branch) {
            Branch::query()->updateOrCreate(['slug' => $branch['slug']], $branch);
        }
    }

    private function formOptions(): void
    {
        $options = [
            ['form' => 'contact', 'field' => 'destino', 'label' => 'Comercial', 'value' => 'Comercial', 'email' => 'adv@ramosbenedetti.adv.br', 'sort' => 1],
            ['form' => 'contact', 'field' => 'destino', 'label' => 'Jurídico', 'value' => 'Jurídico', 'email' => 'adv@ramosbenedetti.adv.br', 'sort' => 2],
            ['form' => 'contact', 'field' => 'destino', 'label' => 'Fale conosco', 'value' => 'Fale conosco', 'email' => 'adv@ramosbenedetti.adv.br', 'sort' => 3],
        ];

        foreach ($options as $option) {
            FormOption::query()->updateOrCreate(
                ['form' => $option['form'], 'field' => $option['field'], 'value' => $option['value']],
                $option
            );
        }
    }

    private function pages(): void
    {
        $pages = require database_path('data/pages.php');

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'template' => $page['template'],
                    'status' => 'published',
                    'published_at' => now(),
                    'seo_title' => $page['seo_title'],
                    'seo_description' => $page['seo_description'],
                    'canonical' => $page['canonical'],
                    'og_image' => $page['og_image'],
                    'og_image_alt' => $page['og_image_alt'],
                    'robots' => 'index, follow, max-image-preview:large',
                    'twitter_card' => 'summary_large_image',
                    'content' => $page['content'],
                ]
            );
        }
    }
}
