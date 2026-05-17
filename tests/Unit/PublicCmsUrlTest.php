<?php

namespace Tests\Unit;

use App\Support\PublicCmsUrl;
use PHPUnit\Framework\TestCase;

class PublicCmsUrlTest extends TestCase
{
    public function test_pendaftaran_card_input_strips_base_path(): void
    {
        $this->assertSame('baptisan', PublicCmsUrl::formatPendaftaranCardSlugForInput('/pendaftaran/baptisan'));
        $this->assertSame('jemaat', PublicCmsUrl::formatPendaftaranCardSlugForInput('/pendaftaran/jemaat'));
    }

    public function test_pendaftaran_card_storage_prepends_base_path_from_slug(): void
    {
        $this->assertSame('/pendaftaran/baptisan', PublicCmsUrl::normalizePendaftaranCardUrlForStorage('baptisan'));
        $this->assertSame('/pendaftaran/pernikahan', PublicCmsUrl::normalizePendaftaranCardUrlForStorage('pernikahan'));
    }

    public function test_pendaftaran_card_keeps_full_path_and_external_url(): void
    {
        $this->assertSame('/pendaftaran/baptisan', PublicCmsUrl::normalizePendaftaranCardUrlForStorage('/pendaftaran/baptisan'));
        $this->assertSame('https://example.com/form', PublicCmsUrl::normalizePendaftaranCardUrlForStorage('https://example.com/form'));
        $this->assertSame('https://example.com/form', PublicCmsUrl::formatPendaftaranCardSlugForInput('https://example.com/form'));
    }

    public function test_pendaftaran_card_accepts_legacy_full_path_on_save(): void
    {
        $this->assertSame('/pendaftaran/baptisan', PublicCmsUrl::normalizePendaftaranCardUrlForStorage('/pendaftaran/baptisan'));
        $this->assertSame('baptisan', PublicCmsUrl::formatPendaftaranCardSlugForInput('/pendaftaran/baptisan'));
    }
}
