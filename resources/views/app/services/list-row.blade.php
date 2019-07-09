<div class="crud-list-row">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <p><a href="{{ route('services.show', ['room' => Hashids::encode($row->id)]) }}">{{ $row->description }}</a></p>
        </div>
        <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 visible-md visible-lg">
            <p><a href="{{ route('services.show', ['room' => Hashids::encode($row->id)]) }}">{{ number_format($row->price, 2, ',', '.') }}</a></p>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            @include('partials.dropdown-btn', [
                'options' => [
                    [
                        'option' => trans('common.edit'),
                        'url' => route('services.edit', [
                            'room' => Hashids::encode($row->id)
                        ]),
                    ],
                    [
                        'type' => 'confirm',
                        'option' => trans('common.delete'),
                        'url' => route('services.destroy', [
                            'room' => Hashids::encode($row->id)
                        ]),
                        'method' => 'DELETE'
                    ],
                ]
            ])
        </div>
    </div>
</div>