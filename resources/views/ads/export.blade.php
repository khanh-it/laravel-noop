<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{{$model->ads_code ? ("[{$model->ads_code}]") : ''}} {{$model->ads_name}}</title>
    <script>
        /**
         * system/utility/ads.export
         */
        window.onload = function() {
            window.print();
            // window.close();
        }
    </script>
    <style>
        @media print {
            .print-hidden{display:none;}
        }
        .btn-box{text-align: right;padding: 5px;border: solid 1px grey;background-color: silver;}
        .btn-close{font-size:2em;background-color: red;color: white;border: solid 2px #d70000;display: inline-block;padding: 3px 9px;text-decoration: none;}
    </style>
</head>
<body>
    <div class="btn-box print-hidden">
        <a id="btn-close" class="btn btn-close" href="javascript:void(0);" onclick="window.close();">X</a><br clear="all" />
    </div>
    {!!$model->ads_content!!}
</body>
</html>
