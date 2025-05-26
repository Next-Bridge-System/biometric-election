<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#print_address">
    <i class="fas fa-print mr-1"></i>Print
</button>

<div class="modal fade" id="print_address" tabindex="-1" aria-labelledby="print_address_label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="print_address_label">Print Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print_section">
                <table style="width:100%">
                    <tr>
                        <td style="border: 1px solid black;padding:10px">ADDRESS</td>
                        <td style="border: 1px solid black;padding:10px">LICENSE</td>
                        <td style="border: 1px solid black;padding:10px">LEDGER</td>
                        <td style="border: 1px solid black;padding:10px">PLJ</td>
                        <td style="border: 1px solid black;padding:10px">BF</td>
                        <td style="border: 1px solid black;padding:10px">GI</td>
                        <td style="border: 1px solid black;padding:10px">RCPT</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black;padding:10px">{{ getLcPostalAddress($application->id) }}</td>
                        <td style="border: 1px solid black;padding:10px">{{ $application->license_no_lc ?? '-' }}</td>
                        <td style="border: 1px solid black;padding:10px">{{ $application->reg_no_lc ?? '-' }}</td>
                        <td style="border: 1px solid black;padding:10px">{{ $application->plj_no_lc ?? '-' }}</td>
                        <td style="border: 1px solid black;padding:10px">{{ $application->bf_no_lc ?? '-' }}</td>
                        <td style="border: 1px solid black;padding:10px">{{ $application->gi_no_lc ?? '-' }}</td>
                        <td style="border: 1px solid black;padding:10px">{{ $application->rcpt_no_lc ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printSection()">Print</button>
            </div>
        </div>
    </div>
</div>

<script>
    function printSection()
    {
        var divToPrint=document.getElementById('print_section');
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},10);
    }
</script>