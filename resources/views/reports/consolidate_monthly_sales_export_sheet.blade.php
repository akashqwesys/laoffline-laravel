<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
</style>
<table class="" width="">
    @php
        $total_payment = $total_received = $total_pending = 0;
    @endphp
    <thead>
        <tr>
            <th colspan="4" align="center" style="font-size: 20px;"><b>MONTHLY SALES REPORT</b></th>
        </tr>
        <tr>
            <th colspan="4" align="center" style="font-size: 16px;"><b>{{ $request->customer['name'] ?? 'All Parties' }}</b></th>
        </tr>
        <tr>
            <th colspan="4" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
        <tr>
            <th colspan="4" align="center"><b>{{ $request->agent['name'] }}</b></th>
        </tr>
        <tr>
            <th><b>Month</b></th>
            <th align="right"><b>Gross Sales(Amt)</b></th>
            <th align="right"><b>Net Sales(Amt)</b></th>
            <th align="right"><b>Gross Pending(Amt)</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        @php
            $total_payment += floatval($d->total_payment);
            $total_received += floatval($d->total_received);
            $total_pending += floatval($d->total_pending);
        @endphp
        <tr>
            <td align="left"> {{ $d->month_year }} </td>
            <td align="right"> {{ number_format($d->total_payment) }}</td>
            <td align="right"> {{ number_format($d->total_received) }}</td>
            <td align="right"> {{ number_format($d->total_pending) }}</td>
        </tr>
        @endforeach
        <tr>
            <th class="">  </th>
            <th class=""> </th>
            <th align="right"></th>
            <th align="right"></th>
        </tr>
        <tr>
            <th class=""> <b>Total</b> </th>
            <th align="right"><b> {{ number_format($total_payment) }} </b></th>
            <th align="right"><b> {{ number_format($total_received) }} </b></th>
            <th align="right"><b> {{ number_format($total_pending) }} </b></th>
        </tr>
    </tbody>
</table>
