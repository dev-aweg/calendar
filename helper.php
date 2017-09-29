<html>
<head>
    <title>Przykład getElementById</title>

    <script type="text/javascript">

        function changeColor(element)
        {
            //var elem = document.getElementById("para1");
            element.style.backgroundColor = "blue";
        }
    </script>
</head>

<body>
<p id="para1">Jakiś tekst</p>
<button onclick="changeColor(this);">niebieski</button>
<button onclick="changeColor(this);">czerwony</button>
</body>
</html>