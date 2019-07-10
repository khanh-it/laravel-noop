<?php

namespace App\Http\Controllers;

use Request, Auth;
use App\Models;
use App\Http\Controllers\Controller;
use App\Helpers\Jqx;

/**
 * @class TagController
 */
class TagController extends Controller
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
		$prefix = Models\Tag::$columnPrefix;
		$tags = [
			$prefix . 'code' => '',
			$prefix . 'name' => 'required',
			$prefix . 'note' => '',
		];
		$datafields = array_keys($tags);
		$validator = \Validator::make(Request::only($datafields), $tags);
		// +++
		if ($validator->fails()) {
			$response['errors'] = $validator->errors();
		} else {
			try {
				// Insert data
				$data = $validator->getData();
                $model = app()->make(Models\Tag::class);
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
		$prefix = Models\Tag::$columnPrefix;
		$tags = [
			($ID = ($prefix . 'id')) => 'required',
            $prefix . 'name' => 'required',
            $prefix . 'uses' => 'required',
			$prefix . 'note' => '',
			$prefix . 'status' => 'required',
		];
		$datafields = array_keys($tags);
		$validator = \Validator::make(Request::only($datafields), $tags);
		// +++
		if ($validator->fails()) {
			$response['errors'] = $validator->errors();
		} else {
			try {
				// Update data
				$data = $validator->getData();
				$model = Models\Tag::find($data[$ID]);
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
	protected function _showUpdate(Models\Tag $model)
	{
		// @var Illuminate\Http\Request
		$request = Request::getFacadeRoot();
		if ($request->isMethod('POST')) {
			// Response data
			$response = static::$jqxResponse;
			// Validate
			// \Illuminate\Validation\Validator;
			// +++
			$tags = [
				'data' => '',
			];
			$datafields = array_keys($tags);
			$validator = \Validator::make(Request::only($datafields), $tags);
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
		$model = Models\Tag::find($id);
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
				($rName = 'index') => (route(($rPrefix = 'tag::') . $rName)),
				($rName = 'update') => $routeShow = (route($rPrefix . 'show', $id)),
			]),
			'major' => ($majorData = [])
		]);
		//.end

		// Render view
		return view('tag.details', compact([ 'model' ]));
	}

	/**
	 * Export|Print
	 */
	public function export($id, Request $request)
	{
		// Fetch data
		$model = Models\Tag::find($id);
		if (!$model) {
			abort(404);
		}

		// Render view
		return view('tag.export', compact([ 'model' ]));
	}

	public function destroy($id)
	{
		// Response data
		$response = static::$jqxResponse;
		// Validate
		//...
		try {
			// delete data
			$model = Models\Tag::find($id);
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
		$model;
		switch ($readtype) {
			default:
				$model = app()->make(Models\Tag::class);
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
				// tag
				'index' => route('tag::index'),
				'create' => route('tag::create'),
				'store' => route('tag::store'),
				'edit' => route('tag::edit', '_id_'),
				'update' => route('tag::update', '_id_'),
				'delete' => route('tag::destroy', '_id_'),
			]
		]);
		// +++ tag
		// +++ +++ grid
		$tagDfds; $tagCgs;
		$tagColumns = Models\Tag::jqxGridColumns($tagDfds, $tagCgs);
		$jqxGrid = app()->make(Jqx\Grid::class);
		// @TODO: tat tinh nang filter, do bug groups --> hien thi sai khi co filter!
		// $jqxGrid->setProps([]);
		$jqxGrid->addColumns($tagColumns, $tagDfds, $tagCgs);
		// +++ +++ window: CRUD cho tag!
		$jqxWindow = app()->make(Jqx\Window::class, ['options' => [
			'props' => [
				'title' => 'Tag',
				'width' => 680,
				'height' => 340,
				'autoOpen' => false,
				'isModal' => true,
			]
		]]);
		// +++ +++ form: CRUD tag
		$jqxForm = app()->make(Jqx\Form::class);

		// Render view
		return view('tag.index', [
			'jqxGrid' => $jqxGrid,
			// 'jqxForm' => $jqxForm,
			'jqxWindow' => $jqxWindow,
		]);
	}
}
