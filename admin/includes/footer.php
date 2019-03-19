<!--footer starts here-->
</div>
<br>
<footer class="text-center" id="footer">&copy; Copyright 2019 Hayk's Boutique</footer>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>

    function updateSizes() {
        var sizeString='';
        for (var i=1;i<=12;i++){
            if($('#size'+i).val()!=''){
                sizeString+=$('#size'+i).val()+':'+$('#qty'+i).val()+',';
            }
        }
        var strLen = sizeString.length;
        sizeString=sizeString.slice(0,strLen-1);
        $('#sizes').val(sizeString);

    }

    function get_child_options(selected) {
        if(typeof selected ==='undefined'){
            var selected = '';
        }
        var parentID = $('#parent').val();
        $.ajax({
            url: '/shop/admin/parsers/child_categories.php',
            type: 'POST',
            data: {parentID: parentID,selected:selected},
            success: function (data) {
                $('#child').html(data);
            },
            error: function () {
                alert("Something went wrong with the child options")
            }
        });
    }

    $('select[name="parent"]').change(function () {
        get_child_options();
    });
</script>
</body>
</html>