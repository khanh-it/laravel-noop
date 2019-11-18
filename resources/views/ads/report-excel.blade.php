@php
// @var App\Helpers\Exporter;
$Exporter = App\Helpers\Exporter::class;
// Ads model?!
$adsModel = $model;
// Summary
$sums = [];
// Text(s)
$txts = [
    'nl' => ($nl = $Exporter::txtExcelNewline()),
    'trNl' => "<tr><td colspan=\"18\">{$nl}</td></tr>"
];
unset($nl);
@endphp
<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        table, tbody, tfoot, thead, tr, th, td {
            font-family: "Times New Roman", Arial, Helvetica, sans-serif, auto;
            mso-generic-font-family: "Times New Roman", Arial, Helvetica, sans-serif, auto;
            margin: 0;
            padding: 3;
            border: 1;
            font-size: 100%;
        }
        table {border-collapse: collapse;border-spacing: 5;}
    </style>
</head>
<body>
    <table border="1" style="border-collapse:collapse;">
        <thead>
            <tr>
                <td colspan="7" style="text-align:left; vertical-align:top">
                    <b>{{$Exporter::txtCompanyName()}}</b>
                </td>
            </tr>
            <tr>
                <td colspan="7" style="text-align:center; font-size: 25px; vertical-align:top">
                    <b>Ads Report</b>
                </td>
            </tr>
            <tr>
                <th>No.</th>
                <th>Loại</th>
                <th>IP</th>
                <th>URI tải</th>
                <th>URI click</th>
                <th>Platform/Browser</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
        @if($data)
            @foreach ($data->Rows as $index => $item)
                <tr>
                    <td align="center">{{$index + 1}}</td>
                    <td align="left" style="font-family:times new roman,times,serif">
                        {{$item->rpt_type}}
                    </td>
                    <td align="left">
                        {{$item->rpt_ips}}
                    </td>
                    <td align="left">
                        {{$item->rpt_uri_fr}}
                    </td>
                    <td align="left">
                        {{$item->rpt_uri_to}}
                    </td>
                    <td align="left">
                        {{$item->rpt_ua}}
                    </td>
                    <td align="right">
                        {{$item->rpt_created_at}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
        <tfoot>
            <tr>
                <td align="center"></td>
                <td align="right" colspan="2">Total records: </td>
                <td align="right" colspan="3">{{\number_format($data->TotalRows)}}</td>
                <td align="center"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
