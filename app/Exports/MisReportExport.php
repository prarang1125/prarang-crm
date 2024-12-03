<?php

namespace App\Exports;

use App\Models\Misreport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MisReportExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $geography;

    public function __construct($startDate, $endDate, $geography)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->geography = $geography;
    }

    public function collection()
    {
        $query = Misreport::query();

        if ($this->geography !== 'All') {
            $query->where('Id', $this->geography);
        }

        $query->whereBetween('CreatedDate', [$this->startDate, $this->endDate]);

        return $query->get([
            'UserName',
            'MobileNumber',
            'EmailId',
            'DateOfJoining',
            'AppDownloadDate',
            'TotalColorFeeds',
            'AppUsageTime',
            'FeedsComments',
            'FeedsLikes',
            'FeedShares',
            'SavedBank',
            'LucknowColorFeeds',
            'MeerutColorFeeds',
            'RampurColorFeeds',
            'JaunpurColorFeeds',
        ]);
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Mobile Number',
            'Email ID',
            'Date Of Joining',
            'App Download Date',
            'Total Color Feeds',
            'App Usage Time',
            'Feeds Comments',
            'Feeds Likes',
            'Feed Shares',
            'Saved Bank',
            'Lucknow Color Feeds',
            'Meerut Color Feeds',
            'Rampur Color Feeds',
            'Jaunpur Color Feeds',
        ];
    }
}
