<?php

namespace App\Support;

/**
 * Inspection custom fields stored as JSON in system settings.
 * New format: array of sections, each with title + fields.
 * Legacy: flat array of field definitions (wrapped as one "General" section when reading).
 */
class InspectionFieldsConfig
{
    /**
     * @param  array<int, mixed>|null  $raw
     * @return array<int, array{id: string, title: string, fields: array<int, mixed>}>
     */
    public static function normalizeSections(?array $raw): array
    {
        if ($raw === null || $raw === []) {
            return [];
        }

        $first = $raw[0] ?? null;
        if (is_array($first) && array_key_exists('fields', $first)) {
            return array_values(array_map(function (array $s): array {
                return [
                    'id' => (string) ($s['id'] ?? ('section_' . uniqid())),
                    'title' => (string) ($s['title'] ?? 'Section'),
                    'width' => (string) ($s['width'] ?? 'full'),
                    'fields' => array_values(is_array($s['fields'] ?? null) ? $s['fields'] : []),
                ];
            }, $raw));
        }

        return [[
            'id' => 'section_1',
            'title' => 'General',
            'fields' => array_values($raw),
        ]];
    }

    /**
     * @param  array<int, array{fields?: array<int, mixed>}>  $sections
     * @return array<int, mixed>
     */
    public static function flattenFields(array $sections): array
    {
        $out = [];
        foreach ($sections as $s) {
            foreach ($s['fields'] ?? [] as $f) {
                $out[] = $f;
            }
        }

        return $out;
    }

    /**
     * @param  array<int, array{fields?: array<int, mixed>}>  $sections
     */
    public static function totalFieldCount(array $sections): int
    {
        return count(self::flattenFields($sections));
    }
}
