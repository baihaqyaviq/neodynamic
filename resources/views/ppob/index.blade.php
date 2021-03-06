@extends('layouts.app')

@section('title','Print Known File Formats')

@section('body')

    <div class="container">
        <div class="row">

            <h3>Print PPOB STRUK</h3>
            <div class="row">
                <div class="col-md-4 col-md-offset-2">
                    <hr/>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="useDefaultPrinter" id="useDefaultPrinter" value="checked"/>
                            <strong>Print to Default printer</strong> or...
                        </label>
                    </div>
                    <div id="loadPrinters">
                        Click to load and select one of the installed printers!
                        <br/>
                        <a onclick="javascript:jsWebClientPrint.getPrinters();" class="btn btn-success">Load installed
                            printers...</a>
                        <br/>
                        <br/>
                    </div>
                    <div id="installedPrinters" style="visibility: hidden">
                        <label for="installedPrinterName">Select an installed Printer:</label>
                        <select name="installedPrinterName" id="installedPrinterName" class="form-control"></select>
                    </div>
                </div>
                <div class="col-md-4">
                    <hr/>
                    <div id="fileToPrint">
                        <label for="ddlFileType">Select a sample Struk to print:</label>
                        <select id="ddlFileType" class="form-control">
                            <option>GENERAL</option>
                            <option>PDAMMKM</option>
                            <option>PDAMSYS</option>
                            <option>TELKOM</option>
                            <option>PLNPREPAID</option>
                            <option>PLNPOSTPAID</option>
                            <option>PLNNONTAGLIS</option>
                        </select>
                        <br/>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="useHex" id="useHex" value="checked"/>
                                <strong>Enable HEX value</strong>
                            </label>
                        </div>
                        <hr>
                        <a class="btn btn-success btn-lg"
                           onclick="javascript:jsWebClientPrint.print('useHex='+ $('input[name=\'useHex\']:checked').val() +'&useDefaultPrinter=' + $('input[name=\'useDefaultPrinter\']:checked').val() + '&printerName=' + encodeURIComponent($('#installedPrinterName').val()) + '&filetype=' + $('#ddlFileType').val());">Print
                            Struk...</a>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection

@section('scripts')

    <script type="text/javascript">
        //var wcppGetPrintersDelay_ms = 0;
        var wcppGetPrintersTimeout_ms = 10000; //10 sec
        var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
        function wcpGetPrintersOnSuccess() {
            // Display client installed printers
            if (arguments[0].length > 0) {
                var p = arguments[0].split("|");
                var options = '';
                for (var i = 0; i < p.length; i++) {
                    options += '<option>' + p[i] + '</option>';
                }
                $('#installedPrinters').css('visibility', 'visible');
                $('#installedPrinterName').html(options);
                $('#installedPrinterName').focus();
                $('#loadPrinters').hide();
            } else {
                alert("No printers are installed in your system.");
            }
        }

        function wcpGetPrintersOnFailure() {
            // Do something if printers cannot be got from the client
            alert("No printers are installed in your system.");
        }

    </script>

    {!!

    // Register the WebClientPrint script code
    // The $wcpScript was generated by DemoPrintFileController@index

    $wcpScript;

    !!}
@endsection
