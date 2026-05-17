<?php

namespace Tests\Unit;

use App\Support\CmsIcon;
use PHPUnit\Framework\TestCase;

class CmsIconTest extends TestCase
{
    public function test_converts_numeric_html_entity_to_font_awesome(): void
    {
        $this->assertSame('fa-solid fa-bullhorn', CmsIcon::toFontAwesome('&#128226;'));
        $this->assertSame('fa-solid fa-images', CmsIcon::toFontAwesome('&#128444;'));
        $this->assertSame('fa-solid fa-cross', CmsIcon::toFontAwesome('&#10013;'));
    }

    public function test_keeps_existing_font_awesome_classes(): void
    {
        $this->assertSame('fa-solid fa-church', CmsIcon::toFontAwesome('fas fa-church'));
    }

    public function test_detects_legacy_html(): void
    {
        $this->assertTrue(CmsIcon::isLegacyHtml('&#128226;'));
        $this->assertFalse(CmsIcon::isLegacyHtml('fa-solid fa-bullhorn'));
    }

    public function test_sidebar_card_icon_falls_back_to_url_based_font_awesome(): void
    {
        $data = CmsIcon::normalizePageData('beranda', [
            'sidebar_cards' => [
                ['icon' => '', 'url' => '/informasi-kegiatan'],
                ['icon' => '', 'url' => '/galeri'],
                ['icon' => '', 'url' => '/pendaftaran'],
            ],
        ]);

        $this->assertSame('fa-solid fa-bullhorn', $data['sidebar_cards'][0]['icon']);
        $this->assertSame('fa-solid fa-images', $data['sidebar_cards'][1]['icon']);
        $this->assertSame('fa-solid fa-clipboard-list', $data['sidebar_cards'][2]['icon']);
    }

    public function test_sidebar_card_placeholder_circle_is_replaced_by_url_icon(): void
    {
        $data = CmsIcon::normalizePageData('beranda', [
            'sidebar_cards' => [
                ['icon' => 'fa-solid fa-circle', 'url' => '/informasi-kegiatan'],
                ['icon' => 'fas fa-circle', 'url' => '/galeri'],
                ['icon' => '•', 'url' => '/pendaftaran'],
            ],
        ]);

        $this->assertSame('fa-solid fa-bullhorn', $data['sidebar_cards'][0]['icon']);
        $this->assertSame('fa-solid fa-images', $data['sidebar_cards'][1]['icon']);
        $this->assertSame('fa-solid fa-clipboard-list', $data['sidebar_cards'][2]['icon']);
    }

    public function test_linked_card_keeps_explicit_non_placeholder_icon(): void
    {
        $this->assertSame(
            'fa-solid fa-star',
            CmsIcon::linkedCardIconClasses('fa-solid fa-star', '/informasi-kegiatan')
        );
    }
}
