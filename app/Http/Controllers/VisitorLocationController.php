<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class VisitorLocationController extends Controller

    {
        /**
         * Store the visitor location data.
         *
         * @param Request $request
         * @return \Illuminate\Http\Response
         * @author Vivek Yadav <dev.vivek16@gmail.com>
         */
        public function storeVisitorLocation(Request $request)
        {
            // Get the current date
             $currentDate = Carbon::now();
    
            // Determine the dynamic month range and file name
            $monthRange = $this->getMonthRange($currentDate);
            
            // Construct the file path for the CSV
            $filePath = storage_path('app/visitor/' . $monthRange . '.csv');
    
            // Prepare the data to be saved
            $visitorData = [
                $request->input('currentUrl'),
                $request->input('ipAddress'),
                $request->input('latitude'),
                $request->input('longitude'),
                // $request->input('browserInfo'),
                $request->input('language'),
                $request->input('screenWidth'),
                $request->input('screenHeight'),
                $request->input('timestamp')
            ];
    
            // Check if the file exists, if not, add a header row
            if (!file_exists($filePath)) {
                $header = ['Current URL', 'IP Address', 'Latitude', 'Longitude', 'Language', 'Screen Width', 'Screen Height', 'Timestamp'];
                $this->appendToCSV($filePath, $header);
            }
    
            // Append the visitor data to the CSV file
            $this->appendToCSV($filePath, $visitorData);
    
            return response()->json(['message' => 'Data stored successfully'], 200);
        }
    
        /**
         * Determine the month range based on the current date.
         *
         * @param Carbon $date
         * @return string
         */
        private function getMonthRange(Carbon $date)
        {
            $year = $date->year;
    
            // Define the month ranges
            if ($date->between(Carbon::create($year, 1, 1), Carbon::create($year, 4, 30))) {
                return 'jan-april-' . $year;
            } elseif ($date->between(Carbon::create($year, 5, 1), Carbon::create($year, 8, 31))) {
                return 'may-aug-' . $year;
            } elseif ($date->between(Carbon::create($year, 9, 1), Carbon::create($year, 12, 31))) {
                return 'sep-dec-' . $year;
            }
    
            // Default case (should never be hit if the above ranges cover all months)
            return 'other-' . $year;
        }
    
        /**
         * Append data to a CSV file.
         *
         * @param string $filePath
         * @param array $data
         * @return void
         */
        private function appendToCSV(string $filePath, array $data)
        {
            $file = fopen($filePath, 'a');
            fputcsv($file, $data);
            fclose($file);
        }
}
