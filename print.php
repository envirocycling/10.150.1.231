<!DOCTYPE html>
<html>
    <head>
        <style>
            @media screen {
                p {
                    font-family: verdana,sans-serif;
                    font-size: 14px;
                }
            }

            @media print {
                p {
                    font-size: 20px;
                    color: red;
                    size: 8.5in 5.5in;
                    size: portrait;
                    margin-top: 100px;
                }
            }
        </style>
    </head>
    <body>

        <h2>The @media Rule</h2>
        <p>The @media rule allows different style rules for different media in the same style sheet.</p>
        <p>The style in this example tells the browser to display a 14 pixels Verdana font on the screen.
            However, if the page is printed, the text will be in 20 pixels Verdana font, and in a red color.</p>
        <p><b>See it yourself !</b> Print this page (or open Print Preview), and you will see
            that the text will be displayed in a larger font size, and in red color.</p>

    </body>
</html>
