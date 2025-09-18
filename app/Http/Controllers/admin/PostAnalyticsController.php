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
        $request->validate([
            'search' => 'nullable|string|max:255',
            'geography' => 'nullable|integer',
            'area' => 'nullable|integer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'sort_by' => 'nullable|string|in:chittiId,makerId,checkerId,uploaderId,comments_count,likes_count,prarangApplication,SubTitle,dateOfCreation',
            'sort_order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|in:10,30,50,100',
        ]);

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        $regions = Mregion::all();
        $cities = Mcity::all();
        $countries = Mcountry::all();

        $chittisQuery = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])->withCount(['likes', 'comments'])
            ->whereNotNull('Title')
            ->where('Title', '!=', '');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $chittisQuery->where(function ($q) use ($search) {
                $q->where('Title', 'like', "%{$search}%")
                    ->orWhere('SubTitle', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('geography')) {
            $chittisQuery->whereHas('geographyMappings', function ($q) use ($request) {
                $q->where('geographyId', $request->input('geography'));
            });
        }
        if ($request->filled('area')) {
            $chittisQuery->whereHas('geographyMappings', function ($q) use ($request) {
                $q->where('areaId', $request->input('area'));
            });
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('from_date'))->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->input('to_date'))->endOfDay();
            $chittisQuery->whereBetween(DB::raw("STR_TO_DATE(dateOfCreation, '%d-%b-%y %H:%i:%s')"), [$startDate, $endDate]);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'dateOfCreation');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($sortBy == 'dateOfCreation') {
            $chittisQuery->orderBy(DB::raw("STR_TO_DATE(dateOfCreation, '%d-%b-%y %H:%i:%s')"), $sortOrder);
        } else {
            $chittisQuery->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->input('per_page', 30);
        $chittis = $chittisQuery->paginate($perPage);

        return view('admin.postanalytics.post-analytics-listing', compact('geographyOptions', 'regions', 'cities', 'countries', 'chittis'));
    }


    #this method is use for get the export data
    public function getPostAnalyticsData(Request $request)
    {
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        $chittisQuery = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])->withCount(['likes', 'comments'])
            ->whereNotNull('Title')
            ->where('Title', '!=', '');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $chittisQuery->where(function ($q) use ($search) {
                $q->where('Title', 'like', "%{$search}%")
                    ->orWhere('SubTitle', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('geography')) {
            $chittisQuery->whereHas('geographyMappings', function ($q) use ($request) {
                $q->where('geographyId', $request->input('geography'));
            });
        }
        if ($request->filled('area')) {
            $chittisQuery->whereHas('geographyMappings', function ($q) use ($request) {
                $q->where('areaId', $request->input('area'));
            });
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('from_date'))->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->input('to_date'))->endOfDay();
            $chittisQuery->whereBetween(DB::raw("STR_TO_DATE(dateOfCreation, '%d-%b-%y %H:%i:%s')"), [$startDate, $endDate]);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'dateOfCreation');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($sortBy == 'dateOfCreation') {
            $chittisQuery->orderBy(DB::raw("STR_TO_DATE(dateOfCreation, '%d-%b-%y %H:%i:%s')"), $sortOrder);
        } else {
            $chittisQuery->orderBy($sortBy, $sortOrder);
        }

        $chittis = $chittisQuery->get();

        $data = [];
        if ($chittis->isEmpty()) {
            $data[] = [
                'S.No' => 1,
                'Geography' => 'No Data',
                'Area' => 'No Data',
                'Comments' => 0,
                'Likes' => 0,
                'App Visits' => 0,
                'Sub Title' => '--',
            ];
        } else {
            $index = 1;
            foreach ($chittis as $chitti) {
                if ($chitti->geographyMappings->isNotEmpty()) {
                    foreach ($chitti->geographyMappings as $mapping) {
                        $option = $geographyOptions->firstWhere('id', $mapping->geographyId);
                        $geographies = $option ? $option->labelInEnglish : $mapping->geographyId;

                        if ($mapping->geographyId == 5 && $mapping->region) {
                            $areas = $mapping->region->regionnameInEnglish;
                        } elseif ($mapping->geographyId == 6 && $mapping->city) {
                            $areas = $mapping->city->citynameInEnglish;
                        } elseif ($mapping->geographyId == 7 && $mapping->country) {
                            $areas = $mapping->country->countryNameInEnglish;
                        } else {
                            $areas = $mapping->areaId;
                        }

                        $data[] = [
                            'S.No' => $index,
                            'Geography' => $geographies,
                            'Area' => $areas,
                            'Comments' => $chitti->comments_count ?? 0,
                            'Likes' => $chitti->likes_count ?? 0,
                            'App Visits' => $chitti->prarangApplication ?? 0,
                            'Sub Title' => $chitti->SubTitle ?? '--',
                        ];
                        $index++;
                    }
                } else {
                    $data[] = [
                        'S.No' => $index,
                        'Geography' => 'No Data',
                        'Area' => 'No Data',
                        'Comments' => $chitti->comments_count ?? 0,
                        'Likes' => $chitti->likes_count ?? 0,
                        'App Visits' => $chitti->prarangApplication ?? 0,
                        'Sub Title' => $chitti->SubTitle ?? '--',
                    ];
                    $index++;
                }
            }
        }
        return $data;
    }

    #this method is use for export file like csv and xsls
    public function export(Request $request)
    {
        // Retrieve the format from the query string;
        $format = $request->query('format', 'csv');

        $data = $this->getPostAnalyticsData($request);

        if ($format === 'csv') {
            // Define CSV headers
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="post_analytics_' . now()->format('Y-m-d') . '.csv"',
            ];

            // Generate CSV content
            $csvContent = "S.No,Geography,Area,Comments,Likes,App Visits,Sub Title\n";

            foreach ($data as $row) {
                $csvContent .= implode(',', [
                    $row['S.No'],
                    "\"{$row['Geography']}\"",
                    "\"{$row['Area']}\"",
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
