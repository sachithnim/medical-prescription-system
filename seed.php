<?php
require_once("config/db.php");
require_once("seeders/DrugSeeder.php");

echo "Seeding started...\n";

// Call seeders
DrugSeeder::run($conn);

echo "Seeding completed successfully!\n";
