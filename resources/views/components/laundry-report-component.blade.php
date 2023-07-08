<div>

    <table class="table table-bordered table-responsive table convert-data-table table-striped" style="font-size: 12px">
        <thead>
        <tr>
            <th>#</th>
            <th>Invoice/Receipt No</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Sub Total</th>
            <th>Total Paid</th>
            <th>Date</th>
            <th>Time</th>
            <th>By</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total = 0;
        @endphp
        @foreach($invoices as $invoice)
            @php
                $total += $invoice->total_amount_paid;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $invoice->invoice_paper_number }}</td>
                <td>{{ $invoice->customer->firstname }} {{ $invoice->customer->lastname }}</td>
                <td>{!! invoice_status($invoice->status) !!}</td>
                <td>{{ number_format($invoice->sub_total,2) }}</td>
                <td>{{ number_format($invoice->total_amount_paid,2) }}</td>
                <td>{{ convert_date2($invoice->invoice_date) }}</td>
                <td>{{ $invoice->sales_time }}</td>
                <td>{{ $invoice->created_user->name }}</td>
                <td>
                    <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button" aria-expanded="false">Action <span class="caret"></span></button>
                        <ul role="menu" class="dropdown-menu">
                            @if(userCanView('laundry.view'))
                                <li><a href="{{ route('laundry.view',$invoice->id) }}">View Laundry</a></li>
                            @endif
                            @if(userCanView('laundry.edit') && $invoice->sub_total > -1 && $invoice->status =="DRAFT")
                                <li><a href="{{ route('laundry.edit',$invoice->id) }}">Edit Laundry</a></li>
                            @endif
                            @if(userCanView('laundry.pos_print'))
                                <li><a onclick="open_print_window(this); return false" href="{{ route('laundry.pos_print',$invoice->id) }}">Print Laundry Pos</a></li>
                            @endif
                            @if(userCanView('laundry.print_afour'))
                                <li><a onclick="open_print_window(this); return false" href="{{ route('laundry.print_afour',$invoice->id) }}">Print Laundry A4</a></li>
                            @endif
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total</th>
            <th>{{ number_format($total,2) }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>

</div>
