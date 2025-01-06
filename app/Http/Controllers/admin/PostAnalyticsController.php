<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Models\Muser;
use App\Models\Chittigeographymapping;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostAnalyticsExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\Makerlebal;
use App\Models\Mregion;
use App\Models\Mcity;
use App\Models\Mcountry;
use Illuminate\Support\Facades\DB;

class PostAnalyticsController extends Controller
{
   


    public function index(Request $request)
    {
        # Show the select country, city, and region according to geography data
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        # Fetch all regions, cities, and countries
        $regions = Mregion::all();
        $cities = Mcity::all();
        $countries = Mcountry::all();

        # Initialize base query
        $chittisQuery = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country', 'likes', 'comments'])
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->select('*');

        # Paginate results
        $chittis = $chittisQuery->paginate(20);
        // dd($chittis);
        # Get the current month dates for the date picker
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $dates = [];
        while ($startDate <= $endDate) {
            $dates[] = $startDate->format('d-m-Y');
            $startDate->addDay();
        }

        return view('admin.postanalytics.post-analytics-listing', compact('dates', 'geographyOptions', 'regions', 'cities', 'countries', 'chittis'));
    }


    #this method is use for get the export data
    public function getPostAnalyticsData()
    {
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country', 'likes', 'comments'])
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->select('*')
        ->get();

        $data = [];
        if ($chittis->isEmpty()) {
            $data[] = [
                'S.No' => 1,
                'Geography' => 'No Data',
                'Area' => 'No Data',
                'Maker' => 0,
                'Checker' => 0,
                'Uploader' => 0,
                'Comments' => 0,
                'Likes' => 0,
                'App Visits' => 0,
                'Sub Title' => '--',
            ];
        } else {
            $index = 1;
            foreach ($chittis as $chitti) {
                foreach ($chitti->geographyMappings as $mapping){
                        $option = $geographyOptions->firstWhere('id', $mapping->geographyId);
                        if($option)
                            $geographies = $option->labelInEnglish;
                        else
                            $geographies = $mapping->geographyId;

                        if ($mapping->geographyId == 5 && $mapping->region)
                            $areas = $mapping->region->regionnameInEnglish;
                        elseif ($mapping->geographyId == 6 && $mapping->city)
                            $areas = $mapping->city->cityNameInEnglish ;
                        elseif ($mapping->geographyId == 7 && $mapping->country)
                            $areas = $mapping->country->countryNameInEnglish;
                        else
                            $areas = $mapping->areaId;
                }
                $data[] = [
                    'S.No' => $index,
                    'Geography' => $geographies,
                    'Area' => $areas,
                    'Maker' => $chitti->makerId ?? 0,
                    'Checker' => $chitti->checkerId ?? 0,
                    'Uploader' => $chitti->uploaderId ?? 0,
                    'Comments' => $chitti->comments->count() ?? 0,
                    'Likes' => $chitti->likes->count() ?? 0,
                    'App Visits' => $chitti->prarangApplication ?? 0,
                    'Sub Title' => $chitti->SubTitle ?? '--',
                ];

                $index++;
            }
        }
        return $data;
    }

    #this method is use for export file like csv and xsls
    public function export(Request $request)
    {
        // Retrieve the format from the query string;
        $format = $request->query('format', 'csv');

        $data = $this->getPostAnalyticsData();

        if ($format === 'csv') {
            // Define CSV headers
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="post_analytics_' . now()->format('Y-m-d') . '.csv"',
            ];

            // Generate CSV content
            $csvContent = "S.No,Geography,Area,Maker,Checker,Uploader,Comments,Likes,App Visits,Sub Title\n";

            foreach ($data as $row) {
                $csvContent .= implode(',', [
                    $row['S.No'],
                    "\"{$row['Geography']}\"",
                    "\"{$row['Area']}\"",
                    $row['Maker'],
                    $row['Checker'],
                    $row['Uploader'],
                    $row['Comments'],
                    $row['Likes'],
                    $row['App Visits'],
                    "\"{$row['Sub Title']}\""
                ]) . "\n";
            }

            return response($csvContent, 200, $headers);
        }

        if ($format === 'xlsx') {
            // Generate XLSX file using Laravel Excel
            return Excel::download(new PostAnalyticsExport($data), 'post_analytics_' . now()->format('Y-m-d') . '.xlsx');
        }
        abort(400, 'Invalid format specified.');
    }
}

?>
