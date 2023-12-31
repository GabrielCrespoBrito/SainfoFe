<div id="qz-alert" style="position: fixed; width: 60%; margin: 0 4% 0 36%; z-index: 900;"></div>
<div id="qz-pin" style="position: fixed; width: 30%; margin: 0 66% 0 4%; z-index: 900;"></div>

<div class="container" role="main">

  <div class="row">
    <h1 id="title" class="page-header">QZ Tray v<span id="qz-version">0</span></h1>
  </div>

  <div class="row spread">
    <div class="col-md-4">
      <div id="qz-connection" class="panel panel-default">
        <div class="panel-heading">
          <button class="close tip" data-toggle="tooltip" title="Launch QZ" id="launch" href="#" onclick="launchQZ();" style="display: none;">
            <i class="fa fa-external-link"></i>
          </button>
          <h3 class="panel-title">
            Connection: <span id="qz-status" class="text-muted" style="font-weight: bold;">Unknown</span>
          </h3>
        </div>

        <div class="panel-body">
          <div class="btn-toolbar">
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-success" onclick="startConnection();">Connect</button>
              <button id="toggleConnectionGroup" type="button" class="btn btn-success" onclick="checkGroupActive('toggleConnectionGroup', 'connectionGroup'); $('#connectionHost').select();" data-toggle="tooltip" data-placement="bottom" title="Connect to QZ Tray running on a print server"><span class="fa fa-caret-down"></span>&nbsp;</button>
              <button type="button" class="btn btn-warning" onclick="endConnection();">Disconnect</button>
            </div>
            <button type="button" class="btn btn-info" onclick="listNetworkDevices();">Network Info</button>
          </div>
          <div class="form-group" id="connectionGroup">
            <hr>
            <label for="connectionHost">Connect to host:</label>
            <input type="text" id="connectionHost" value="localhost" class="form-control" />
            <div class="form-group form-inline">
              <label for="connectionUsingSecure" data-toggle="tooltip" title="HTTPS Only: When disabled, allows secure pages to connect to insecure locations.">
                Secure
              </label>
              <input checked type="checkbox" id="connectionUsingSecure" class="pull-right" />
            </div>
          </div>
        </div>
      </div>

      <hr />

      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Printer</h3>
        </div>

        <div class="panel-body">
          <div class="form-group">
            <label for="printerSearch">Search:</label>
            <input type="text" id="printerSearch" value="zebra" class="form-control" />
          </div>
          <div class="form-group">
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-default btn-sm" onclick="findPrinter($('#printerSearch').val(), true);">Find Printer</button>
              <button type="button" class="btn btn-default btn-sm" onclick="findDefaultPrinter(true);">Find Default Printer</button>
              <button type="button" class="btn btn-default btn-sm" onclick="findPrinters();">Find All Printers</button>
            </div>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-default btn-sm" onclick="detailPrinters();">Get Printer Details</button>
            </div>
          </div>
          <hr />
          <div class="form-group">
            <label>Current printer:</label>
            <div id="configPrinter">NONE</div>
          </div>
          <div class="form-group">
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-default btn-sm" onclick="setPrinter($('#printerSearch').val());">Set To Search</button>
              <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#askFileModal">Set To File</button>
              <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#askHostModal">Set To Host</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <ul class="nav nav-tabs" role="tablist">
        <li id="rawTab" role="presentation" class="active"><a href="#rawContent" role="tab" data-toggle="tab">Raw Printing</a></li>
        <li id="pxlTab" role="presentation"><a href="#pxlContent" role="tab" data-toggle="tab">Pixel Printing</a></li>
        <li id="serialTab" role="presentation"><a href="#serialContent" role="tab" data-toggle="tab">Serial</a></li>
        <li id="socketTab" role="presentation"><a href="#socketContent" role="tab" data-toggle="tab">Socket</a></li>
        <li id="usbTab" role="presentation"><a href="#usbContent" role="tab" data-toggle="tab">USB</a></li>
        <li id="hidTab" role="presentation"><a href="#hidContent" role="tab" data-toggle="tab">HID</a></li>
        <li id="statusTab" role="presentation"><a href="#statusContent" role="tab" data-toggle="tab">Printer Status</a></li>
        <li id="fileTab" role="presentation"><a href="#fileContent" role="tab" data-toggle="tab">Files</a></li>
      </ul>
    </div>

    <div class="tab-content">
      <div id="rawContent" class="tab-pane active col-md-8">
        <h3>Raw Printing</h3>

        <div class="row">
          <div class="col-md-12">
            <a href="https://qz.io/wiki/What-is-Raw-Printing" target="new">What is Raw Printing?</a>

            <span style="float: right;">
              <a href="javascript:findPrinter('Zebra', true);">Zebra</a> |
              <a href="javascript:findPrinter('ZDesigner', true);">ZDesigner</a> |
              <a href="javascript:findPrinter('Epson', true);">Epson</a> |
              <a href="javascript:findPrinter('Citizen', true);">Citizen</a> |
              <a href="javascript:findPrinter('Star', true);">Star</a>
            </span>
          </div>
        </div>

        <hr />

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Language</label>
              <div>
                <label>
                  <input type="radio" name="pLanguage" id="pLangEPL" value="EPL" />
                  EPL2
                </label>
                <label>
                  <input type="radio" name="pLanguage" id="pLangZPL" value="ZPL" />
                  ZPLII
                </label>
                <br />
                <label>
                  <input type="radio" name="pLanguage" id="pLangESCPOS" value="ESCPOS" />
                  ESC/POS
                </label>
                <label>
                  <input type="radio" name="pLanguage" id="pLangEPCL" value="EPCL" />
                  EPCL
                </label>
                <label>
                  <input type="radio" name="pLanguage" id="pLangEVOLIS" value="EVOLIS" />
                  Evolis
                </label>
              </div>
            </div>
            <div class="form-group">
              <div>
                <label>Print From File</label>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-default" onclick="printFile('zpl_sample.txt');">zpl_sample.txt</button>
                <button type="button" class="btn btn-default" onclick="printFile('fgl_sample.txt');">fgl_sample.txt</button>
                <button type="button" class="btn btn-default" onclick="printFile('epl_sample.txt');">epl_sample.txt</button>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <div>
                <label>Print Data</label>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-default" onclick="printCommand();">Commands</button>
                <button type="button" class="btn btn-default" onclick="printBase64();">Base64</button>
                <button type="button" class="btn btn-default" onclick="printXML();">XML</button>
                <button type="button" class="btn btn-default" onclick="printHex();">Hex</button>
              </div>
            </div>
            <div class="form-group">
              <div>
                <label>Raster Print</label>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-default" onclick="printRawImage();">Image</button>
                <button type="button" class="btn btn-default" onclick="printRawPDF();">PDF</button>
                <button type="button" class="btn btn-default" onclick="printRawHTML();">HTML</button>
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top: 1em;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">

                <fieldset>
                  <legend>Config Options</legend>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="rawEncoding">Encoding</label>
                        <input type="text" id="rawEncoding" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="rawSpoolEnd">End Of Doc</label>
                        <input type="text" id="rawSpoolEnd" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="rawPerSpool">Per Spool</label>
                        <input type="number" id="rawPerSpool" class="form-control pull-right" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label class="tip" for="rawAltPrinting" data-toggle="tooltip" title="Bypass driver when using CUPS">
                          Alternate Printing
                        </label>
                        <input type="checkbox" id="rawAltPrinting" class="pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="rawCopies">Copies</label>
                        <input type="number" id="rawCopies" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label class="tip" for="rawJobName" data-toggle="tooltip" title="Job title as it appears in print queue">
                          Job Name
                        </label>
                        <input type="text" id="rawJobName" class="form-control pull-right" />
                      </div>
                    </div>
                  </div>
                </fieldset>

                <hr />

                <fieldset>
                  <legend>Printer Options</legend>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="pX">Image X</label>
                        <input type="number" id="pX" class="form-control pull-right" />
                      </div>
                      <div class="form-group form-inline">
                        <label for="pY">Image Y</label>
                        <input type="number" id="pY" class="form-control pull-right" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label class="tip" for="pDotDensity" data-toggle="tooltip" title="For ESCPOS only">Dot Density</label>
                        <input type="text" id="pDotDensity" class="form-control pull-right" />
                      </div>
                      <div class="form-group form-inline">
                        <label for="pXml">XML Tag</label>
                        <input type="text" id="pXml" class="form-control pull-right" />
                      </div>
                      <div class="form-group form-inline">
                        <label for="pRawWidth" class="tip" data-toggle="tooltip" title="In pixels">Render Width</label>
                        <input type="number" id="pRawWidth" class="form-control pull-right" />
                      </div>
                      <div class="form-group form-inline">
                        <label for="pRawHeight" class="tip" data-toggle="tooltip" title="In pixels">Render Height</label>
                        <input type="number" id="pRawHeight" class="form-control pull-right" />
                      </div>
                    </div>
                  </div>
                </fieldset>

                <hr />

                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-danger pull-right" onclick="resetRawOptions();">Reset</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="pxlContent" class="tab-pane col-md-8">
        <h3>Pixel Printing</h3>

        <div class="row">
          <div class="col-md-12">
            <a href="https://qz.io/wiki/2.0-pixel-printing" target="new">What is Pixel Printing?</a>

            <span style="float: right;">
              <a href="javascript:findPrinter('XPS', true);">Microsoft XPS</a> |
              <a href="javascript:findPrinter('PDF', true);">PDF</a>
            </span>
          </div>
        </div>

        <hr />

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <button type="button" class="btn btn-default" onclick="printHTML();">Print HTML</button>
              <button type="button" class="btn btn-default" onclick="printPDF();">Print PDF</button>
              <button type="button" class="btn btn-default" onclick="printImage();">Print Image</button>
            </div>
          </div>
        </div>
        <div class="row" style="margin-top: 1em;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">

                <fieldset>
                  <legend>Config Options</legend>

                  <div class="row">
                    <div class="col-md-6">

                      <div class="form-group form-inline">
                        <label for="pxlColorType">Color Type</label>
                        <select id="pxlColorType" class="form-control pull-right">
                          <option value="color">Color</option>
                          <option value="grayscale">Grayscale</option>
                          <option value="blackwhite">Black & White</option>
                        </select>
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlCopies">Copies</label>
                        <input type="number" id="pxlCopies" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlDuplex"> Duplex</label>
                        <select id="pxlDuplex" class="form-control pull-right">
                          <option value="">Single Sided</option>
                          <option value="duplex">Double Sided</option>
                          <option value="long-edge">Two Sided (Long Edge)</option>
                          <option value="short-edge">Two Sided (Short Edge)</option>
                          <option value="tumble">Tumble</option>
                        </select>
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlInterpolation">Interpolation</label>
                        <select id="pxlInterpolation" class="form-control pull-right">
                          <option value="">Default</option>
                          <option value="bicubic">Bicubic</option>
                          <option value="bilinear">Bilinear</option>
                          <option value="nearest-neighbor">Nearest Neighbor</option>
                        </select>
                      </div>

                      <div class="form-group form-inline">
                        <label class="tip" for="pxlJobName" data-toggle="tooltip" title="Job title as it appears in print queue">
                          Job Name
                        </label>
                        <input type="text" id="pxlJobName" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlLegacy">Legacy Printing</label>
                        <input type="checkbox" id="pxlLegacy" class="pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlOrientation">Orientation</label>
                        <select id="pxlOrientation" class="form-control pull-right">
                          <option value="">Default</option>
                          <option value="portrait">Portrait</option>
                          <option value="landscape">Landscape</option>
                          <option value="reverse-landscape">Landscape - Reverse</option>
                        </select>
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlPaperThickness">Paper<br />Thickness</label>
                        <input disabled type="number" step="any" id="pxlPaperThickness" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlPrinterTray">Printer Tray</label>
                        <input type="text" id="pxlPrinterTray" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlRasterize">Rasterize</label>
                        <input type="checkbox" id="pxlRasterize" class="pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlRotation">Rotation</label>
                        <input type="number" step="any" id="pxlRotation" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlSpoolSize">Per Spool</label>
                        <input type="number" id="pxlSpoolSize" class="form-control pull-right" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="pxlDensity" class="tip" data-toggle="tooltip" title="DPI, DPCM, or DPMM depending on units specified">Density</label>
                        (
                        <label for="pxlDensityAsymm" class="inline">Asymmetric:</label>
                        <input type="checkbox" id="pxlDensityAsymm" onclick="checkGroupActive('pxlDensityAsymm', 'pxlDensityGroup', 'pxlDensity');">
                        )
                        <input id="pxlDensity" class="form-control" />
                      </div>
                      <div class="inline" id="pxlDensityGroup">
                        <div class="form-group form-inline">
                          <label for="pxlCrossDensity">&nbsp; Cross:</label>
                          <input type="number" id="pxlCrossDensity" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="pxlFeedDensity">&nbsp; Feed:</label>
                          <input type="number" id="pxlFeedDensity" class="form-control pull-right" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Units</label>
                        <div>
                          <label>
                            <input type="radio" name="pxlUnits" id="pxlUnitsIN" value="in" />
                            IN
                          </label>
                          <label>
                            <input type="radio" name="pxlUnits" id="pxlUnitsMM" value="mm" />
                            MM
                          </label>
                          <label>
                            <input type="radio" name="pxlUnits" id="pxlUnitsCM" value="cm" />
                            CM
                          </label>
                        </div>
                      </div>

                      <div class="form-group form-inline">
                        <label for="pxlScale">Scale Content:</label>
                        <input type="checkbox" id="pxlScale" class="pull-right" />
                      </div>

                      <div class="form-group">
                        <label for="pxlMargins" class="tip" data-toggle="tooltip" title="In relation to units specified">Margins</label>
                        (
                        <label for="pxlMarginsActive" class="inline">Individual:</label>
                        <input type="checkbox" id="pxlMarginsActive" onclick="checkGroupActive('pxlMarginsActive', 'pxlMarginsGroup', 'pxlMargins');">
                        )
                        <input type="number" step="any" id="pxlMargins" class="form-control" />
                      </div>
                      <div class="inline" id="pxlMarginsGroup">
                        <div class="form-group form-inline">
                          <label for="pxlMarginsTop">&nbsp; Top:</label>
                          <input type="number" step="any" id="pxlMarginsTop" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="pxlMarginsRight">&nbsp; Right:</label>
                          <input type="number" step="any" id="pxlMarginsRight" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="pxlMarginsBottom">&nbsp; Bottom:</label>
                          <input type="number" step="any" id="pxlMarginsBottom" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="pxlMarginsLeft">&nbsp; Left:</label>
                          <input type="number" step="any" id="pxlMarginsLeft" class="form-control pull-right" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="inline">Size</label>
                        (
                        <label for="pxlSizeActive" class="inline">Enable:</label>
                        <input type="checkbox" id="pxlSizeActive" onclick="checkGroupActive('pxlSizeActive', 'pxlSizeGroup');" />
                        )
                      </div>
                      <div class="inline" id="pxlSizeGroup">
                        <div class="form-group form-inline">
                          <label for="pxlSizeWidth" class="tip" data-toggle="tooltip" title="In relation to units specified">
                            &nbsp; Width:
                          </label>
                          <input type="number" step="any" id="pxlSizeWidth" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="pxlSizeHeight" class="tip" data-toggle="tooltip" title="In relation to units specified">
                            &nbsp; Height:
                          </label>
                          <input type="number" step="any" id="pxlSizeHeight" class="form-control pull-right" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="inline">Bounds</label>
                        (
                        <label for="pxlBoundsActive" class="inline">Enable:</label>
                        <input type="checkbox" id="pxlBoundsActive" onclick="checkGroupActive('pxlBoundsActive', 'pxlBoundsGroup');" />
                        )
                      </div>
                      <div class="inline" id="pxlBoundsGroup">
                        <div class="form-group form-inline">
                          <label for="pxlBoundX" class="tip" data-toggle="tooltip" title="In relation to units specified">
                            &nbsp; X:
                          </label>
                          <input type="number" step="any" id="pxlBoundX" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="pxlBoundY" class="tip" data-toggle="tooltip" title="In relation to units specified">
                            &nbsp; Y:
                          </label>
                          <input type="number" step="any" id="pxlBoundY" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="pxlBoundWidth" class="tip" data-toggle="tooltip" title="In relation to units specified">
                            &nbsp; Width:
                          </label>
                          <input type="number" step="any" id="pxlBoundWidth" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="pxlBoundHeight" class="tip" data-toggle="tooltip" title="In relation to units specified">
                            &nbsp; Height:
                          </label>
                          <input type="number" step="any" id="pxlBoundHeight" class="form-control pull-right" />
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <hr />

                <fieldset>
                  <legend>Printer Options</legend>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="pPxlWidth" class="tip" data-toggle="tooltip" title="In relation to units specified">
                          Render Width
                        </label>
                        <input type="number" id="pPxlWidth" class="form-control pull-right" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="pPxlHeight" class="tip" data-toggle="tooltip" title="In relation to units specified">
                          Render Height
                        </label>
                        <input type="number" id="pPxlHeight" class="form-control pull-right" />
                      </div>
                    </div>
                  </div>
                </fieldset>

                <hr />

                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-danger pull-right" onclick="resetPixelOptions();">Reset</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="serialContent" class="tab-pane col-md-8">
        <h3>Serial</h3>
        <hr />

        <div class="row">
          <div class="col-md-12">
            <div class="btn-toolbar">
              <button type="button" class="btn btn-info" onclick="listSerialPorts();">List Ports</button>
              <div class="btn-group">
                <button type="button" class="btn btn-success" onclick="openSerialPort();">Open Port</button>
                <button type="button" class="btn btn-warning" onclick="closeSerialPort();">Close Port</button>
              </div>
              <button type="button" class="btn btn-default" onclick="sendSerialData();">Send Command</button>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top: 1em;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">

                <fieldset>
                  <legend>Options</legend>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="serialPort">Port</label>
                        <input type="text" id="serialPort" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialBaud">Baud Rate</label>
                        <input type="number" id="serialBaud" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialData">Data Bits</label>
                        <input type="number" id="serialData" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialStop">Stop Bits</label>
                        <input type="number" id="serialStop" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialParity">Parity</label>
                        <input type="text" id="serialParity" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialFlow">Flow Control</label>
                        <input type="text" id="serialFlow" class="form-control pull-right" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="serialCmd">Data</label>
                        <input type="text" id="serialCmd" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label>Data Type</label>
                        <div class="pull-right">
                          <label>
                            <input type="radio" name="serialType" id="serialPlainRadio" value="PLAIN" />
                            Plain
                          </label>
                          <label>
                            <input type="radio" name="serialType" id="serialFileRadio" value="FILE" />
                            File
                          </label>
                        </div>
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialWidth">Encoding</label>
                        <input type="text" id="serialEncoding" class="form-control pull-right" />
                      </div>
                    </div>
                  </div>
                </fieldset>

                <hr />

                <fieldset>
                  <legend>Response Options</legend>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="serialStart">Start Bytes</label>
                        <input type="text" id="serialStart" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialEnd">End Byte</label>
                        <input type="text" id="serialEnd" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialWidth">Width</label>
                        <input type="number" id="serialWidth" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialHeader">Include Header</label>
                        <input type="checkbox" id="serialHeader" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="serialRespEncoding">Encoding</label>
                        <input type="text" id="serialRespEncoding" class="form-control pull-right" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="serialNewline" class="inline">Wait for new line</label>
                        <input type="checkbox" id="serialNewline" onclick="checkItemsDisabled('serialNewline', [ 'serialStart', 'serialEnd', 'serialWidth', 'serialHeader' ]);" />
                      </div>
                      <div class="form-group">
                        <label class="inline">Length Bytes</label>
                        (
                        <label for="serialLengthActive" class="inline">Enable:</label>
                        <input type="checkbox" id="serialLengthActive" onclick="checkGroupActive('serialLengthActive', 'serialLengthGroup');" />
                        )
                      </div>
                      <div class="inline" id="serialLengthGroup">
                        <div class="form-group form-inline">
                          <label for="serialLenIndex">&nbsp; Index:</label>
                          <input type="number" id="serialLenIndex" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="serialLenLength">&nbsp; Length:</label>
                          <input type="number" id="serialLenLength" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label>&nbsp; Endian:</label>
                          <div class="pull-right">
                            <label>
                              <input type="radio" name="serialLenEndian" id="serialLenEndianBig" value="BIG" />
                              Big
                            </label>
                            <label>
                              <input type="radio" name="serialLenEndian" id="serialLenEndianLittle" value="LITTLE" />
                              Little
                            </label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="inline">CRC Bytes</label>
                        (
                        <label for="serialCrcActive" class="inline">Enable:</label>
                        <input type="checkbox" id="serialCrcActive" onclick="checkGroupActive('serialCrcActive', 'serialCrcGroup');" />
                        )
                      </div>
                      <div class="inline" id="serialCrcGroup">
                        <div class="form-group form-inline">
                          <label for="serialCrcIndex">&nbsp; Index:</label>
                          <input type="number" id="serialCrcIndex" class="form-control pull-right" />
                        </div>
                        <div class="form-group form-inline">
                          <label for="serialCrcLength">&nbsp; Length:</label>
                          <input type="number" id="serialCrcLength" class="form-control pull-right" />
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <hr />
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-danger pull-right" onclick="resetSerialOptions();">Reset</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="socketContent" class="tab-pane col-md-8">
        <h3>Socket</h3>
        <p>Socket API is experimental; API subject to change.</p>
        <hr />

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <button type="button" class="btn btn-success" onclick="openSocket();">Open Socket</button>
                  <button type="button" class="btn btn-warning" onclick="closeSocket();">Close Socket</button>
                </div>
                <button type="button" class="btn btn-default" onclick="sendSocketData()">Send Data</button>
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top: 1em;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">

                <fieldset>
                  <legend>Options</legend>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="socketHost">Host</label>
                        <input type="text" id="socketHost" class="form-control pull-right" />
                      </div>

                      <div class="form-group form-inline">
                        <label for="socketPort">Port</label>
                        <input type="number" id="socketPort" class="form-control pull-right" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group form-inline">
                        <label for="socketData">Data</label>
                        <input type="text" id="socketData" class="form-control pull-right" />
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="usbContent" class="tab-pane col-md-8">
        <h3>USB</h3>
        <hr />

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <button type="button" class="btn btn-info" onclick="listUsbDevices();">List Devices</button>
                  <button type="button" class="btn btn-info" onclick="listUsbDeviceInterfaces();">List Interfaces</button>
                  <button type="button" class="btn btn-info" onclick="listUsbInterfaceEndpoints();">List Endpoints</button>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-info" onclick="checkUsbDevice()">Check Claimed</button>
                  <button type="button" class="btn btn-success" onclick="claimUsbDevice()">Claim Device</button>
                  <button type="button" class="btn btn-warning" onclick="releaseUsbDevice()">Release Device</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <button type="button" class="btn btn-default" onclick="sendUsbData()">Send Data</button>
                  <button type="button" class="btn btn-default" onclick="readUsbData()">Read Data</button>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-default" onclick="openUsbStream()">Open Stream</button>
                  <button type="button" class="btn btn-default" onclick="closeUsbStream()">Close Stream</button>
                </div>
                <div class="btn-group" data-toggle="buttons">
                  <label id="usbRawRadio" class="btn btn-default active">
                    <input type="radio" autocomplete="off" checked>
                    Raw
                  </label>
                  <label id="usbWeightRadio" class="btn btn-default">
                    <input type="radio" autocomplete="off">
                    Weight
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top: 1em;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">Options</h4>
              </div>

              <div class="panel-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group form-inline">
                      <label for="usbVendor">Vendor ID</label>
                      <input type="text" id="usbVendor" class="form-control pull-right" onblur="formatHexInput('usbVendor')" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="usbProduct">Product ID</label>
                      <input type="text" id="usbProduct" class="form-control pull-right" onblur="formatHexInput('usbProduct')" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="usbInterface">Device Interface</label>
                      <input type="text" id="usbInterface" class="form-control pull-right" onblur="formatHexInput('usbInterface')" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="usbEndpoint">Interface Endpoint</label>
                      <input type="text" id="usbEndpoint" class="form-control pull-right" onblur="formatHexInput('usbEndpoint')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group form-inline">
                      <label for="usbData">Send Data</label>
                      <input type="text" id="usbData" class="form-control pull-right" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="usbResponse">Read size</label>
                      <input type="text" id="usbResponse" class="form-control pull-right" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="usbStream" class="tip" data-toggle="tooltip" title="Streaming Only: In milliseconds">
                        Stream Interval
                      </label>
                      <input type="text" id="usbStream" class="form-control pull-right" />
                    </div>
                  </div>
                </div>
                <hr />
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-danger pull-right" onclick="resetUsbOptions();">Reset</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="hidContent" class="tab-pane col-md-8">
        <h3>HID</h3>
        <hr />

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <button type="button" class="btn btn-info" onclick="listHidDevices();">List Devices</button>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-info" onclick="checkHidDevice()">Check Claimed</button>
                  <button type="button" class="btn btn-success" onclick="claimHidDevice()">Claim Device</button>
                  <button type="button" class="btn btn-warning" onclick="releaseHidDevice()">Release Device</button>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-default" onclick="startHidListen()">Listen for Events</button>
                  <button type="button" class="btn btn-default" onclick="stopHidListen()">Stop Listening</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <button type="button" class="btn btn-default" onclick="sendHidData()">Send Data</button>
                  <button type="button" class="btn btn-default" onclick="readHidData()">Read Data</button>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-default" onclick="openHidStream()">Open Stream</button>
                  <button type="button" class="btn btn-default" onclick="closeHidStream()">Close Stream</button>
                </div>
                <div class="btn-group" data-toggle="buttons">
                  <label id="hidRawRadio" class="btn btn-default active">
                    <input type="radio" autocomplete="off" checked>
                    Raw
                  </label>
                  <label id="hidWeightRadio" class="btn btn-default">
                    <input type="radio" autocomplete="off">
                    Weight
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top: 1em;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">Options</h4>
              </div>

              <div class="panel-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group form-inline">
                      <label for="hidVendor">Vendor ID</label>
                      <input type="text" id="hidVendor" class="form-control pull-right" onblur="formatHexInput('hidVendor')" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="hidProduct">Product ID</label>
                      <input type="text" id="hidProduct" class="form-control pull-right" onblur="formatHexInput('hidProduct')" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="hidUsagePage" class="tip" data-toggle="tooltip" title="Optional: For devices that expose multiple endpoints">
                        Usage Page
                      </label>
                      <input type="text" id="hidUsagePage" class="form-control pull-right" onblur="formatHexInput('hidUsagePage')" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="hidSerial" class="tip" data-toggle="tooltip" title="Optional: For distinguishing between identical devices">
                        Serial Number
                      </label>
                      <input type="text" id="hidSerial" class="form-control pull-right" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group form-inline">
                      <label for="hidData">Send Data</label>
                      <input type="text" id="hidData" class="form-control pull-right" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="hidReport">Report Id</label>
                      <input type="text" id="hidReport" class="form-control pull-right" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="hidResponse">Read size</label>
                      <input type="text" id="hidResponse" class="form-control pull-right" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="hidStream" class="tip" data-toggle="tooltip" title="Streaming Only: In milliseconds">
                        Stream Interval
                      </label>
                      <input type="text" id="hidStream" class="form-control pull-right" />
                    </div>
                  </div>
                </div>
                <hr />
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-danger pull-right" onclick="resetHidOptions();">Reset</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="statusContent" class="tab-pane col-md-8">
        <h3>Printer Status</h3>
        <hr />

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <button type="button" class="btn btn-info" onclick="startPrintersListen()">All Printers</button>
                  <button type="button" class="btn btn-success" onclick="startPrintersListen($('#configPrinter').text())">
                    Current Printer
                  </button>
                  <button type="button" class="btn btn-default" onclick="getPrintersStatus()">Request current status</button>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-warning" onclick="stopPrintersListen()">Stop Listening</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top: 1em;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">Event Log</h4>
              </div>

              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                    <pre id="printersLog"></pre>
                  </div>
                </div>
                <hr />
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-danger pull-right" onclick="clearPrintersLog();">Clear</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="fileContent" class="tab-pane col-md-8">
        <h3>Files</h3>
        <hr />

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="btn-toolbar">
                <button type="button" class="btn btn-info" onclick="listFiles();">List Files</button>
                <div class="btn-group">
                  <button type="button" class="btn btn-info" onclick="readFile();">Read File</button>
                  <button type="button" class="btn btn-success" onclick="writeFile();">Write File</button>
                  <button type="button" class="btn btn-warning" onclick="deleteFile();">Delete File</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <button type="button" class="btn btn-default" onclick="startFileListen()">Listen for Folder Events</button>
                  <button type="button" class="btn btn-default" onclick="stopFileListen()">Stop Listening</button>
                </div>
                <button type="button" class="btn btn-default" onclick="stopAllFileListeners();">Stop All Listeners</button>
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top: 1em;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">Options</h4>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="fileLocation">
                        File / Directory Path
                      </label>
                      <input type="text" id="fileLocation" class="form-control" />
                    </div>
                    <div class="form-group">
                      <label for="fileData">Data To Write</label>
                      <textarea id="fileData" rows="5" style="resize:vertical" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="includePattern" class="tip" data-toggle="tooltip" title="File pattern to match when listening for file events (e.g. *.txt)">
                        Include File Pattern
                      </label>
                      <input type="text" id="includePattern" class="form-control pull-right" />
                    </div>
                    <div class="form-group">
                      <label for="excludePattern" class="tip" data-toggle="tooltip" title="File pattern to ignore when listening for file events (e.g. *.tmp) ">
                        Exclude File Pattern
                      </label>
                      <input type="text" id="excludePattern" class="form-control pull-right" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group form-inline">
                      <label for="fileShared">
                        Shared
                      </label>
                      <input type="checkbox" id="fileShared" class="pull-right" />
                    </div>
                    <div class="form-group form-inline">
                      <label for="fileSandbox" data-toggle="tooltip">
                        Sandbox
                      </label>
                      <input checked type="checkbox" id="fileSandbox" class="pull-right" />
                    </div>
                    <div class="form-group form-inline">
                      <label for="fileAppend">
                        Append Data
                      </label>
                      <input type="checkbox" id="fileAppend" class="pull-right" />
                    </div>

                    <div class="form-group form-inline">
                      <label for="fileListenerData">
                        Listener Data
                      </label>
                      <input type="checkbox" id="fileListenerData" class="pull-right" onclick="checkGroupActive('fileListenerData', 'fileTriggersGroup')" />
                    </div>
                    <div class="inline" id="fileTriggersGroup">
                      <div class="form-group form-inline">
                        <label>
                          Read direction
                        </label>
                        <div>
                          <label>
                            <input type="radio" name="fileDir" id="fileDirEnd" value="end" />
                            End
                          </label>
                          <label>
                            <input type="radio" name="fileDir" id="fileDirStart" value="begin" />
                            Start
                          </label>
                        </div>
                      </div>
                      <div class="form-group form-inline">
                        <label>
                          Truncate
                        </label>
                        <div>
                          <label>
                            <input type="radio" name="fileTruncate" id="fileTruncateLines" value="lines" />
                            Lines
                          </label>
                          <label>
                            <input type="radio" name="fileTruncate" id="fileTruncateBytes" value="bytes" />
                            Bytes
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="fileLength">Truncate length</label>
                        <input type="number" id="fileLength" class="form-control pull-right" />
                      </div>
                    </div>
                  </div>
                </div>
                <hr />
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-danger pull-right" onclick="resetFileOptions();">Reset</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="askFileModal" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="form-group">
            <label for="askFile">File:</label>
            <input type="text" id="askFile" class="form-control" value="C:\tmp\example-file.txt" />
            <hr />
            <p><span class="text-danger" style="font-weight:bold;"><span class="fa fa-warning"></span> WARNING:</span> This feature has been deprecated. Please configure a local raw <code>FILE:</code> printer, or use <code>File IO</code></a> instead. For more
              information please see <a href="https://github.com/qzind/tray/issues/730">issue&nbsp;<code>#730</code>.</a></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="setPrintFile();">Set</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="askHostModal" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="form-group">
            <label for="askHost">Host:</label>
            <input type="text" id="askHost" class="form-control" value="192.168.1.254" />
          </div>
          <div class="form-group">
            <label for="askPort">Port:</label>
            <input type="text" id="askPort" class="form-control" value="9100" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="setPrintHost();">Set</button>
        </div>
      </div>
    </div>
  </div>

</div>
