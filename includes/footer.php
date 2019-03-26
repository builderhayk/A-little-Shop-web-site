<!--footer starts here-->
</div>
</div>
<footer class="text-center" id="footer">&copy; Copyright 2019 Hayk's Boutique</footer>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    $(window).scroll(function () {
        var vscroll = $(this).scrollTop();
        $('#logotext').css({
            "transform": "translate(0px," + vscroll / 2 + "px)"
        });
        var vscroll = $(this).scrollTop();
        $('#back-flower').css({
            "transform": "translate(" + vscroll / 5 + "px,-" + vscroll / 12 + "px)"
        });
        var vscroll = $(this).scrollTop();
        $('#fore-flower').css({
            "transform": "translate(0px,-" + vscroll / 2 + "px)"
        });
    });

    function detailsmodal(id) {
        var data = {"id": id};
        $.ajax({
            url: 'includes/detailsmodal.php',
            method: "post",
            data: data,
            success: function (data) {
                $('body').append(data);
                $('#details-modal').modal('toggle');
            },
            error: function () {
                alert("Something went wrong");
            }
        })
    };

    function update_cart(mode,edit_id,edit_size) {
        var data = {"mode":mode,"edit_id":edit_id,"edit_size":edit_size};
        $.ajax({
            url:'/shop/admin/parsers/update_cart.php',
            method:"post",
            data:data,
            success:function () {
                location.reload();
            },
            error:function () {
                alert("Something went wrong");
            }
        })
    }

    function add_to_cart() {
        $('#modal_errors').html("");
        var size = $('#size').val();
        var quantity = $('#quantity').val();
        var available = $('#available').val();
        var product_id =$('#product_id').val();
        var error = '';
        var data = $('#add_product_form').serialize();
        if (size == '' || quantity == '' || quantity == 0) {
            error += '<p class="text-danger text-center">You must choose a size and quantity</p>';
            $('#modal_errors').html(error);
            return;
        } else if (quantity > available) {
            error += '<p class="text-danger text-center">There are only ' + available + ' available</p>';
            $('#modal_errors').html(error);
            return;
        } else {
            $.ajax({
                url: '/shop/admin/parsers/add_cart.php',
                type: 'post',
                data:data,
                // async:true,
                success: function () {
                     location.reload();
                },
                error: function () {
                    alert("Something went wrong");
                }

            })
        }
    }


</script>
</body>
</html>