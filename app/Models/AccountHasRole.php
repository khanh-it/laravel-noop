<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountHasRole extends Account
{
    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Mã nhân viên',
            'datafield' => 'code',
            'width' => 90,
            'pinned' => true,
        ],
        [
            'text' => 'Họ và tên',
            'datafield' => 'fullname',
            'pinned' => true,
        ],
        [ 'datafield' => 'position_id' ],
        [
            'text' => 'Chức vụ',
            'datafield' => [ ['position_name'] ],
            'filtertype' => 'list',
            'sortable' => false,
            'pinned' => true,
        ],
        [ 'datafield' => [ ['role_id'] ]],
        [
            'text' => 'Vai trò',
            'datafield' => [ ['role_name'] ],
            'filtertype' => 'list',
            'sortable' => false,
            'cellsalign' => 'center',
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
    ];

    /**
     * Get Salary\Basic.
     * @return Basic|null
     */
    public function modelHasRole()
    {
        return $this->hasMany(ModelHasRole::class, ModelHasRole::columnName('model_id'));
    }
    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if (in_array($dfd, [
                'role_name'
            ])
        ) {
            $col = array_replace($col, [
                'columntype' => 'custom',
                'createeditor' => "{!!window.mkJqxGridEditor(0, '{$dfd}')!!}",
                'initeditor' => "{!!window.mkJqxGridEditor(1, '{$dfd}')!!}",
                // 'geteditorvalue' => "{!!window.mkJqxGridEditor(2, '{$dfd}')!!}",
            ]);
        }

        if ('role_name' === $col['datafield']) {
            $col['filteritems'] = array_values(Roles::makeList());
        }

        return $col;
    }

    public static function makeListAccountHasRole(array $options = array()) {

        $self = app()->make(static::class);
        $collect = static::with('modelHasRole')->get()->mapWithKeys(function($item) use ($self, $options) {
            return [$item[$self->getKeyName()] => ($item->modelHasRole[0]['role_id'])
            ];
        });
        return $collect->toArray();
    }
    /**
     *
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null) {
        // Format data
		// Prepare the data
		$qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB) {
                $models = [
                    // 'com' => app()->make(Company::class),
                    'modelHasRole' => app()->make(ModelHasRole::class),
                    'role' => app()->make(Roles::class),
                    'pos' => app()->make(Position::class),
                ];
                $modelTable = $this->getTable();
                
                $qB->leftJoin(
                    ($tableMHRole = $models['modelHasRole']->getTable())
                    , "{$tableMHRole}.model_id"
                    , '=', "{$modelTable}." . $this->getKeyName()
                );
                // Join position
                $qB->leftJoin(
                    ($tablerRole = $models['role']->getTable())
                    , ("{$tablerRole}." . ($pK = $models['role']->getKeyName()))
                    , '=', "{$tableMHRole}.role_id"
                );
                
                // Join position
                $qB->leftJoin(
                    ($tablePos = $models['pos']->getTable())
                    , ("{$tablePos}." . ($pK = $models['pos']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );

                //SẮP XẾP THEO CHỨC DANH
                $qB->orderBy("{$modelTable}.account_position_id", 'ASC');
                
                // Select
                $qB->select([
                    "{$modelTable}.account_id",
                    "{$modelTable}.account_code",
                    "{$modelTable}.account_fullname",
                    "{$modelTable}.account_position_id",
                    // "{$tableMHRole}.*",
                    "{$tablerRole}.name as role_name",
                    "{$tablerRole}.id as role_id",
                    "{$tablePos}.position_name",
                ]);
            },
        ]);
		// +++
        $rows = $qB->get()->map(function($row, $idx){
                $prop;
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
	}
}
