@php
// Define buttons
$__stdButtons = [
    // insert
    'insert' => [
        'iconClass' => 'fa fa-floppy-o',
        'text' => 'Lưu',
        'attrs' => [
            'class' => 'btn btn-sm btn-primary',
            'data-route' => 'store'
        ]
    ],
    'insert_n_close' => [
        'iconClass' => 'fa fa-files-o',
        'text' => 'Lưu &amp; Đóng',
        'attrs' => [
            'class' => 'btn btn-sm btn-success',
            'data-route' => 'store',
            'data-windowaction' => 'hide'
        ]
    ],
    // update
    'update' => [
        'iconClass' => 'fa fa-floppy-o',
        'text' => 'Lưu',
        'attrs' => [
            'class' => 'btn btn-sm btn-primary',
            'data-route' => 'update'
        ]
    ],
    'update_n_close' => [
        'iconClass' => 'fa fa-files-o',
        'text' => 'Lưu &amp; Đóng',
        'attrs' => [
            'class' => 'btn btn-sm btn-success',
            'data-route' => 'update',
            'data-windowaction' => 'hide'
        ]
    ],
    // hide|close
    'hide' => [
        'iconClass' => 'fa fa-times',
        'text' => 'Hủy',
        'attrs' => [
            'class' => 'btn btn-sm btn-danger',
            'data-windowaction' => 'hide'
        ]
    ],
];
if (is_null($buttons)) {
    $buttons = $__stdButtons;
}
if (is_array($buttons) && (true === $buttons[0])) {
    unset($buttons[0]);
    $buttons = array_merge($__stdButtons, $buttons);
}
if (is_callable($buttons)) {
    $buttons = $buttons($__stdButtons);
}
$buttons = (array)$buttons;
//.end
@endphp
<div class="col-xs-12">
@foreach ($buttons as $button)
    {!! Html::link(
        $button['href'] ?: '#',
        "<i class=\"{$button['iconClass']}\"></i> {$button['text']}",
        array_replace([ 'role' => 'button' ], (array)$button['attrs']),
        null, false
    ) !!}
@endforeach
</div>
