<?php

namespace App\Support;

/**
 * Profile-photo helpers shared by every public page, so a seller with no photo (never uploaded,
 * removed by an admin, or a file that fails to load) always gets the same branded image instead of
 * a plain block, a broken-image icon, or a picture from an outside avatar website.
 */
class Avatar
{
    /** Words that are titles or company suffixes, not part of a person's initials. */
    private const SKIP = [
        'dr', 'mr', 'mrs', 'ms', 'miss', 'prof', 'esq', 'jr', 'sr', 'ii', 'iii', 'iv',
        'cpa', 'md', 'dds', 'dmd', 'phd', 'llc', 'inc', 'pllc', 'pa', 'the', 'and', 'of',
    ];

    /** The Zonely brand image used when there is no usable photo. */
    public static function brandUrl(): string
    {
        return asset('frontend/img/brand-avatar.webp');
    }

    /** Public URL of the seller's own photo, or null when there is none. */
    public static function photoUrl(?string $photo): ?string
    {
        $photo = trim((string) $photo);
        if ($photo === '') {
            return null;
        }

        return str_starts_with($photo, 'http') ? $photo : asset($photo);
    }

    /**
     * Two-letter initials: first letter of the first and the last name, skipping titles and suffixes.
     * "Mohammad A. Aziz, Esq." => MA, "Dr. Ruhul M. Mumen" => RM, "Kan Builders" => KB, "Cher" => CH.
     */
    public static function initials(?string $name): string
    {
        $words = array_values(array_filter(
            preg_split('/[\s.,]+/u', (string) $name) ?: [],
            fn ($w) => $w !== '' && preg_match('/[\p{L}\p{N}]/u', $w) && !in_array(mb_strtolower($w), self::SKIP, true)
        ));

        if (!$words) {
            return '?';
        }

        if (count($words) === 1) {
            return mb_strtoupper(mb_substr($words[0], 0, 2));
        }

        return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[count($words) - 1], 0, 1));
    }
}
