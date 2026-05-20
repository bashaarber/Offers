<?php

namespace App\Support;

use Illuminate\Support\Collection;

class OrganigramDisplayOrder
{
    /**
     * Build lookup maps for organigram / group / element display order (matches position edit UI).
     *
     * @return array{groupOrder: array<string, array<string, int>>, elementOrder: array<string, array<string, array<string, int>>>}
     */
    public static function buildSortMaps(Collection $organigrams): array
    {
        $groupOrder = [];
        $elementOrder = [];

        foreach ($organigrams as $organigram) {
            $groupOrder[$organigram->name] = [];
            $elementOrder[$organigram->name] = [];

            foreach ($organigram->group_elements as $groupIndex => $groupElement) {
                $groupOrder[$organigram->name][$groupElement->name] = $groupIndex;
                $elementOrder[$organigram->name][$groupElement->name] = [];

                foreach ($groupElement->elements as $elementIndex => $element) {
                    $elementOrder[$organigram->name][$groupElement->name][$element->name] = $elementIndex;
                }
            }
        }

        return compact('groupOrder', 'elementOrder');
    }

    /**
     * Sort groups and elements within each organigram section using the organigram tree order.
     */
    public static function sortGroupsAndElements(array $groups, string $organigramName, array $sortMaps): array
    {
        $groupOrder = $sortMaps['groupOrder'][$organigramName] ?? [];
        $elementOrder = $sortMaps['elementOrder'][$organigramName] ?? [];

        uksort($groups, function (string $a, string $b) use ($groupOrder): int {
            $rankA = $groupOrder[$a] ?? PHP_INT_MAX;
            $rankB = $groupOrder[$b] ?? PHP_INT_MAX;

            return $rankA <=> $rankB ?: strnatcasecmp($a, $b);
        });

        foreach ($groups as $groupName => $elements) {
            $ranks = $elementOrder[$groupName] ?? [];
            usort($elements, function (array $a, array $b) use ($ranks): int {
                $nameA = $a['element_name'];
                $nameB = $b['element_name'];
                $rankA = $ranks[$nameA] ?? PHP_INT_MAX;
                $rankB = $ranks[$nameB] ?? PHP_INT_MAX;

                return $rankA <=> $rankB ?: strnatcasecmp($nameA, $nameB);
            });
            $groups[$groupName] = $elements;
        }

        return $groups;
    }
}
