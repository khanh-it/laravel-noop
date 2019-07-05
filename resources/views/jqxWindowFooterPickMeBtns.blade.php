@include('jqxWindowFooterBtns', ['buttons' => [
    'pick_n_close' => [
        'iconClass' => 'fa fa-floppy-o',
        'text' => 'Chọn',
        'attrs' => [
            'class' => 'btn btn-sm btn-primary',
            'data-windowaction' => 'pick_n_close'
        ]
    ],
    'insert' => [
        'iconClass' => 'fa fa-files-o',
        'text' => 'Thêm',
        'attrs' => [
            'class' => 'btn btn-sm btn-success',
            'data-windowaction' => 'store'
        ]
    ],
    'hide' => [
        'iconClass' => 'fa fa-times',
        'text' => 'Hủy',
        'attrs' => [
            'class' => 'btn btn-sm btn-danger',
            'data-windowaction' => 'hide'
        ]
    ],
]])