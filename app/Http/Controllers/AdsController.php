<?php

namespace App\Http\Controllers;

use Request, Auth;
use App\Models;
use App\Http\Controllers\Controller;
use App\Helpers\Jqx;

/**
 * @class AdsController
 */
class AdsController extends Controller
{
	/**
	 * construct
	 */
	public function __construct()
	{}

	/**
	 *
	 */
	// public function create() {}

	/**
	 *
	 */
	public function store()
	{
		// Response data
		$response = static::$jqxResponse;
		// Validate
		// \Illuminate\Validation\Validator;
		// +++
		$prefix = Models\Ads::$columnPrefix;
		$adss = [
			$prefix . 'code' => '',
            $prefix . 'name' => 'required',
            $prefix . 'spec_width' => 'required',
            $prefix . 'spec_height' => 'required',
            $prefix . 'uses' => 'required',
            $prefix . 'viewed' => 'required',
            $prefix . 'clicked' => 'required',
            'tags' => '',
            'links' => '',
			$prefix . 'note' => '',
		];
		$datafields = array_keys($adss);
		$validator = \Validator::make(Request::only($datafields), $adss);
		// +++
		if ($validator->fails()) {
			$response['errors'] = $validator->errors();
		} else {
			try {
                // Insert data
				$data = $validator->getData();
                $model = app()->make(Models\Ads::class);
                $model->fill($data);
                $result = $model->statusActive()->save();
                if ($result) {
                    $model = Models\Ads::find($model->id());
                    // +++ tags
                    $effected1st = $model->useTags($data['tags'] ?? '');
                    // +++ links
                    $model->useLinks($data['links'] ?? '');
                    $effected2nd = $model->save();
                }
                //
				$response['status'] = !!$result;
			} catch (\Exception $e) {
				$response['errors'][] = [$e->getMessage()];
			}
		}
		return response()->json($response);
	}

	/**
	 * [getEditAccount description]
	 */
	// public function edit($id) {}

	/**
	 * Update data
	 *
	 * @param int|string $id
	 * @return void
	 */
	public function update($id)
	{
		// Response data
		$response = static::$jqxResponse;
		// Validate
		// \Illuminate\Validation\Validator;
		// +++
		$prefix = Models\Ads::$columnPrefix;
		$adss = [
			($ID = ($prefix . 'id')) => 'required',
            $prefix . 'name' => 'required',
            $prefix . 'spec_width' => 'required',
            $prefix . 'spec_height' => 'required',
            $prefix . 'uses' => 'required',
            $prefix . 'viewed' => 'required',
            $prefix . 'clicked' => 'required',
            'tags' => '',
            'links' => '',
			$prefix . 'note' => '',
			$prefix . 'status' => 'required',
		];
		$datafields = array_keys($adss);
		$validator = \Validator::make(Request::only($datafields), $adss);
		// +++
		if ($validator->fails()) {
			$response['errors'] = $validator->errors();
		} else {
			try {
				// Update data
				$data = $validator->getData();
				$model = Models\Ads::find($data[$ID]);
				if ($model) {
                    $model->fill($data);
                    // +++ links
                    $model->useLinks($data['links'] ?? '');
                    $result = $model->save();
                    // +++ tags
                    if ($result) {
                        $effected = $model->useTags($data['tags'] ?? '');
                    }
				}
				$response['status'] = !!$result;
			} catch (\Exception $e) {
				$response['errors'][] = [$e->getMessage()];
			}
		}
		return response()->json($response);
	}

	/**
	 * Show details :: update
	 */
	protected function _showUpdate(Models\Ads $model)
	{
		// @var Illuminate\Http\Request
		$request = Request::getFacadeRoot();
		if ($request->isMethod('POST')) {
			// Response data
			$response = static::$jqxResponse;
			// Validate
			// \Illuminate\Validation\Validator;
			// +++
			$adss = [
				'data' => '',
			];
			$datafields = array_keys($adss);
			$validator = \Validator::make(Request::only($datafields), $adss);
			// +++
			if ($validator->fails()) {
				$response['errors'] = $validator->errors();
			} else {
				try {
					// Update data
					$data = $validator->getData();
					$model->setColVal('content', $data['data']);
					$result = $model->save();
					$response['status'] = !!$result;
				} catch (\Exception $e) {
					$response['errors'][] = [$e->getMessage()];
				}
			}
			return $response;
		}
	}

	/**
	 * Show details
	 */
	public function show($id, Request $request)
	{
		// Fetch data
		$model = Models\Ads::find($id);
		if (!$model) {
			abort(404);
		}

		// Update data?
		$response = $this->_showUpdate($model);
		if ($response) {
			return response()->json($response);
		}

		// Init widgets
		// +++ layout
		\JqxLayout::setJsData([
			'routes' => ($routes = [
				($rName = 'index') => (route(($rPrefix = 'ads::') . $rName)),
				($rName = 'update') => $routeShow = (route($rPrefix . 'show', $id)),
			]),
			'major' => ($majorData = [])
		]);
		//.end

		// Render view
		return view('ads.details', compact([ 'model' ]));
	}

	/**
	 * Export|Print
	 */
	public function export($id, Request $request)
	{
		// Fetch data
		$model = Models\Ads::find($id);
		if (!$model) {
			abort(404);
		}

		// Render view
		return view('ads.export', compact([ 'model' ]));
	}

	public function destroy($id)
	{
		// Response data
		$response = static::$jqxResponse;
		// Validate
		//...
		try {
			// delete data
			$model = Models\Ads::find($id);
			if ($model) {
				$result = $model->delete();
			}
			$response['status'] = !!$result;
		} catch (\Exception $e) {
			$response['errors'][] = [$e->getMessage()];
		}
		return response()->json($response);
	}

	/**
	 * Handle request of list (read) data
	 * @param array $data
	 * @param Grid $jqxGrid
	 * @return void
	 */
	public function read($data, $jqxLayout)
	{
		// Prepare the data
        $readtype = Request::get('_readtype');
        $alterData = Request::get('_alterData');
        // +++ --- alter data?
        if (\is_callable($alterData)) {
            $alterData($data);
        }
        $model;
		switch ($readtype) {
            case 'report':
                $model = app()->make(Models\AdsReport::class);
                $adsModel = Request::get('ads');
                ($adsModel instanceof Models\Ads) && $model->forAds($adsModel);
                break;
			default:
				$model = app()->make(Models\Ads::class);
		}
		$qB; $totalRowsQB;
		$rows = $model->jqxFetchRecordList($data, $qB, $totalRowsQB);
		$totalRows = $totalRowsQB->count();
		//.end
		// Response
		return $jqxLayout->responseJqxGrid($rows, $totalRows);
	}

	/**
	 * Report
	 */
	public function report($id = null, Request $request)
	{
		// Fetch data
		$model = $id ? Models\Ads::find($id) : null;
		if ($id && !$model) {
			abort(404);
        }
        /* DEBUG: if ($model) { Models\AdsReport::updateAdsReportStats($model->id()); }
        else { Models\AdsReport::clearAdsReportStats(); }
        die(); */
        // +++
        $act = strtolower($request::query('act'));
        // +++ --- report delete?!
        $isActRptDel = ('rpt-del' === $act) || ('rpt-del-all' === $act);
        // +++ --- report excel?!
        $isActRptExcel = ('rpt-excel' === $act) || ('rpt-excel-all' === $act);
        // +++ primary ids
        $pid = trim($request::query('pid'));
        if ($pid) {
            $pid = explode(',', $pid);
        }

        // Handle CRUD requests!
        Request::merge([
            '_readtype' => 'report',
            'ads' => $model,
            '_alterData' => function(&$data) use ($isActRptDel, $pid) {
                $data['pid'] = $pid;
                // +++ report delete
                $isActRptDel && ($data['_delete'] = true);
            },
        ]);
        //.end
		$response = \JqxLayout::onRequestRead([$this, 'read']);
		if ($response) {
            // Report delete?!
            if ($isActRptDel) {
                return redirect()->back();
            }
            //.end
            // Report excel?!
            // +++ Render view --> download file?!
            if ($isActRptExcel) {
                // Render view --> download file?!
                \App\Helpers\Exporter::downloadExcel($filename = "AdsReport" . ("_" . \date('YmdHi')));
                $data = $response->getData();
                return view('ads.report-excel', compact([ 'model', 'data' ]));
            }
            //.end

            // Normal response --> list data!
			return $response;
		}
		//.end

		// Handle first page load request!
		// Init widgets
		// +++ layout
		\JqxLayout::setJsData([
			'routes' => $routes = []
		]);
		// +++ ads
		// +++ +++ grid
		$adsDfds; $adsCgs;
		$adsColumns = Models\AdsReport::jqxGridColumns($adsDfds, $adsCgs);
		$jqxGrid = app()->make(Jqx\Grid::class);
		// @TODO: tat tinh nang filter, do bug groups --> hien thi sai khi co filter!
		$jqxGrid->setProps([
            'selectionmode' => 'checkbox',
            'altrows' => false,
        ]);
		$jqxGrid->addColumns($adsColumns, $adsDfds, $adsCgs);
		// +++ +++ window:
		$jqxWindow = app()->make(Jqx\Window::class, ['options' => [
			'props' => [
				'title' => 'Ads Report',
				'width' => 680,
				'height' => 540,
				'autoOpen' => false,
				'isModal' => true,
			]
        ]]);
		// +++ +++ form: CRUD ads
		$jqxForm = app()->make(Jqx\Form::class);

        // Render view
        $view = 'ads.report';
		return view($view, compact([
            'model',
			'jqxGrid',
            'jqxWindow',
		]));
	}

	/**
	 * Account managent
	 *
	 * @return void
	 */
	public function index()
	{
		// Handle CRUD requests!
		$response = \JqxLayout::onRequestRead([$this, 'read']);
		if ($response) {
			return $response;
		}
		//.end

		// Handle first page load request!
		// Init widgets
		// +++ layout
		\JqxLayout::setJsData([
			'routes' => $routes = [
				// ads
				'index' => route('ads::index'),
				'create' => route('ads::create'),
				'store' => route('ads::store'),
                'edit' => route('ads::edit', '_id_'),
                'show' => route('ads::show', '_id_'),
				'update' => route('ads::update', '_id_'),
				'delete' => route('ads::destroy', '_id_'),
                'report' => route('ads::report', '_id_'),
			]
		]);
		// +++ ads
		// +++ +++ grid
		$adsDfds; $adsCgs;
		$adsColumns = Models\Ads::jqxGridColumns($adsDfds, $adsCgs);
		$jqxGrid = app()->make(Jqx\Grid::class);
		// @TODO: tat tinh nang filter, do bug groups --> hien thi sai khi co filter!
		// $jqxGrid->setProps([]);
		$jqxGrid->addColumns($adsColumns, $adsDfds, $adsCgs);
		// +++ +++ window: CRUD cho ads!
		$jqxWindow = app()->make(Jqx\Window::class, ['options' => [
			'props' => [
				'title' => 'Ads',
				'width' => 680,
				'height' => 520,
				'autoOpen' => false,
				'isModal' => true,
			]
        ]]);
        // +++ +++ window: show embedded code!
		$jqxWindowCode = app()->make(Jqx\Window::class, ['options' => [
            'selector' => '#jqxWindowCode',
			'props' => [
				'title' => 'Embedded script',
				'width' => 680,
				'height' => 560,
				'autoOpen' => false,
				'isModal' => true,
			]
		]]);
		// +++ +++ form: CRUD ads
		$jqxForm = app()->make(Jqx\Form::class);

		// Render view
		return view('ads.index', compact([
			'jqxGrid',
			// 'jqxForm',
            'jqxWindow',
            'jqxWindowCode',
		]));
	}
}
