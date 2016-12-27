/**
 * Created by PROGRAMERIUA on 27.12.2016.
 */
$(document).ready(function () {
    $('.open-popup').click(function(){
     var position = popupPosition($(window).outerHeight(), $('.popup'));
     $("#btn_form").find("span").text('Відправити');

     $('.popup').css(position);
     $('.popup').fadeIn(400);
     $('.black').fadeIn(400);
     return false;
     });

     $('.close-popup, .black').click(function(){
     $('.popup').fadeOut(400);
     $('.black').fadeOut(400);
     return false;
     });


    $('#btn_form').click(function (e) {
        e.preventDefault();
        $('#name,#phone,#message').removeClass('error');
        var error = false,
            name = $('#name').val(),
            phone = $('#phone').val(),
            message = $('#message').val(),
            span = $('#popup-btn span');

        if (name == '') {
            $('#name').addClass('error');
            error = true;
        }
        var phone_test = /[0-9]/i;
        if (phone == '' || !phone_test.test(phone)) {
            $('#phone').addClass('error');
            error = true;
        }

        if (message == '') {
            $('#message').addClass('error');
            error = true;
        }

        if(error == false) {
            $.ajax({
                type: 'POST',
                url: 'http://kom.sufix/call/send/',
                dataType: 'json',
                data: {
                    name: name,
                    phone: phone,
                    message: message
                },
                success: function (response) {
                    // console.log(response);
                    setTimeout(function () {
                        $("#btn_form").find("span").text('Відправка');
                        $('#name').val('');
                        $('#phone').val('');
                        $('#message').val('');
                        setTimeout(function () {
                            $("#btn_form").find("span").text('Відправлено')
                        },500);
                    },500);
                    setTimeout(function () {
                        $('.popup').fadeOut(1000);
                        $('.black').fadeOut(1000);
                    },500);
                }
            });
        }
        $("#btn_form").find("span").text('Відправити');
    });
});
function popupPosition(windowHeight, popupObject){
    var $top, $result, $popupHalf, $windowHalf;

    if(windowHeight >= popupObject.outerHeight()){
        $popupHalf = popupObject.outerHeight() / 2;
        $windowHalf = windowHeight / 2;
        $top = $(window).scrollTop() + ($windowHalf - $popupHalf);
    } else {
        $top = $(window).scrollTop() + 50;
    }

    $result = {'top' : $top};

    return $result;

};


