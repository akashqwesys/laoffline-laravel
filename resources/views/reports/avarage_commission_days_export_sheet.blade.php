<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
</style>
<table class="" width="">
        <thead>
        <tr>
            <th colspan="3" align="center" style="font-size: 20px;"><b >AVARAGE COMMISSION DAYS REPORT</b></th>
        </tr>
        <tr>
            <th colspan="3" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
    </thead>
    <tbody>
    @php
        if (!empty($data['company_data'])) {
            $i = 1;
    @endphp
            <tr>
                <td><b>No</b></td>
                <td><b>Company Name</b></td>
                <td><b>Avarage Days</b></td>
            </tr>
            @foreach ($data['company_data'] as $cd)
                <tr>
                    <td align="left">{{ $i++ }}</td>
                    <td>{{ $cd['company_name'] }}</td>
                    <td>{{ $cd['avarageday'] }} Days</td>
                </tr>
            @endforeach
        @php
        } else {
        @endphp
            <tr>
                <td colspan=3>No Data Found<td>
            </tr>
        @php
            }
        @endphp
    </tbody>
</table>
