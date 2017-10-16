<html>
<head>
</head>
<body>

<div class="thesame">
    <button onclick="check(this)">taka sytuacja</button>
    <span> acoto</span>
</div>
<div class="thesame">
    <button onclick="check(this)">taka sytuacja</button>
    <span> acoto</span>
</div>

<script>
    function check(e) {
        $(this).parents
    }
</script>
</body>
</html>

<select <?php echo $event['button_status'] ?> name="type" onmousedown="this.value='';"
                                              onchange="jsFunction(this.value);">

    <option value='<?php echo $key['nazwa'] ?? "" ?>'>""</option>

</select>