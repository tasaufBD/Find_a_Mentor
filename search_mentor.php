<?php

session_start();

if(!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Search Mentor</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h1 align="center">Search Mentor</h1>

<hr>

<div style="width: 90%; max-width: 700px; margin: 30px auto;">

    <label for="search">Search Mentor</label>

    <br><br>

    <input
        type="text"
        id="search"
        style="width: 100%; padding: 10px; box-sizing: border-box;"
        placeholder="Enter name, skills, university or department">

    <br><br>
	<button type="button" onclick="searchMentor()">Search</button>
	<br><br>

    <a href="student_dashboard.php">Back to Dashboard</a>

    <br><br>

    <div id="result"></div>

</div>

<hr>

<p align="center">
    Copyright &copy; <?php echo date("Y"); ?>
</p>

<script>

var xhr = null;
var debounceTimer = null;

document.getElementById("search").addEventListener("input", function() {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(function() {
        searchMentor();
    }, 400);
});

function searchMentor()
{
    var value = document.getElementById("search").value.trim();

    if(xhr != null)
    {
        xhr.abort();
    }

    if(value == "")
    {
        document.getElementById("result").innerHTML = "";
        return;
    }

    xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function()
    {
        if(this.readyState == 4)
        {
            if(this.status == 200)
            {
                document.getElementById("result").innerHTML =
                    this.responseText;
            }
            else if(this.status != 0)
            {
                document.getElementById("result").innerHTML =
                    "<p>Search failed. Please try again.</p>";
            }
        }
    };

    xhr.open("POST", "search_mentor_result.php", true);

    xhr.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
    );

    xhr.send("value=" + encodeURIComponent(value));
}

</script>

</body>

</html>