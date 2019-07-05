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
            $prefix . 'uses' => 'required',
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
					$result = $model->save();
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
		$model;
		switch ($readtype) {
			case 'adstype':
				$model = app()->make(Models\AdsType::class);
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
				'update' => route('ads::update', '_id_'),
				'delete' => route('ads::destroy', '_id_'),
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
				'height' => 340,
				'autoOpen' => false,
				'isModal' => true,
			]
		]]);
		// +++ +++ form: CRUD ads
		$jqxForm = app()->make(Jqx\Form::class);

		// Render view
		return view('ads.index', [
			'jqxGrid' => $jqxGrid,
			// 'jqxForm' => $jqxForm,
			'jqxWindow' => $jqxWindow,
		]);
	}
}
