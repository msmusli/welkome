<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ trans('shifts.shift') }}</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Hotel</th>
                <th>@lang('common.created.at')</th>
                <th>@lang('common.closed.at')</th>
                <th>@lang('payments.cash')</th>
                <th>@lang('common.status')</th>
                <th>@lang('transactions.made.by')</th>
            </tr>
            <tr>
                <th>{{ $shift->hotel->business_name }}</th>
                <th>{{ $shift->created_at }}</th>
                <th>{{ $shift->closed_at ?? '-' }}</th>
                <th>{{ $shift->cash }}</th>
                <th>{{ $shift->open ? trans('shifts.open') : trans('shifts.close') }}</th>
                <th>{{ $shift->team_member_name }}</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <th>@lang('common.date')</th>
                <th>@lang('common.number')</th>
                <th>@lang('transactions.type')</th>
                <th>@lang('common.value')</th>
                <th>@lang('payments.title')</th>
                <th>@lang('transactions.made.by')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shift->vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->created_at->format('Y-m-d') }}</td>
                    <td>{{ $voucher->number }}</td>
                    <td>{{ trans('transactions.' . $voucher->type) }}</td>
                    <td>{{ $voucher->value }}</td>
                    <td>{{ $voucher->payments->where('payment_method', 'cash')->sum('value') }}</td>
                    <td>{{ $voucher->made_by }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>