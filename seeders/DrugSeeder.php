<?php
require_once(__DIR__ . '/../config/db.php');

class DrugSeeder
{
    public static function run($conn)
    {
        $drugs = [
            ['Paracetamol 500mg', 5.00],
            ['Amoxicillin 250mg', 10.00],
            ['Ibuprofen 200mg', 7.50],
            ['Ciprofloxacin 500mg', 14.00],
            ['Metformin 500mg', 12.00],
            ['Atorvastatin 20mg', 15.00],
            ['Amlodipine 5mg', 6.50],
            ['Losartan 50mg', 8.00],
            ['Pantoprazole 40mg', 9.90],
            ['Cetirizine 10mg', 8.00],
            ['Loratadine 10mg', 7.80],
            ['Omeprazole 20mg', 10.50],
            ['Salbutamol Inhaler', 22.00],
            ['Azithromycin 250mg', 18.00],
            ['Clopidogrel 75mg', 19.00],
            ['Hydrochlorothiazide 25mg', 6.00],
            ['Aspirin 81mg', 4.00],
            ['Simvastatin 10mg', 10.00],
            ['Ranitidine 150mg', 8.20],
            ['Prednisolone 5mg', 5.30],
            ['Doxycycline 100mg', 12.00],
            ['Nifedipine 10mg', 9.00],
            ['Warfarin 5mg', 7.50],
            ['Furosemide 40mg', 8.50],
            ['Gabapentin 300mg', 14.00],
            ['Levothyroxine 50mcg', 6.75],
            ['Insulin Glargine (Pen)', 120.00],
            ['Insulin Regular (Vial)', 95.00],
            ['Budesonide Inhaler', 40.00],
            ['Montelukast 10mg', 11.00],
            ['Fluconazole 150mg', 13.50],
            ['Erythromycin 250mg', 14.00],
            ['Clindamycin 300mg', 15.50],
            ['Zinc Sulphate 20mg', 3.50],
            ['Vitamin C 500mg', 4.25],
            ['Folic Acid 5mg', 2.00],
            ['Iron Supplement 100mg', 4.80],
            ['Calcium Carbonate 500mg', 6.20],
            ['Magnesium 250mg', 5.90],
            ['Domperidone 10mg', 7.30],
            ['Loperamide 2mg', 3.60],
            ['Ondansetron 4mg', 9.00],
            ['Chlorpheniramine 4mg', 2.50],
            ['Phenylephrine 10mg', 3.20],
            ['Codeine 15mg', 12.75],
            ['Tramadol 50mg', 15.00],
            ['Diazepam 5mg', 8.40],
            ['Lorazepam 2mg', 10.90],
            ['Sertraline 50mg', 14.20],
            ['Fluoxetine 20mg', 13.80],
        ];

        foreach ($drugs as $drug) {
            $stmt = $conn->prepare("INSERT INTO drug_prices (drug_name, unit_price) VALUES (?, ?)");
            $stmt->bind_param("sd", $drug[0], $drug[1]);
            $stmt->execute();
        }

        echo "DrugSeeder: " . count($drugs) . " drugs inserted.\n";
    }
}
