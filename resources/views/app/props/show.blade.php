@extends('layouts.panel')

@section('breadcrumbs')
    {{ Breadcrumbs::render('prop', $prop) }}
@endsection

@section('content')

    <div id="page-wrapper">
        @include('partials.page-header', [
            'title' => trans('props.title'),
            'url' => route('props.index'),
            'options' => [
                [
                    'option' => trans('common.reports'),
                    'url' => route('props.prop.report', [
                        'id' => Hashids::encode($prop->id)
                    ])
                ],
                [
                    'option' => 'Transacciones',
                    'url' => route('props.vouchers.create')
                ],
                [
                    'type' => 'dropdown',
                    'option' => trans('common.options'),
                    'url' => [
                        [
                            'option' => trans('common.new'),
                            'url' => route('props.create')
                        ],
                        [
                            'option' => trans('common.edit'),
                            'url' => route('props.edit', [
                                'id' => Hashids::encode($prop->id)
                            ]),
                        ],
                        [
                            'type' => 'confirm',
                            'option' => trans('common.delete.item'),
                            'url' => route('props.destroy', [
                                'id' => Hashids::encode($prop->id)
                            ]),
                            'method' => 'DELETE'
                        ],
                    ]
                ],
                [
                    'option' => trans('common.back'),
                    'url' => url()->previous()
                ],
            ]
        ])

        @include('app.props.info')

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="vouchers-tab" data-toggle="tab" href="#vouchers" role="tab" aria-controls="vouchers" aria-selected="true">
                    @lang('transactions.title')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="charts-tab" data-toggle="tab" href="#charts" role="tab" aria-controls="charts" aria-selected="false">
                    @lang('common.chart')
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="vouchers" role="tabpanel" aria-labelledby="vouchers-tab">
                @include('partials.list', [
                    'data' => $prop->vouchers->take(20),
                    'listHeading' => 'app.props.vouchers.list-heading',
                    'listRow' => 'app.props.vouchers.list-row'
                ])
            </div>
            <div class="tab-pane fade" id="charts" role="tabpanel" aria-labelledby="charts-tab">
                <canvas id="myChart"></canvas>
            </div>
        </div>

        @include('partials.modal-confirm')
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    translator.trans('months.january'),
                    translator.trans('months.february'),
                    translator.trans('months.march'),
                    translator.trans('months.april'),
                    translator.trans('months.may'),
                    translator.trans('months.june'),
                    translator.trans('months.july'),
                    translator.trans('months.august'),
                    translator.trans('months.september'),
                    translator.trans('months.october'),
                    translator.trans('months.november'),
                    translator.trans('months.december')
                ],
                datasets: Array.from({!! $data->toJson() !!})
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endsection
