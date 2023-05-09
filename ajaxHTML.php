<?php
session_start();
include('header.html');
?>

<body>
    <?php
    include('connect.php');
    if (isset($_POST['save'])) {
        $signSVG = strrchr($_POST['Signimg'], "width");
        $signSVG = substr($signSVG, 7, 1);
        if ($signSVG != 0) {
            echo '<script>alert();</script>';
            
            $sign = strchr($_POST['Signimg'], 'dtd">');
            $sign = substr($sign, 5);
            $_SESSION['a'] = $sign;

            $stmt = $conn->prepare("UPDATE itoss_sign SET Sign_image=? WHERE Sign_id = 15");
            $stmt->bindParam(1, $sign);
            $stmt->execute();
        }
    }

    $sql = $conn->query("SELECT * FROM itoss_sign WHERE Sign_id = 15");
    $data = $sql->fetch();
    ?>
    <form action="" method="post">
        <div class="row">
            <div class="col-6">
                <div id="signature"></div>
            </div>
        </div>
        <button type="submit" name="save" id="save">Save</button>
        <div class="row">
            <div class="col">
                <input type="text" name="Signimg" id="Signimg">
            </div>
        </div>
        <div class="col" id="tools"></div>
        <button type="button" id="convert">Convert SVG to PNG</button>
        <img src="" id="imgpng" alt="">
    </form>
    <div id="text">

    </div>
    <script src="./libs/jquery.js"></script>
    <script src="./libs/jSignature.min.noconflict.js"></script>
    <script>
        (function($) {
            $(document).ready(function() {
                var $sigdiv = $("#signature").jSignature({
                        'UndoButton': false
                    }),
                    $tools = $('#tools')
                $("#save").on('click', function() {
                    var data = $sigdiv.jSignature('getData', 'svg');
                    // const xhttp = new XMLHttpRequest();
                    // xhttp.onload = function() {
                    //     var text = document.getElementById('text');
                    //     text.innerText = this.responseText;
                    // //     const svgData = new XMLSerializer().serializeToString(this.responseText)
                    // // const svgDataBase64 = btoa(unescape(encodeURIComponent(svgData)))
                    // }
                    // xhttp.open("POST", "test.php");
                    // xhttp.send('aa');
                    $("#Signimg").val(data);
                });
                $('<button type="button" class="btn btn-secondary d-block mx-auto my-5" value="Reset">Reset</button>').bind('click', function(e) {
                    $sigdiv.jSignature('reset')
                }).appendTo($tools)
            })
        })(jQuery)

        document.getElementById('convert').addEventListener('click', function() {
            // const svgData = new XMLSerializer().serializeToString('<?=$data['Sign_image']?>')
            const svgDataBase64 = btoa(unescape(encodeURIComponent('<?=$data['Sign_image']?>')))
            const svgDataUrl = `data:image/svg+xml;charset=utf-8;base64,${svgDataBase64}`
            document.getElementById('text').innerText = svgDataUrl;
            document.getElementById('imgpng').src = svgDataUrl;
        })
    </script>
</body>

</html>
<!-- <script>
    main()

    function main() {
      const input = document.querySelector('#input')
      const output = document.querySelector('#output')

      const svgData = new XMLSerializer().serializeToString(input)
      const svgDataBase64 = btoa(unescape(encodeURIComponent(svgData)))

      const svgDataUrl = `data:image/svg+xml;charset=utf-8;base64,${svgDataBase64}`

      // console.log(svgData)
      // console.log(encodeURIComponent(svgData))
      // console.log(unescape(encodeURIComponent(svgData)))
      // console.log(btoa(unescape(encodeURIComponent(svgData))))

      const image = new Image()

      image.addEventListener('load', () => {
        const width = input.getAttribute('width')
        const height = input.getAttribute('height')
        const canvas = document.createElement('canvas')

        canvas.setAttribute('width', width)
        canvas.setAttribute('height', height)

        const context = canvas.getContext('2d')
        context.drawImage(image, 0, 0, width, height)

        const dataUrl = canvas.toDataURL('image/png')
        output.src = dataUrl
      })

      image.src = svgDataUrl
    }
  </script> -->