<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info | GoFit</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="user_info_box">
        <div class="userinfo">
            <div class="username">
                Name
                <br>
                <input type="text" id="text"  placeholder="Name">
                <br>
            </div>

            <div class="gender">
                Biological Gender
                <br>
                <select name="gender" id="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                </select>
                <br>
            </div>

            <div class="currentweight">
                Current Weight
                <br>
                <input type="text" id="text"  placeholder="Weight(Kg)">
                <br>
            </div>
            
            <div class="userheight">
                Height
                <br>
                <input type="text" id="text"  placeholder="Height(cm)">
                <br>
            </div>
            
            <div class="targetweight">
                Target Weight
                <br>
                <input type="text" id="text"  placeholder="Weight(kg)">
                <br>
            </div>
            
            <button>Save Info</button>
        </div>
    </div>
</body>
</html>