<script type="text/javascript">
    
    $(function () {

        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
    

        function getDateRange(target) {
            var rangeAbsen = $(target).val().split(" to ");
            if(rangeAbsen.length > 1) {
                return rangeAbsen;
            } else {
                return [rangeAbsen, rangeAbsen];
            }
        }

        $("#range_absen").change(function() {
            tableAbsen.ajax.reload();
        });

        //Init Absen
        $.ajax({
            async: false,
                url: __HOSTAPI__  + "/Absen",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    request: 'update_absen',
                },
                success: function (response) {
                    tableAbsen.ajax.reload();
                },
                error: function (response) {
                    //
                }
        });     
        
        var tableAbsen = $("#table-absen-harian").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                async:false,
                url: __HOSTAPI__ + "/Absen",
                type: "POST",
                data: function(d) {
                    d.request = "get_absen";
                    d.from = getDateRange("#range_absen")[0];
                    d.to = getDateRange("#range_absen")[1];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    console.log(response);
                    var returnedData = [];
                    var returnedData = response.response_package.response_data;

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                  
                    return returnedData;
                }
            },
            autoWidth: false,
            searching: false,
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.tanggal;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.jam_masuk === null){
                            return "<span class='badge badge-danger'>Belum Absen Masuk</span>"
                        } else {
                            return row.jam_masuk;
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.jam_keluar === null){
                            return "<span class='badge badge-danger'>Belum Absen Pulang</span>"
                        } else {
                            return row.jam_keluar;
                        }
                        
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.jam_masuk === null){
                            return ""
                        }else {
                            return "<span class='badge badge-success'>hadir</span>"
                        }
                        
                    }
                }
            ]
        });

        $("#btnAbsenMasuk").click(function () {
            $.ajax({
                async: false,
                url: __HOSTAPI__  + "/Absen",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    request: 'update_absen',
                    absen: 'masuk',
                },
                success: function (response) {
                    console.log(response);
                    tableAbsen.ajax.reload();
                },
                error: function (response) {
                    //
                }
            });
            return false;
        });            

        $("#btnAbsenKeluar").click(function () {
            $.ajax({
                async: false,
                url: __HOSTAPI__  + "/Absen",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    request: 'update_absen',
                    absen: 'keluar',
                },
                success: function (response) {
                    console.log(response);
                    tableAbsen.ajax.reload();
                },
                error: function (response) {
                    //
                }
            });
            return false;
        });  
        
        setInterval(function() {
            var start = new Date;
            var hours = (start.getHours() < 10 ? "0"+start.getHours() :start.getHours()) ;
            var minutes = (start.getMinutes() < 10 ? "0"+start.getMinutes(): start.getMinutes());
            var seconds = (start.getSeconds() < 10 ? "0"+start.getSeconds(): start.getSeconds());
            var day = start.getDay();
            var month = start.getMonth();
            var year = start.getFullYear();
            var tday = days[day];
            month = months[month];
            $('.time').text(hours+" : "+minutes+" : "+seconds);
            $('.tanggalSekarang').text(tday+", "+day+" "+month+" "+year);
            
        }, 1000);
    });
</script>
