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


</script>
</body>
</html>