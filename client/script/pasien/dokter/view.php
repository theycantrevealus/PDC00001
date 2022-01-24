<script type="text/javascript">
    $(function () {
        var UID = __PAGES__[4];
        
        $("body").on("click", ".cppt_paginate_prev", function() {
            if(currentCPPTStep > 1) {
                currentCPPTStep -= 1;
                loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, "");
            }
            return false;
        });

        $("body").on("click", ".cppt_paginate_next", function() {
            var total = $(".cppt_paginate").length;
            if(currentCPPTStep < total) {
                currentCPPTStep += 1;
                loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, "");
            }
            return false;
        });

        $("body").on("click", ".cppt_paginate", function(e) {
            e.preventDefault();
            var tar = $(this).attr("target");
            currentCPPTStep = parseInt(tar);
            loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, "");
            return false;
        });

        loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, UID);

        $("#filter_date").change(function() {
            loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, UID);
        });

        $("body").on("click", ".lampiran_view_trigger", function() {
            var target = $(this).attr("target");
            $("#modal-lampiran-viewer").modal("show");

            var request = new XMLHttpRequest();
            request.open('GET', target, true);
            request.responseType = 'blob';
            request.onload = function() {
                var reader = new FileReader();
                reader.readAsDataURL(request.response);
                reader.onload =  function(e){
                    var fileReader = new FileReader();
                    fileReader.onload = function() {
                        var pdfData = new Uint8Array(this.result);
                        // Using DocumentInitParameters object to load binary data.
                        var loadingTask = pdfjsLib.getDocument({
                            data: pdfData
                        });
                        loadingTask.promise.then(function(pdf) {
                            // Fetch the first page
                            var pageNumber = 1;
                            pdf.getPage(pageNumber).then(function(page) {
                                var scale = 1.5;
                                var viewport = page.getViewport({
                                    scale: scale
                                });
                                // Prepare canvas using PDF page dimensions
                                var canvas = $("#pdfViewer")[0];
                                var context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                // Render PDF page into canvas context
                                var renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                var renderTask = page.render(renderContext);
                                renderTask.promise.then(function() {
                                    //$("#btnSubmit").removeAttr("disabled").html("Terima SK").removeClass("btn-warning").addClass("btn-primary");
                                });
                            });
                        }, function(reason) {
                            // PDF loading error
                            console.error(reason);
                        });
                    };
                    //fileReader.readAsArrayBuffer(file);
                    fileReader.readAsArrayBuffer(request.response);
                };
            };
            request.send();

            return false;
        });
    });
</script>
<div id="modal-lampiran-viewer" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index: 2048;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Lampiran Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas style="width: 100%; border: solid 1px #ccc;" id="pdfViewer"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>