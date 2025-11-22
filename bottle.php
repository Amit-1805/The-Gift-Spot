<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customizable Product</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        /* Your existing styles */
              body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff5f8; /* Soft Pinkish White */
            color: #333;
        }

        header {
            background-color: #ff9aa2; /* Soft Coral Pink */
            padding: 20px 0;
            text-align: center;
        }

        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #fff;
        }

         nav {
            margin-top: 15px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
        }

        .banner {
            background-color: #ffb7b2; /* Soft Coral */
            padding: 60px 20px;
            text-align: center;
            color: #fff;
        }

        .banner h1 {
            font-size: 48px;
            margin: 0;
        }

        .banner p {
            font-size: 24px;
            margin-top: 10px;
        }

        .main-content {
            padding: 40px 20px;
            text-align: center;
        }
        .product-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            margin-top: 20px;
        }

        .product-image img {
            width: 300px;
            height: auto;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .customization-options {
            margin-top: 20px;
        }

        .customization-options label {
            font-size: 16px;
            margin-right: 10px;
        }

        .customization-options .color-option {
            display: inline-block;
            width: 50px;
            height: 50px;
            cursor: pointer;
            margin-right: 10px;
            border: 2px solid transparent;
        }

        .customization-options .color-option img {
            width: 100%;
            height: auto;
        }

        .customization-options .color-option.selected {
            border-color: #000;
        }

        .customization-options input[type="file"] {
            padding: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .customization-options button {
            padding: 5px 10px;
            font-size: 16px;
            margin: 5px;
            cursor: pointer;
        }

        #lblProductTag {
            position: absolute;
            font-size: 21px;
            left: 47%;
            top: 48%;
        }

        #stickerImg {
            position: absolute;
            width: 50px;
            height: auto;
            display: none;
            left: 47%;
            top: 48%;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">Gifting Portal</div>
        <nav>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Login</a></li>
                <li><a href="product.html">Product</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">My Cart</a></li>
                <li><a href="#">Wishlist</a></li>
                <li><a href="#">Admin</a></li>
            </ul>
        </nav>
    </header>

    <div class="product-container">
        <h1>Customize Your Product</h1>

        <img id="ArrowRight" style="cursor: pointer; width: 47px;" src="arrowRight.png">
        <img id="ArrowLeft" style="cursor: pointer; width: 47px;" src="leftarrow.png">

        <div class="product-image">
            <label id="lblProductTag">MY MUG</label>
            <img id="productImg" src="bottle1.jpg" alt="Product Image">
            <img id="stickerImg" alt="Sticker">
        </div>

        <img id="DownArrow" style="cursor: pointer; width: 47px;" src="DownArrow.png">
        <img id="UpArrow" style="cursor: pointer; width: 47px;" src="uparrow.png">

        <div class="customization-options">
            <label for="color">Choose a color:</label>
            <div id="colorOptions">
                <div class="color-option" data-color="mug">
                    <img src="bottle1.jpg" alt="White Mug">
                </div>
                <div class="color-option" data-color="red">
                    <img src="bottle2.jpg" alt="Red Mug">
                </div>
                <div class="color-option" data-color="blue">
                    <img src="bottle3.jpg" alt="Blue Mug">
                </div>
                <div class="color-option" data-color="green">
                    <img src="bottle4.jpg" alt="Green Mug">
                </div>
            </div>

            <div>
                <form id="customizationForm" method="post" enctype="multipart/form-data" action="save.php">
                    <input type="file" name="coverimg" id="coverimg" />
                    <input type="hidden" name="textLeft" id="textLeft" />
                    <input type="hidden" name="textTop" id="textTop" />
                    <input type="hidden" name="stickerLeft" id="stickerLeft" />
                    <input type="hidden" name="stickerTop" id="stickerTop" />
                    <input type="hidden" name="stickerWidth" id="stickerWidth" />
                    <input type="hidden" name="color" id="color" />
                    <input type="hidden" name="productImageSrc" id="productImageSrc" />
                    <button type="button" id="removeText">Remove Text</button>
                    <button type="button" id="removeSticker">Remove Sticker</button>
                    <button type="button" id="zoomInSticker">Zoom In Sticker</button>
                    <button type="button" id="zoomOutSticker">Zoom Out Sticker</button>
                    <button type="button" id="saveCustomization" style="background-color: rgb(255, 102, 0);" class="btn btn-warning">Save Customization</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var defaultLeftVal = 47;
            var defaultTopVal = 48;
            var stickerSize = 50;  // Initial size of the sticker in pixels

            // Arrow functions to move the text label and sticker
            $("#ArrowRight").click(function() {
                defaultLeftVal += 1;
                $("#lblProductTag").css("left", defaultLeftVal + "%");
                $("#stickerImg").css("left", defaultLeftVal + "%");
            });

            $("#ArrowLeft").click(function() {
                defaultLeftVal -= 1;
                $("#lblProductTag").css("left", defaultLeftVal + "%");
                $("#stickerImg").css("left", defaultLeftVal + "%");
            });

            $("#DownArrow").click(function() {
                defaultTopVal += 1;
                $("#lblProductTag").css("top", defaultTopVal + "%");
                $("#stickerImg").css("top", defaultTopVal + "%");
            });

            $("#UpArrow").click(function() {
                defaultTopVal -= 1;
                $("#lblProductTag").css("top", defaultTopVal + "%");
                $("#stickerImg").css("top", defaultTopVal + "%");
            });

            // Initialize positions
            $("#lblProductTag").css("left", defaultLeftVal + "%");
            $("#lblProductTag").css("top", defaultTopVal + "%");
            $("#stickerImg").css("left", defaultLeftVal + "%");
            $("#stickerImg").css("top", defaultTopVal + "%");

            // Handle sticker upload
            $("#coverimg").change(function(event) {
                var stickerImg = $("#stickerImg");
                var reader = new FileReader();

                reader.onload = function(e) {
                    stickerImg.attr("src", e.target.result);
                    stickerImg.show();
                };

                reader.readAsDataURL(event.target.files[0]);
            });

            // Handle form submission
            $("#saveCustomization").click(function() {
                // Update hidden input fields
                $("#textLeft").val(defaultLeftVal + "%");
                $("#textTop").val(defaultTopVal + "%");
                $("#stickerLeft").val(defaultLeftVal + "%");
                $("#stickerTop").val(defaultTopVal + "%");
                $("#stickerWidth").val(stickerSize + "px");
                $("#color").val($(".color-option.selected").data("color"));
                $("#productImageSrc").val($("#productImg").attr("src"));

                // Submit the form
                $("#customizationForm").submit();
            });

            // Remove text label
            $("#removeText").click(function() {
                $("#lblProductTag").toggle();
                $(this).text(function(i, text) {
                    return text === "Remove Text" ? "Show Text" : "Remove Text";
                });
            });

            // Remove sticker
            $("#removeSticker").click(function() {
                $("#stickerImg").toggle();
                $(this).text(function(i, text) {
                    return text === "Remove Sticker" ? "Show Sticker" : "Remove Sticker";
                });
            });

            // Zoom in sticker
            $("#zoomInSticker").click(function() {
                stickerSize += 10; // Increase the size by 10 pixels
                $("#stickerImg").css("width", stickerSize + "px");
            });

            // Zoom out sticker
            $("#zoomOutSticker").click(function() {
                if (stickerSize > 10) { // Prevent sticker from becoming too small
                    stickerSize -= 10; // Decrease the size by 10 pixels
                    $("#stickerImg").css("width", stickerSize + "px");
                }
            });

            // Change mug color based on selected image
            $(".color-option").click(function() {
                $(".color-option").removeClass("selected");
                $(this).addClass("selected");

                var selectedColor = $(this).data("color");
                $("#productImg").attr("src", selectedColor + ".jpg");
            });

            // Initialize first selection as selected
            $(".color-option:first").addClass("selected");
        });
    </script>
</body>
</html>