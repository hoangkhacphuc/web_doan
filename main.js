$(document).ready(function () {
      $('#btn-login').click(function () { 
            let data = {
                'user' : 'admin',
                'pass' : '123123123'
            };

            $.post("./backend/index.php?API=Login", data,
                function (data, textStatus, jqXHR) {
                    console.log(data);
                }
            );
      });
});