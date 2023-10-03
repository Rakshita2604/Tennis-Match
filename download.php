<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();

// Get the active sheet
$sheet = $spreadsheet->getActiveSheet();

// Set the headers for the Excel file
$sheet->setCellValue('A1', 'Match Date');
$sheet->setCellValue('B1', 'Player 1');
$sheet->setCellValue('C1', 'Player 2');
$sheet->setCellValue('D1', 'Referee');

// You need to fetch match data from your database and populate the Excel file here
// For this example, I'll create sample data

// Sample match data
$matches = [
    ['2023-10-03', 'Player A', 'Player B', 'Referee_1'],
    // Add more match data here
];

// Populate the Excel file with match data
$row = 2;
foreach ($matches as $match) {
    $sheet->setCellValue('A' . $row, $match[0]); // Match Date
    $sheet->setCellValue('B' . $row, $match[1]); // Player 1
    $sheet->setCellValue('C' . $row, $match[2]); // Player 2
    $sheet->setCellValue('D' . $row, $match[3]); // Referee
    $row++;
}

// Set the file name and type
$filename = 'match_schedule.xlsx';

// Redirect output to a client's web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Create Excel file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;
?>
