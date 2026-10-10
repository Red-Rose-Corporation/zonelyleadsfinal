<?php

namespace App\Support;

/**
 * Extra JSON-LD for a few hand-picked blog posts: a BreadcrumbList and, when the post body has a
 * "Frequently asked questions" section, a FAQPage built from that same section so the markup always
 * matches what readers see.
 *
 * It is opt-in per slug (see ENABLED_SLUGS), so every other post renders exactly as before. Any
 * failure while reading the body returns an empty string; a post page must never break over markup.
 */
class BlogStructuredData
{
    /** Posts that get the extra markup. Add a slug here to opt another post in. */
    public const ENABLED_SLUGS = [
        'toronto-stock-option-cpa',
    ];

    /** The <script> tags to print inside the page's schema section ('' when the post is not opted in). */
    public static function scripts(?string $slug, ?string $title, ?string $bodyHtml): string
    {
        // Opt-in check first: for every other post nothing below runs, so nothing here can affect them.
        if (!in_array((string) $slug, self::ENABLED_SLUGS, true)) {
            return '';
        }

        try {
            $blocks = [self::breadcrumb((string) $title, url()->current(), route('frontend.home'), route('frontend.blog'))];

            $faq = self::faqFromHtml((string) $bodyHtml);
            if ($faq !== []) {
                $blocks[] = [
                    '@context'   => 'https://schema.org',
                    '@type'      => 'FAQPage',
                    'mainEntity' => array_map(fn(array $qa) => [
                        '@type'          => 'Question',
                        'name'           => $qa['question'],
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa['answer']],
                    ], $faq),
                ];
            }

            $out = '';
            foreach ($blocks as $block) {
                $json = json_encode(
                    $block,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_PRETTY_PRINT
                );
                if ($json === false) {
                    continue;
                }
                $out .= '<script type="application/ld+json">' . "\n" . $json . "\n" . '</script>' . "\n";
            }

            return $out;
        } catch (\Throwable $e) {
            return '';
        }
    }

    private static function breadcrumb(string $title, string $postUrl, string $homeUrl, string $blogUrl): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $homeUrl],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => $blogUrl],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $title, 'item' => $postUrl],
            ],
        ];
    }

    /**
     * Question/answer pairs from the section that starts at the <h2> "Frequently asked questions":
     * each <h3> is a question and the <p> elements after it (up to the next heading) are its answer.
     *
     * @return array<int, array{question: string, answer: string}>
     */
    public static function faqFromHtml(string $html): array
    {
        if (trim($html) === '') {
            return [];
        }

        $previous = libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="utf-8" ?><div id="faq-root">' . $html . '</div>', LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('faq-root');
        if (!$root) {
            return [];
        }

        $items = [];
        $inFaq = false;
        $question = null;
        $answer = [];

        $flush = function () use (&$items, &$question, &$answer) {
            if ($question !== null && $answer !== []) {
                $items[] = ['question' => $question, 'answer' => implode(' ', $answer)];
            }
            $question = null;
            $answer = [];
        };

        foreach ($root->childNodes as $node) {
            if (!$node instanceof \DOMElement) {
                continue;
            }
            $tag = strtolower($node->tagName);
            $text = self::clean($node->textContent);

            if ($tag === 'h2') {
                $flush();
                $inFaq = (bool) preg_match('/^frequently asked questions\b/i', $text);
                continue;
            }
            if (!$inFaq) {
                continue;
            }
            if ($tag === 'h3') {
                $flush();
                $question = $text !== '' ? $text : null;
            } elseif ($tag === 'p' && $question !== null && $text !== '') {
                $answer[] = $text;
            }
        }
        $flush();

        return $items;
    }

    private static function clean(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\xC2\xA0", ' ', $text);

        return trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    }
}
