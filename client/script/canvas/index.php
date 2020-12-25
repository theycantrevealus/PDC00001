<script type="text/javascript">
    $(function () {
        var savedPoint = {};
        var currentCount = 1;
        function writeMessage(canvas, message, xloc, yloc, context) {
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.font = '18pt Calibri';
            context.fillStyle = 'red';
            context.fillText(message, xloc, yloc); // x,y are bottom left of text
        }

        function getMousePos(canvas, evt) {
            var rect = $("#myCanvas").get(0).getBoundingClientRect(),
                root = $("body");

            // return relative mouse position
            /*var mouseX = evt.clientX - rect.top - root.scrollTop();
            var mouseY = evt.clientY - rect.left - root.scrollLeft();*/

            var mouseX = evt.clientX - rect.left - 10;
            var mouseY = evt.clientY - rect.top;
            return {
                x: mouseX,
                y: mouseY
            };
        }

        /*var canvas = document.getElementById('myCanvas');
        var context = canvas.getContext('2d');*/
        var canvas = $("#myCanvas");
        var context = canvas.get(0).getContext('2d');

        $("#myCanvas").click(function(evt) {
            var c= 215;
            var mousePos = getMousePos(canvas, evt);
            var message = String.fromCharCode(c);

            if(savedPoint["point_" + currentCount] === undefined) {
                savedPoint["point_" + currentCount] = {
                    message: message,
                    x: 0,
                    y: 0
                };
            }

            savedPoint["point_" + currentCount] = {
                message: message,
                x: mousePos.x,
                y: mousePos.y
            };
            console.log(savedPoint);
            refreshLokalis(savedPoint, canvas, context);
            //writeMessage(canvas, message, mousePos.x -10, mousePos.y, context);
            currentCount++;
        });

        function refreshLokalis(dataSet, canvas, context) {
            $("#lokalis_value tbody tr").remove();
            var autoNum = 1;
            context.clearRect(0, 0, canvas.width(), canvas.height());


            for(var key in dataSet) {
                var newRow = document.createElement("TR");
                $(newRow).attr({
                    "id": "row-" + key
                });
                var newNum = document.createElement("TD");
                $(newNum).html(autoNum);
                var newRemark = document.createElement("TD");
                var newAct = document.createElement("TD");

                var remark = document.createElement("TEXTAREA");
                var deleteBtn = document.createElement("BUTTON");

                $(remark).addClass("form-control").attr({
                    "placeholder": "Keterangan"
                });

                $(deleteBtn).addClass("btn btn-danger btnHapusLokalis").html("<i class=\"fa fa-times\"></i>").attr({
                    "id": "hapus-" + key
                });

                $(newRemark).append(remark);
                $(newAct).append(deleteBtn);

                $(newRow).append(newNum);
                $(newRow).append(newRemark);
                $(newRow).append(newAct);

                $("#lokalis_value tbody").append(newRow);

                writeMessage(canvas, dataSet[key].message, dataSet[key].x, dataSet[key].y, context);
                autoNum++;
            }
        }

        $("body").on("click", ".btnHapusLokalis", function () {
            var id = $(this).attr("id").split("-");
            id = id[id.length - 1];

            $("#row-" + id).remove();
            delete savedPoint[id];
            refreshLokalis(savedPoint, canvas, context);

            return false;
        });
    });
</script>