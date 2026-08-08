<?php

namespace Database\Factories;

use App\Models\PropertyDocumentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyDocumentModel>
 */
class PropertyDocumentModelFactory extends Factory
{
    protected $model = PropertyDocumentModel::class;

    public function definition(): array
    {
        $docKeys = [
            'Documents_Master_Deed' => 'property_documents/master_deed_1.pdf',
            'Documents_Expence_Calculations' => 'property_documents/expense_statement_4.pdf',
            'Documents_Rent_Calculations' => 'property_documents/rent_calculation_3.pdf',
            'Documents_Deed_Restrictions' => 'property_documents/deed_restrictions_5.pdf',
            'Documents_Closing_Statement_Example' => 'property_documents/closing_statement_6.pdf',
            'document_1' => 'property_documents/operating_agreement_2.pdf',
        ];

        $randomKey = fake()->randomElement(array_keys($docKeys));

        return [
            'document_key' => $randomKey,
            'document_value' => $docKeys[$randomKey],
        ];
    }
}
