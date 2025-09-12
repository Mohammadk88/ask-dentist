<?php

namespace Database\Seeders;

use App\Infrastructure\Models\TeethReference;
use Illuminate\Database\Seeder;

class TeethReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teeth = [
            // Upper Right Quadrant (1)
            ['fdi_code' => '11', 'name' => 'Upper Right Central Incisor', 'type' => 'incisor', 'quadrant' => 'upper_right', 'position_in_quadrant' => 1],
            ['fdi_code' => '12', 'name' => 'Upper Right Lateral Incisor', 'type' => 'incisor', 'quadrant' => 'upper_right', 'position_in_quadrant' => 2],
            ['fdi_code' => '13', 'name' => 'Upper Right Canine', 'type' => 'canine', 'quadrant' => 'upper_right', 'position_in_quadrant' => 3],
            ['fdi_code' => '14', 'name' => 'Upper Right First Premolar', 'type' => 'premolar', 'quadrant' => 'upper_right', 'position_in_quadrant' => 4],
            ['fdi_code' => '15', 'name' => 'Upper Right Second Premolar', 'type' => 'premolar', 'quadrant' => 'upper_right', 'position_in_quadrant' => 5],
            ['fdi_code' => '16', 'name' => 'Upper Right First Molar', 'type' => 'molar', 'quadrant' => 'upper_right', 'position_in_quadrant' => 6],
            ['fdi_code' => '17', 'name' => 'Upper Right Second Molar', 'type' => 'molar', 'quadrant' => 'upper_right', 'position_in_quadrant' => 7],
            ['fdi_code' => '18', 'name' => 'Upper Right Third Molar (Wisdom Tooth)', 'type' => 'molar', 'quadrant' => 'upper_right', 'position_in_quadrant' => 8],

            // Upper Left Quadrant (2)
            ['fdi_code' => '21', 'name' => 'Upper Left Central Incisor', 'type' => 'incisor', 'quadrant' => 'upper_left', 'position_in_quadrant' => 1],
            ['fdi_code' => '22', 'name' => 'Upper Left Lateral Incisor', 'type' => 'incisor', 'quadrant' => 'upper_left', 'position_in_quadrant' => 2],
            ['fdi_code' => '23', 'name' => 'Upper Left Canine', 'type' => 'canine', 'quadrant' => 'upper_left', 'position_in_quadrant' => 3],
            ['fdi_code' => '24', 'name' => 'Upper Left First Premolar', 'type' => 'premolar', 'quadrant' => 'upper_left', 'position_in_quadrant' => 4],
            ['fdi_code' => '25', 'name' => 'Upper Left Second Premolar', 'type' => 'premolar', 'quadrant' => 'upper_left', 'position_in_quadrant' => 5],
            ['fdi_code' => '26', 'name' => 'Upper Left First Molar', 'type' => 'molar', 'quadrant' => 'upper_left', 'position_in_quadrant' => 6],
            ['fdi_code' => '27', 'name' => 'Upper Left Second Molar', 'type' => 'molar', 'quadrant' => 'upper_left', 'position_in_quadrant' => 7],
            ['fdi_code' => '28', 'name' => 'Upper Left Third Molar (Wisdom Tooth)', 'type' => 'molar', 'quadrant' => 'upper_left', 'position_in_quadrant' => 8],

            // Lower Left Quadrant (3)
            ['fdi_code' => '31', 'name' => 'Lower Left Central Incisor', 'type' => 'incisor', 'quadrant' => 'lower_left', 'position_in_quadrant' => 1],
            ['fdi_code' => '32', 'name' => 'Lower Left Lateral Incisor', 'type' => 'incisor', 'quadrant' => 'lower_left', 'position_in_quadrant' => 2],
            ['fdi_code' => '33', 'name' => 'Lower Left Canine', 'type' => 'canine', 'quadrant' => 'lower_left', 'position_in_quadrant' => 3],
            ['fdi_code' => '34', 'name' => 'Lower Left First Premolar', 'type' => 'premolar', 'quadrant' => 'lower_left', 'position_in_quadrant' => 4],
            ['fdi_code' => '35', 'name' => 'Lower Left Second Premolar', 'type' => 'premolar', 'quadrant' => 'lower_left', 'position_in_quadrant' => 5],
            ['fdi_code' => '36', 'name' => 'Lower Left First Molar', 'type' => 'molar', 'quadrant' => 'lower_left', 'position_in_quadrant' => 6],
            ['fdi_code' => '37', 'name' => 'Lower Left Second Molar', 'type' => 'molar', 'quadrant' => 'lower_left', 'position_in_quadrant' => 7],
            ['fdi_code' => '38', 'name' => 'Lower Left Third Molar (Wisdom Tooth)', 'type' => 'molar', 'quadrant' => 'lower_left', 'position_in_quadrant' => 8],

            // Lower Right Quadrant (4)
            ['fdi_code' => '41', 'name' => 'Lower Right Central Incisor', 'type' => 'incisor', 'quadrant' => 'lower_right', 'position_in_quadrant' => 1],
            ['fdi_code' => '42', 'name' => 'Lower Right Lateral Incisor', 'type' => 'incisor', 'quadrant' => 'lower_right', 'position_in_quadrant' => 2],
            ['fdi_code' => '43', 'name' => 'Lower Right Canine', 'type' => 'canine', 'quadrant' => 'lower_right', 'position_in_quadrant' => 3],
            ['fdi_code' => '44', 'name' => 'Lower Right First Premolar', 'type' => 'premolar', 'quadrant' => 'lower_right', 'position_in_quadrant' => 4],
            ['fdi_code' => '45', 'name' => 'Lower Right Second Premolar', 'type' => 'premolar', 'quadrant' => 'lower_right', 'position_in_quadrant' => 5],
            ['fdi_code' => '46', 'name' => 'Lower Right First Molar', 'type' => 'molar', 'quadrant' => 'lower_right', 'position_in_quadrant' => 6],
            ['fdi_code' => '47', 'name' => 'Lower Right Second Molar', 'type' => 'molar', 'quadrant' => 'lower_right', 'position_in_quadrant' => 7],
            ['fdi_code' => '48', 'name' => 'Lower Right Third Molar (Wisdom Tooth)', 'type' => 'molar', 'quadrant' => 'lower_right', 'position_in_quadrant' => 8],
        ];

        foreach ($teeth as $tooth) {
            $tooth['is_permanent'] = true;
            $tooth['description'] = $this->getToothDescription($tooth['type'], $tooth['position_in_quadrant']);

            TeethReference::create($tooth);
        }
    }

    /**
     * Get description for tooth based on type and position
     */
    private function getToothDescription(string $type, int $position): string
    {
        return match($type) {
            'incisor' => $position === 1
                ? 'Central incisors are used for cutting food and are located at the front center of the mouth.'
                : 'Lateral incisors are used for cutting food and are located next to the central incisors.',
            'canine' => 'Canines are the sharpest teeth, used for tearing food. They are located at the corners of the mouth.',
            'premolar' => $position === 4
                ? 'First premolars are used for crushing and grinding food. They have two cusps.'
                : 'Second premolars are used for crushing and grinding food. They have two or three cusps.',
            'molar' => match($position) {
                6 => 'First molars are the largest teeth used for grinding food. They typically have four cusps.',
                7 => 'Second molars are used for grinding food and have similar structure to first molars.',
                8 => 'Third molars (wisdom teeth) are the last teeth to erupt and may not be present in all individuals.',
                default => 'Molar tooth used for grinding and chewing food.'
            },
            default => 'Tooth used for food processing and digestion.'
        };
    }
}
