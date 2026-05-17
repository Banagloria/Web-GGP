<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class CmsContentBlocks
{
    /** @var list<string> */
    public const HEADING_TYPES = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

    /** @var list<string> */
    public const LIST_TYPES = ['ul', 'ol', 'ol_roman', 'ol_upper', 'ol_lower'];

    /** @var list<string> */
    public const TYPES = ['p', ...self::HEADING_TYPES, ...self::LIST_TYPES];

    public static function isHeading(string $type): bool
    {
        return in_array($type, self::HEADING_TYPES, true);
    }

    public static function isList(string $type): bool
    {
        return in_array($type, self::LIST_TYPES, true);
    }

    public static function isTextBlock(string $type): bool
    {
        return $type === 'p' || self::isHeading($type);
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    public static function selectOptions(): array
    {
        $options = [['value' => 'p', 'label' => self::typeLabel('p')]];

        foreach (self::HEADING_TYPES as $type) {
            $options[] = ['value' => $type, 'label' => self::typeLabel($type)];
        }

        foreach (self::LIST_TYPES as $type) {
            $options[] = ['value' => $type, 'label' => self::typeLabel($type)];
        }

        return $options;
    }

    /**
     * @param  list<array<string, mixed>>  $blocks
     */
    public static function toHtml(array $blocks): string
    {
        $parts = [];

        foreach (self::normalize($blocks) as $block) {
            $type = $block['type'];

            if (self::isTextBlock($type)) {
                $text = trim((string) ($block['text'] ?? ''));
                if ($text === '') {
                    continue;
                }
                $tag = $type === 'p' ? 'p' : $type;
                $parts[] = '<'.$tag.'>'.e($text).'</'.$tag.'>';

                continue;
            }

            if (self::isList($type)) {
                $items = array_values(array_filter(
                    array_map(static fn ($item) => trim((string) $item), $block['items'] ?? []),
                    static fn (string $item) => $item !== ''
                ));
                if ($items === []) {
                    continue;
                }
                $lis = array_map(static fn (string $item) => '<li>'.e($item).'</li>', $items);
                [$tag, $attrs] = self::listHtmlTag($type);
                $parts[] = '<'.$tag.$attrs.'>'.implode('', $lis).'</'.$tag.'>';
            }
        }

        return implode("\n", $parts);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private static function listHtmlTag(string $type): array
    {
        return match ($type) {
            'ul' => ['ul', ''],
            'ol' => ['ol', ''],
            'ol_roman' => ['ol', ' type="i"'],
            'ol_upper' => ['ol', ' type="A"'],
            'ol_lower' => ['ol', ' type="a"'],
            default => ['ol', ''],
        };
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function fromHtml(string $html): array
    {
        $html = trim($html);
        if ($html === '') {
            return [];
        }

        $dom = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8"?><div id="cms-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('cms-root');
        if (! $root instanceof DOMElement) {
            return [['type' => 'p', 'text' => strip_tags($html)]];
        }

        $blocks = [];
        foreach ($root->childNodes as $node) {
            $parsed = self::nodeToBlock($node);
            if ($parsed !== null) {
                $blocks[] = $parsed;
            }
        }

        return $blocks;
    }

    /**
     * @param  list<array<string, mixed>>|null  $blocks
     * @return list<array<string, mixed>>
     */
    public static function normalize(?array $blocks): array
    {
        if (! is_array($blocks)) {
            return [];
        }

        $sorted = $blocks;
        if (array_is_list($sorted) === false) {
            ksort($sorted, SORT_NUMERIC);
        }
        $sorted = array_values($sorted);

        $normalized = [];

        foreach ($sorted as $block) {
            if (! is_array($block)) {
                continue;
            }

            $type = (string) ($block['type'] ?? '');
            if (! in_array($type, self::TYPES, true)) {
                continue;
            }

            if (self::isTextBlock($type)) {
                $text = trim((string) ($block['text'] ?? ''));
                if ($text === '') {
                    continue;
                }
                $normalized[] = ['type' => $type, 'text' => $text];

                continue;
            }

            $items = [];
            foreach ((array) ($block['items'] ?? []) as $item) {
                $item = trim((string) $item);
                if ($item !== '') {
                    $items[] = $item;
                }
            }

            if ($items === []) {
                continue;
            }

            $normalized[] = ['type' => $type, 'items' => $items];
        }

        return $normalized;
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function nodeToBlock(DOMNode $node): ?array
    {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return null;
        }

        /** @var DOMElement $node */
        $tag = strtolower($node->nodeName);

        if ($tag === 'p' || self::isHeading($tag)) {
            $text = trim(preg_replace('/\s+/u', ' ', $node->textContent ?? '') ?? '');
            if ($text === '') {
                return null;
            }

            return ['type' => $tag, 'text' => $text];
        }

        if ($tag === 'ul') {
            $items = self::listItemsFromElement($node);
            if ($items === []) {
                return null;
            }

            return ['type' => 'ul', 'items' => $items];
        }

        if ($tag === 'ol') {
            $items = self::listItemsFromElement($node);
            if ($items === []) {
                return null;
            }

            return ['type' => self::olTypeFromAttribute($node->getAttribute('type')), 'items' => $items];
        }

        return null;
    }

    /**
     * @return list<string>
     */
    private static function listItemsFromElement(DOMElement $node): array
    {
        $items = [];
        foreach ($node->getElementsByTagName('li') as $li) {
            $text = trim(preg_replace('/\s+/u', ' ', $li->textContent ?? '') ?? '');
            if ($text !== '') {
                $items[] = $text;
            }
        }

        return $items;
    }

    private static function olTypeFromAttribute(string $typeAttr): string
    {
        $typeAttr = trim($typeAttr);
        if ($typeAttr === '') {
            return 'ol';
        }

        return match ($typeAttr) {
            '1' => 'ol',
            'i', 'I' => 'ol_roman',
            'a' => 'ol_lower',
            'A' => 'ol_upper',
            default => 'ol',
        };
    }

    /**
     * Blok untuk editor CMS: dari array tersimpan, HTML, atau teks biasa (baris → daftar).
     *
     * @param  list<array<string, mixed>>|null  $blocks
     * @return list<array<string, mixed>>
     */
    public static function editorBlocksFromStorage(?array $blocks, string $html): array
    {
        if (is_array($blocks) && count($blocks) > 0) {
            return $blocks;
        }

        $parsed = self::fromHtml($html);
        if ($parsed !== []) {
            return $parsed;
        }

        $html = trim($html);
        if ($html !== '' && ! preg_match('/<[^>]+>/', $html)) {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $html))));
            if ($lines !== []) {
                return [['type' => 'ul', 'items' => $lines]];
            }
        }

        return [['type' => 'p', 'text' => '']];
    }

    public static function typeLabel(string $type): string
    {
        return match ($type) {
            'p' => 'Paragraf',
            'h1' => 'Heading 1',
            'h2' => 'Heading 2',
            'h3' => 'Heading 3',
            'h4' => 'Heading 4',
            'h5' => 'Heading 5',
            'h6' => 'Heading 6',
            'ul' => 'Daftar bulat',
            'ol' => 'Daftar angka',
            'ol_roman' => 'Daftar angka romawi',
            'ol_upper' => 'Daftar huruf besar',
            'ol_lower' => 'Daftar huruf kecil',
            default => $type,
        };
    }
}
